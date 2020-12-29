<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\User as UserService;


/**
 * @Route("/admin", name="admin_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user/list", name="user_list")
     */
    public function userListAction(UserService $userService, Request $request)
    {
        $admin = $this->getUser();

        $perPage = 50;
        $pageCount = 0;
        $currentPage = (int) $request->query->get('currentPage', 1);

        $email = $request->query->get('email');

        if ($currentPage == "") {
            $currentPage = 1;
        }

        $userList = $userService->getAll($currentPage, $perPage, $email);

        if (!empty($userList['total']) && $userList['total'] > $perPage) {
            $pageCount = ceil($userList['total'] / $perPage);
        }

        return $this->render('Admin/User/list.html.php', [
            'admin' => $admin,
            'userList' => $userList,
            'pageCount' => $pageCount,
            'currentPage' => $currentPage,
        ]);
    }
}
