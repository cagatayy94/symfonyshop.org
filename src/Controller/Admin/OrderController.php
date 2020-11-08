<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\Order as OrderService;
use App\Service\Admin\Category as CategoryService;
use App\Sdk\Excel as ExcelService;


/**
 * @Route("/admin", name="admin_")
 */
class OrderController extends AbstractController
{
     /**
     * @Route("/order-list", name="order_list")
     */
    public function orderListAction(Request $request, OrderService $orderService, ExcelService $excelService)
    {
        $admin = $this->getUser();

        $perPage = 5;
        $pageCount = 0;
        $currentPage = (int) $request->query->get('currentPage', 1);

        $name = $request->query->get('name');
        $orderId = $request->query->get('orderId');
        $createdAtStart = $request->query->get('createdAtStart');
        $createdAtEnd = $request->query->get('createdAtEnd');
        $excelExport = $request->query->get('excelExport');

        if ($currentPage == "") {
            $currentPage = 1;
        }

        if ($excelExport) {
            $perPage = PHP_INT_MAX;
        }

        $orders = $orderService->getAll($currentPage, $pageCount, $perPage, $createdAtStart, $createdAtEnd, $name, $orderId);

        if (!empty($orders['total']) && $orders['total'] > $perPage) {
            $pageCount = ceil($orders['total'] / $perPage);
        }

        $data = [
            'admin' => $admin,
            'orders' => $orders,
            'pageCount' => $pageCount,
            'currentPage' => $currentPage,
            'excelExport' => $excelExport,
            'createdAtStart' => $createdAtStart,
            'createdAtEnd' => $createdAtEnd,
            'name' => $name,
            'orderId' => $orderId,
        ];

        $view = 'Admin/Order/list.html.php';

        if ($excelExport) {
            return $excelService->renderExcel($view, $data, 'urun-listesi');
        }

        return $this->render($view, $data);
    }

}
