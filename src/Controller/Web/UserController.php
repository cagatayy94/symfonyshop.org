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

    /**
     * @Route("/email-activation", name="email_activation")
     */
    public function emailActivationAction(Request $request, UserService $userService)
    {
        $email = $request->query->get('email');
        $activationCode = $request->query->get('activationCode');

        try {
            $userService->approveEmail($email, $activationCode);
            $result = [
                'success' => true,
                'type' => 1
            ];
            
        } catch (\Exception $e) {
            $result = [
                'success' => false,
                'type' => $e->getMessage()
            ];
        }

        return $this->render('Web/Default/email-approve.html.php', [
            'result' => $result
        ]);
    }

    /**
     * @Route("/new-activation-code", name="new_email_activation_code")
     */
    public function sendNewCodeAction(Request $request, UserService $userService)
    {
        $email = $request->request->get('email');

        if ($request->isMethod('post')) {
            $result = true;
        }else{
            $result = null;
        }

        $userService->sendNewCode($email);

        return $this->render('Web/Default/new-activation-code.html.php', [
            'result' => $result
        ]);
    }

    /**
     * @Route("/email-unsubscribe", name="email_unsubscribe")
     */
    public function unsubscribeAction(Request $request, UserService $userService)
    {
        $email = $request->query->get('email');
        $activationCode = $request->query->get('activationCode');

        print_r($email);echo "<pre>";

        die();


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
