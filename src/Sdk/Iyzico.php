<?php

namespace App\Sdk;

use App\Sdk\ServiceTrait;


class Iyzico
{
    use ServiceTrait;

    /**
     * @var options
     */
    private $options;

    public function __construct($key, $secret, $baseUrl)
    {   
        $options = new \Iyzipay\Options();
        $options->setApiKey($key);
        $options->setSecretKey($secret);
        $options->setBaseUrl($baseUrl);
        $this->options = $options;
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
        $request->setCallbackUrl("https://www.merchant.com/callback");

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

        return $checkoutFormInitialize->getCheckoutFormContent().'<div id="iyzipay-checkout-form" class="responsive"></div>';
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
        $buyerObj->setSurname($buyer['surname']);
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
}
