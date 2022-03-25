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
    const LIMIT_PER_PAGE = 5;

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserService $userService)
    {
        $name = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
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

        $result = null;
        if ($request->isMethod('post')) {
            $result = true;
        }

        $userService->sendNewCode($email);

        return $this->render('Web/Default/new-activation-code.html.php', [
            'result' => $result
        ]);
    }

    /**
     * @Route("/reset-password", name="reset_password")
     */
    public function forgotPasswordAction(Request $request, UserService $userService)
    {
        $email = $request->request->get('email');

        $result = null;
        if ($request->isMethod('post')) {
            $result = true;
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
     * @Route("/profile", name="profile")
     */
    public function profileAction(UserService $userService)
    {
        $user = $this->getUser();

        $addresses = $userService->getUserAccountAddresses($user);

        return $this->render('Web/User/user-profile.html.php', [
            'user'          => $user,
            'addresses'     => $addresses,
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

    /**
     * @Route("/get-user-addresses", name="get_user_addresses")
     */
    public function getUserAccountAddressesAction(UserService $userService)
    {
        $user = $this->getUser();

        try {
            $addresses = $userService->getUserAccountAddresses($user);

            return new JsonResponse([
                'success' => true,
                'addresses' => $addresses
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
     * @Route("/add-user-address", name="add_user_addresses")
     */
    public function addUserAddressesAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();

        $addressName = $request->request->get('address_name');
        $fullName = $request->request->get('full_name');
        $address = $request->request->get('address');
        $county = $request->request->get('county');
        $city = $request->request->get('city');
        $mobile = $request->request->get('mobile');

        try {
            $addresses = $userService->addUserAddresses($user, $addressName, $fullName, $address, $county, $city, $mobile);

            return new JsonResponse([
                'success' => true,
                'addresses' => $addresses
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
     * @Route("/remove-user-address", name="remove_user_addresses")
     */
    public function removeUserAddressAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();

        $addressId = $request->request->get('id');

        try {
            $userService->removeUserAddress($user, $addressId);

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
     * @Route("/update-user-address", name="update_user_addresses")
     */
    public function updateUserAddressesAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();

        $addressName = $request->request->get('address_name');
        $fullName = $request->request->get('full_name');
        $address = $request->request->get('address');
        $county = $request->request->get('county');
        $city = $request->request->get('city');
        $mobile = $request->request->get('mobile');
        $addressId = $request->request->get('address_id');

        try {
            $userService->updateUserAddresses($user, $addressId, $addressName, $fullName, $address, $county, $city, $mobile);

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
     * @Route("/get-user-account-favorites", name="get-user-account-favorites")
     */
    public function getUserAccountFavoritesAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();

        $page = $request->query->get('page');

        $pageCount = 0;

        try {
            $favorites = $userService->getUserAccountFavorites($user, self::LIMIT_PER_PAGE, $page);

            if (!empty($favorites[0]['total_count']) && $favorites[0]['total_count'] > self::LIMIT_PER_PAGE) {
                $pageCount = ceil($favorites[0]['total_count'] / self::LIMIT_PER_PAGE);
            }

            return new JsonResponse([
                'success'   => true,
                'favorites' => $favorites,
                'total_count' => isset($favorites[0]['total_count']) ? $favorites[0]['total_count'] : 0 ,
                'perPage' => self::LIMIT_PER_PAGE,
                'pageCount' => $pageCount,
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
     * @Route("/remove-user-favorite", name="remove_user_favorite")
     */
    public function removeUserFavoriteAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();

        $favoriteId = $request->request->get('id');

        try {
            $userService->removeUserFavorite($user, $favoriteId);

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
     * @Route("/get-user-account-comments", name="get-user-account-comments")
     */
    public function getUserAccountCommentsAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();

        $page = $request->query->get('page');

        $pageCount = 0;

        try {
            $comments = $userService->getUserAccountComments($user, self::LIMIT_PER_PAGE, $page);

            if (!empty($comments[0]['total_count']) && $comments[0]['total_count'] > self::LIMIT_PER_PAGE) {
                $pageCount = ceil($comments[0]['total_count'] / self::LIMIT_PER_PAGE);
            }

            return new JsonResponse([
                'success'   => true,
                'comments' => $comments,
                'total_count' => isset($comments[0]['total_count']) ? $comments[0]['total_count'] : 0 ,
                'perPage' => self::LIMIT_PER_PAGE,
                'pageCount' => $pageCount,
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
     * @Route("/remove-user-comment", name="remove_user_comment")
     */
    public function removeUserCommentAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();

        $commentId = $request->request->get('id');

        try {
            $userService->removeUserComment($user, $commentId);

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
     * @Route("/get-user-account-orders", name="get_user_account_orders")
     */
    public function getUserAccountOrdersAction(Request $request, UserService $userService)
    {
        $user = $this->getUser();

        $page = $request->query->get('page') ? $request->query->get('page') : 1;

        $pageCount = 0;

        try {
            $orders = $userService->getUserOrderList($user, self::LIMIT_PER_PAGE, $page);

            if (!empty($orders[0]['total_count']) && $orders[0]['total_count'] > self::LIMIT_PER_PAGE) {
                $pageCount = ceil($orders[0]['total_count'] / self::LIMIT_PER_PAGE);
            }

            return new JsonResponse([
                'success'   => true,
                'orders' => $orders,
                'total_count' => isset($orders[0]['total_count']) ? $orders[0]['total_count'] : 0 ,
                'perPage' => self::LIMIT_PER_PAGE,
                'pageCount' => $pageCount,
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
