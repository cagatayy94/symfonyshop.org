<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\BankTransfer as BankTransferService;
use App\Service\Admin\SiteSettings as SiteSettingsService;

/**
 * @Route("/admin", name="admin_")
 */
class BankTransferController extends AbstractController
{
    /**
     * @Route("/money-order-list", name="money_order_list")
     */
    public function bankTransferListAction(Request $request, BankTransferService $bankTransferService, SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();
        $allBanks = $siteSettingsService->bankSettings();

        $perPage = 50;
        $pageCount = 0;
        $currentPage = (int) $request->query->get('currentPage', 1);

        $name = $request->query->get('name');
        $email = $request->query->get('email');
        $mobile = $request->query->get('mobile');
        $bankId = $request->query->get('bankId');
        $isApproved = $request->query->get('isApproved');
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        if ($isApproved == "") {
            $isApproved = null;
        }

        if ($currentPage == "") {
            $currentPage = 1;
        }

        $bankTrasferList = $bankTransferService->getAll($currentPage, $pageCount, $perPage, $name, $email, $mobile, $bankId, false, $isApproved, $startDate, $endDate);

        if (!empty($bankTrasferList['total']) && $bankTrasferList['total'] > $perPage) {
            $pageCount = ceil($bankTrasferList['total'] / $perPage);
        }

        return $this->render('Admin/BankTransfer/list.html.php', [
            'admin' => $admin,
            'bankTrasferList' => $bankTrasferList,
            'allBanks' => $allBanks,
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'bankId' => $bankId,
            'isApproved' => $isApproved,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pageCount' => $pageCount,
            'currentPage' => $currentPage,
        ]);
    }

    /**
     * @Route("/money-order-list-deleted", name="money_order_list_deleted")
     */
    public function bankTransferDeletedListAction(Request $request, BankTransferService $bankTransferService, SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();
        $allBanks = $siteSettingsService->bankSettings();

        $perPage = 50;
        $pageCount = 0;
        $currentPage = (int) $request->query->get('currentPage', 1);

        $name = $request->query->get('name');
        $email = $request->query->get('email');
        $mobile = $request->query->get('mobile');
        $bankId = $request->query->get('bankId');
        $isApproved = $request->query->get('isApproved');
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        if ($isApproved == "") {
            $isApproved = null;
        }

        if ($currentPage == "") {
            $currentPage = 1;
        }

        $bankTrasferList = $bankTransferService->getAll($currentPage, $pageCount, $perPage, $name, $email, $mobile, $bankId, true, $isApproved, $startDate, $endDate);

        if (!empty($bankTrasferList['total']) && $bankTrasferList['total'] > $perPage) {
            $pageCount = ceil($bankTrasferList['total'] / $perPage);
        }

        return $this->render('Admin/BankTransfer/list-deleted.html.php', [
            'admin' => $admin,
            'bankTrasferList' => $bankTrasferList,
            'allBanks' => $allBanks,
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'bankId' => $bankId,
            'isApproved' => $isApproved,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pageCount' => $pageCount,
            'currentPage' => $currentPage,
        ]);
    }

    /**
     * @Route("/money-order/delete/{moneyOrderId}", name="money_order_delete")
     */
    public function deleteMoneyOrderAction($moneyOrderId, BankTransferService $bankTransferService)
    {
        try {
            $bankTransferService->deleteMoneyOrder($moneyOrderId);

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
     * @Route("/money-order/undelete/{moneyOrderId}", name="money_order_undelete")
     */
    public function undeleteMoneyOrderAction($moneyOrderId, BankTransferService $bankTransferService)
    {
        try {
            $bankTransferService->undeleteMoneyOrder($moneyOrderId);

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
     * @Route("/money-order/update", name="money_order_update")
     */
    public function updateMoneyOrderAction(Request $request, BankTransferService $bankTransferService)
    {
        $type = $request->query->get('type');
        $id = $request->query->get('id');

        try {
            $bankTransferService->updateMoneyOrder($id, $type);

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
