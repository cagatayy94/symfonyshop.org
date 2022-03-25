<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Web\SiteSettings as SiteSettings;
use App\Service\Web\OrderNotice as OrderNoticeService;


class BankTransferController extends AbstractController
{
    /**
     * @Route("/order-notice", name="order_notice")
     */
    public function orderNotice(SiteSettings $siteSettings)
    {
        $bankAccounts = $siteSettings->getBankAccounts();

        return $this->render('Web/Strings/order_notice.html.php', [

            'bankAccounts' => $bankAccounts

        ]);
    }

    /**
     * @Route("/order-notice", name="order_notice_submit")
     */
    public function orderNoticeSubmit(Request $request, OrderNoticeService $orderNoticeService)
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $bankId = $request->request->get('bankId');
        $mobile = $request->request->get('mobile');
        $message = $request->request->get('message');

        try {
            $orderNoticeService->createOrderNotice($name, $email, $bankId, $mobile, $message);

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
