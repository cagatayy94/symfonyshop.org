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
use App\Service\Web\User as UserService;


class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserService $userService)
    {
        $name = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $phone = $request->request->get('phone');
        $agreement = $request->request->get('agreement');
        $mobile = $request->request->get('mobile');
        $ipAddress = $request->getClientIp();

        try {
            $userService->create($name, $email, $password, $agreement, $ipAddress, $mobile);

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
