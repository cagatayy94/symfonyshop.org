<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Web\Order as OrderService;
use App\Service\Web\User as UserService;
use App\Sdk\Iyzico as IyzicoSdk;

class OrderController extends AbstractController
{
    /**
     * @Route("/order/bank-transfer", name="order_bank_transfer")
     */
    public function getCartTotalAndQuantityAction(Request $request, OrderService $orderService)
    {
        $user = $this->getUser();

        $ipAddress = $request->getClientIp();

        try {
            $orderService->createOrder($user->getId(), $ipAddress, 'bank_transfer');

            return $this->render('Web/Order/order_success.html.php', ['user' => $user]);
        } catch (\Exception $exception) {
            return $this->render('Web/Order/order_fail.html.php', ['user' => $user]);
        }
    }

    /**
     * @Route("/get/iyzico-form", name="get_iyzico_form")
     */
    public function getIyzicoFormAction(Request $request, OrderService $orderService, IyzicoSdk $iyzicoSdk)
    {
        $user = $this->getUser();

        $ipAddress = $request->getClientIp();

        try {

            $details = $orderService->getCartDetailForIyzico($user, $ipAddress);
            $data = $iyzicoSdk->renderForm($details);


            if (!$data['status']) {
                throw new \Exception("Form oluşturulurken bir sorun oluştu banka havalesi ile ödemeyi deneyebilirsiniz");
            }

            return new JsonResponse([
                'success' => true,
                'data' => $data['form']
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
     * @Route("/get/iyzico-form/result", name="get_iyzico_form_result")
     */
    public function getIyzicoFormResultAction(Request $request, OrderService $orderService, IyzicoSdk $iyzicoSdk, UserService $userService)
    {
        $user = $this->getUser();
        $token = $request->request->get('token');
        $ipAddress = $request->getClientIp();

        $data = $iyzicoSdk->getPaymentResult($token);

        if (!$data['status']) {
            throw new \Exception("Ödeme sonucu alınırken bir hata oluştu");
        }

        if ($data['paymentStatus'] != 'SUCCESS') {
            return $this->render('Web/Order/order_fail.html.php', 
                [
                    'user' => $user,
                ]
            );
        }

        if ($data['paymentStatus'] == 'SUCCESS') {

            if (!$user) {
                $userId = $userService->getUserAccountByCartId($data['basketId']);
            }else{
                $userId = $user->getId();
            }

            $orderService->createOrder($userId, $ipAddress, 'credit_card', $data['rawResult']);
            return $this->render('Web/Order/order_success.html.php', 
                [
                    'user' => $user,
                ]
            );
        }
    }
}
