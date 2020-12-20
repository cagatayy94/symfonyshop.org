<?php
namespace App\Sdk;

use App\Sdk\ServiceTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as Router;
use Doctrine\DBAL\Driver\Connection as Connection;
use App\Service\Web\Order as OrderService;

class Iyzico
{
    use ServiceTrait;

    /**
     * @var options
     */
    private $options;

    /**
     * @var router
     */
    private $router;

    public function __construct(Router $router, Connection $connection)
    {
        $settings = $connection->executeQuery('
            SELECT
                iyzico_api_key,
                iyzico_secret_key,
                iyzico_base_url
            FROM
                iyzico
            LIMIT 1
        ')->fetch();

        $options = new \Iyzipay\Options();
        $options->setApiKey($settings['iyzico_api_key']);
        $options->setSecretKey($settings['iyzico_secret_key']);
        $options->setBaseUrl($settings['iyzico_base_url']);

        $this->options = $options;
        $this->router = $router;
    }

    public function renderForm($details)
    {
        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($this->uId());
        $request->setPrice($details['total_order_amount']);
        $request->setPaidPrice($details['grand_total_order_amount']);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setBasketId($details['order_id']);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl($this->router->generate('get_iyzico_form_result', [], 0));

        //set buyer
        $buyer = $this->setBuyer($details['buyer']);
        $request->setBuyer($buyer);

        //set shipping address
        $shippingAddress = $this->setShippingAddress($details['shipping_address']);
        $request->setShippingAddress($shippingAddress);

        //set billing address
        $billingAddress = $this->setBillingAddress($details['billing_address']);
        $request->setBillingAddress($billingAddress);

        //set basket items
        $basketItems = $this->setBasketItems($details['cart_items']);
        $request->setBasketItems($basketItems);

        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $this->options);

        $status = $checkoutFormInitialize->getStatus();
        $errorMessage = $checkoutFormInitialize->getErrorMessage();
        $form = $checkoutFormInitialize->getCheckoutFormContent().'<div id="iyzipay-checkout-form" class="responsive"></div>';

        if($errorMessage){
            throw new \Exception("Form oluşturulurken bir sorun oluştu. Lütfen sayfayı yenileyip tekrar deneyin.");
        }
        
        return [
            'status' => $status,
            'errorMessage' => $errorMessage,
            'form' => $form
        ];
    }

    public function setBillingAddress($billingAddress)
    {
        $billingAddressObj = new \Iyzipay\Model\Address();
        $billingAddressObj->setContactName($billingAddress['name'].' '.$billingAddress['surname']);
        $billingAddressObj->setCity($billingAddress['city']);
        $billingAddressObj->setCountry("Turkey");
        $billingAddressObj->setAddress($billingAddress['address']);
        return $billingAddressObj;
    }

    public function setShippingAddress($shippingAddress)
    {
        $shippingAddressObj = new \Iyzipay\Model\Address();
        $shippingAddressObj->setContactName($shippingAddress['name'].' '.$shippingAddress['surname']);
        $shippingAddressObj->setCity($shippingAddress['city']);
        $shippingAddressObj->setCountry("Turkey");
        $shippingAddressObj->setAddress($shippingAddress['address']);
        return $shippingAddressObj;
    }

    public function setBuyer($buyer)
    {
        $buyerObj = new \Iyzipay\Model\Buyer();
        $buyerObj->setId($buyer['id']);
        $buyerObj->setName($buyer['name']);
        $buyerObj->setSurname($buyer['surname'] ? $buyer['surname'] : $buyer['name']);
        $buyerObj->setEmail($buyer['email']);
        $buyerObj->setIdentityNumber("74300864791");
        $buyerObj->setRegistrationAddress($buyer['address']);
        $buyerObj->setIp($buyer['ip']);
        $buyerObj->setCity($buyer['city']);
        $buyerObj->setCountry($buyer['country']);

        return $buyerObj;
    }

    public function setBasketItems($cartItems)
    { 
        $basketItems = array();

        foreach ($cartItems as $key => $value) {
            $basketItem = new \Iyzipay\Model\BasketItem();
            $basketItem->setId($value['product_id']);
            $basketItem->setName($value['product_name']);
            $basketItem->setCategory1("Default");
            $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $basketItem->setPrice($value['total']);
            $basketItems[$key] = $basketItem;
        }

        return $basketItems;
    }

    public function getPaymentResult($token)
    { 
        $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setToken($token);

        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $this->options);

        $status = $checkoutForm->getStatus();
        $paymentStatus = $checkoutForm->getPaymentStatus();
        $rawResult = $checkoutForm->getRawResult();
        $basketId = $checkoutForm->getBasketId();
        
        return [
            'status' => $status,
            'paymentStatus' => $paymentStatus,
            'rawResult' => $rawResult,
            'basketId' => $basketId,
        ];
    }
}
