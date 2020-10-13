<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Web\Cart as CartService;
use App\Service\Web\SiteSettings as SiteSettings;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add", name="cart_add")
     */
    public function addCartAction(Request $request, CartService $cartService)
    {
        $productId = $request->request->get('productId');
        $variantId = $request->request->get('variant');
        $user = $this->getUser();

        try {
            $cartService->addCart($productId, $variantId, $user->getId());

            return new JsonResponse([
                'success' => true,
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ]);
        }
    }

    /**
     * @Route("/cart/get-total-and-quantity", name="cart_get_total_and_quantity")
     */
    public function getCartTotalAndQuantityAction(Request $request, CartService $cartService)
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse([
                'success' => true,
                'data' => [
                    [
                        'sum' => "0.00 ₺",
                        'count' => 0
                    ]
                ]
            ]);
        }
        try {
            $cart = $cartService->getCartTotalAndQuantity($user->getId());

            return new JsonResponse([
                'success' => true,
                'data' => $cart
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ]);
        }
    }

    /**
     * @Route("/cart/detail", name="cart_detail")
     */
    public function cartDetailAction(Request $request, CartService $cartService)
    {
        $user = $this->getUser();

        return $this->render('Web/Cart/cart_detail.html.php', 
            [
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/cart/get-data", name="cart_get_data")
     */
    public function getCartData(Request $request, CartService $cartService)
    {
        $user = $this->getUser();

        try {
            $cart = $cartService->getCart($user->getId());

            return new JsonResponse([
                'success' => true,
                'data' => $cart
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ]);
        }
    }

    /**
     * @Route("/cart/remove-row", name="cart_remove_row")
     */
    public function cartRemoveItemAction(Request $request, CartService $cartService)
    {
        $user = $this->getUser();
        $cartId = $request->request->get('cartId');

        try {
            $cartService->remove($user->getId(), $cartId);

            return new JsonResponse([
                'success' => true,
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ]);
        }
    }

    /**
     * @Route("/cart/update-quantity", name="cart_update_quantity")
     */
    public function cartUpdateQuantityAction(Request $request, CartService $cartService)
    {
        $user = $this->getUser();

        $cartId = $request->request->get('cartId');
        $type = $request->request->get('type');

        try {

            $res = $cartService->updateQuantity($user->getId(), $cartId, $type);

            return new JsonResponse([
                'success' => true,
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ]);
        }
    }

    /**
     * @Route("/cart/cargo-select", name="cart_cargo_select")
     */
    public function cartCargoSelectAction(Request $request, CartService $cartService)
    {
        $user = $this->getUser();
        $cargoCompany = $cartService->getCargoCompanyForCart();

        return $this->render('Web/Cart/cargo_select.html.php', 
            [
                'user' => $user,
                'cargoCompany' => $cargoCompany,
            ]
        );
    }

    /**
     * @Route("/cart/check-out", name="cart_check_out")
     */
    public function cartCheckOutAction(Request $request, CartService $cartService)
    {
        $user = $this->getUser();

        return $this->render('Web/Cart/cart_check_out.html.php', 
            [
                'user' => $user
            ]
        );
    }
    /**
     * @Route("/cart/update-address-and-cargo", name="cart_update_address_and_cargo")
     */
    public function cartUpdateAddressAndCargoAction(Request $request, CartService $cartService)
    {
        $user = $this->getUser();

        $billingAddressId = $request->request->get('billing_address_id');
        $shippingAddressId = $request->request->get('shipping_address_id');
        $shippingCompanyId = $request->request->get('shipping_company_id');

        try {

            $cartService->cartUpdateAddressAndCargo($user, $billingAddressId, $shippingAddressId, $shippingCompanyId);

            return new JsonResponse([
                'success' => true,
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ]);
        }
    }
}
