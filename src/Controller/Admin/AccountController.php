<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\Account as AccountService;


/**
 * @Route("/admin", name="admin_")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account/list", name="account_list")
     */
    public function adminListAction(AccountService $accountService)
    {
        $admin = $this->getUser();

        $adminAccountList = $accountService -> getAll();
        $adminProfileList = $accountService -> getAllProfile();

        return $this->render('Admin/Account/list.html.php', [
            'admin' => $admin,
            'adminAccountList' => $adminAccountList,
            'adminProfileList' => $adminProfileList
        ]);
    }


    /**
     * @Route("/account/create", name="account_create")
     */
    public function adminCreateAction(Request $request, AccountService $accountService)
    {
        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $email = $request->request->get('email');
        $mobile = $request->request->get('mobile');
        $profileId = $request->request->get('profile_id');
        $password = $request->request->get('password');
        $passwordRepeat = $request->request->get('password_repeat');

        try {
            $accountService->create($name, $surname, $email, $password, $passwordRepeat, $mobile, $profileId);

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
     * @Route("/account/detail/{adminId}", name="account_detail")
     */
    public function adminAccountDetailAction(AccountService $accountService, $adminId)
    {
        $admin = $this->getUser();

        $adminDetails       = $accountService->getAccountDetail($adminId);
        $adminProfileList   = $accountService->getAllProfile();

        return $this->render('Admin/Account/detail.html.php',[
            'admin'             => $admin,
            'adminDetails'      => $adminDetails,
            'adminProfileList'  => $adminProfileList,
        ]);
    }

    /**
     * @Route("/account/update", name="account_update")
     */
    public function adminUpdateAction(Request $request, AccountService $accountService)
    {
        $admin = $this->getUser();

        $id = $request->request->get('adminId');
        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $email = $request->request->get('email');
        $mobile = $request->request->get('mobile');
        $profileId = $request->request->get('profile_id');
        $password = $request->request->get('password');
        $passwordRepeat = $request->request->get('password_repeat');

        if ($password == "") {
            $password = NULL;
        }

        if ($passwordRepeat == "") {
            $passwordRepeat = NULL;
        }

        try {
            $accountService->update($id, $name, $surname, $email, $password, $passwordRepeat, $mobile, $profileId);

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
     * @Route("/account/delete/{adminId}", name="account_delete")
     */
    public function adminDeleteAction($adminId, AccountService $accountService)
    {
        try {
            $accountService->delete($adminId);

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
     * @Route("/profile/list", name="profile_list")
     */
    public function adminProfileListAction(AccountService $accountService)
    {
        $admin = $this->getUser();

        $adminProfileList = $accountService->getAllProfile();

        return $this->render('Admin/Profile/list.html.php', [
            'admin' => $admin,
            'adminProfileList' => $adminProfileList,
        ]);
    }

    /**
     * @Route("/profile/create", name="profile_create")
     */
    public function adminProfileAction(Request $request, AccountService $accountService)
    {
        $admin = $this->getUser();

        $name = $request->request->get('profileName');
        
        try {
            $accountService->profileCreate($name);

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
     * @Route("/profile/detail/{profileId}", name="profile_detail")
     */
    public function adminProfileDetailAction(AccountService $accountService, $profileId)
    {
        $admin = $this->getUser();

        $profileDetail = $accountService->getProfileDetail($profileId);

        $allPermissions = $accountService->getAllPermissions();

        return $this->render('Admin/Profile/detail.html.php', [
            'admin'            => $admin,
            'profileDetail'    => $profileDetail,
            'allPermissions'   => $allPermissions
        ]);
    }

    /**
     * @Route("/profile/update", name="profile_update")
     */
    public function adminProfileUpdateAction(Request $request, AccountService $accountService)
    {
        $admin = $this->getUser();

        $profileId = $request->request->get('profileId');
        $name = $request->request->get('name');
        $permissions = $request->request->get('permissions');

        try {
            $accountService->profileUpdate($profileId, $name, $permissions);

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
     * @Route("/profile/delete/{profileId}", name="profile_delete")
     */
    public function profileDeleteAction($profileId, AccountService $accountService)
    {
        try {
            $accountService->profileDelete($profileId);

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
