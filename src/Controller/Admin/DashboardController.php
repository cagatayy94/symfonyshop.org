<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\Dashboard as DashboardService;


/**
 * @Route("/admin", name="admin_")
 */
class DashboardController extends AbstractController
{

    /**
     * @Route("/", name="dashboard")
     */
    public function dashboardAction()
    {
        $admin = $this->getUser();
        
        if (!$admin->hasRole('site_dashboard_show')) {
            throw new \Exception('Yetkisiz erişim');
        }

        return $this->render('Admin/Default/dashboard.html.php', ['admin' => $admin]);
    }

    /**
     * @Route("/dashboard/data", name="dashboard_data")
     */
    public function dashboardDataAction(DashboardService $dashboardService)
    {
        try {
            $data = $dashboardService->getData();

            return new JsonResponse([
                'success' => true,
                'data' => $data
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
