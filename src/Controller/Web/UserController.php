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
     * @Route("/parolami-sifirla", name="reset_password")
     */
    public function forgotPasswordAction(Request $request, UserService $userService)
    {
        $email = $request->request->get('email');

        if ($request->isMethod('post')) {
            $result = true;
        }else{
            $result = null;
        }

        $userService->sendForgotPassword($email);

        return $this->render('Web/Default/reset-password.html.php', [
            'result' => $result
        ]);
    }

    /**
     * @Route("/new-password", name="new_password")
     */
    public function newPasswordAction(Request $request, UserService $userService)
    {
        $email = $request->query->get('email');
        $code = $request->query->get('activationCode');

        $isMatching = $userService->isEmailAndCodeIsMatching($email, $code);

        if (!$isMatching) {
            return $this->redirectToRoute('index');
        }

        return $this->render('Web/Default/new-password.html.php', [
            'email' => $email,
            'code' => $code
        ]);
    }

    /**
     * @Route("/new-password-create", name="new_password_create")
     */
    public function newPasswordCreateAction(Request $request, UserService $userService)
    {
        $password = $request->request->get('password');
        $passwordRepeat = $request->request->get('passwordRepeat');
        $code = $request->request->get('code');
        $email = $request->request->get('email');

        try {
            $userService->resetForgotPassword($email, $code, $password, $passwordRepeat);

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
     * @Route("/email-unsubscribe", name="email_unsubscribe")
     */
    public function unsubscribeAction(Request $request, UserService $userService)
    {
        $email = $request->query->get('email');

        try {
            $userService->unsubscribe($email);
            echo "Tüm mail listelerinden çıkarıldınız";
            die();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            die();
        }
    }

    /**
     * @Route("/email-subscribe", name="email_subscribe")
     */
    public function subscribeAction(Request $request, UserService $userService)
    {
        $email = $request->request->get('email');

        try {
            $userService->subscribe($email);

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
     * @Route("/profilim", name="profile")
     */
    public function profileAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();
    
        return $this->render('Web/User/user-profile.html.php', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/change-password-on-profile", name="change_password_on_profile")
     */
    public function changePasswordOnProfileAction(Request $request, UserService $userService)
    {
        $currentPassword = $request->request->get('current_password');
        $newPassword = $request->request->get('new_password');
        $newPasswordRepeat = $request->request->get('new_password_repeat');

        $user = $this->getUser();

        try {
            $userService->changePasswordOnProfile($user, $currentPassword, $newPassword, $newPasswordRepeat);

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
     * @Route("/change-mobile-on-profile", name="change_mobile_on_profile")
     */
    public function changeMobileOnProfileAction(Request $request, UserService $userService)
    {
        $mobile = $request->request->get('mobile');

        $user = $this->getUser();

        try {
            $userService->changeMobileOnProfile($user, $mobile);

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
