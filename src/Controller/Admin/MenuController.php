<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\Menu as MenuService;
use App\Service\Admin\Category as CategoryService;

/**
 * @Route("/admin", name="admin_")
 */
class MenuController extends AbstractController
{
    /**
     * @Route("/menu-list", name="menu_list")
     */
    public function listAction(Request $request, MenuService $menuService)
    {
        $admin = $this->getUser();

        $menus = $menuService->getAll();

        return $this->render('Admin/Menu/list.html.php', [
            'admin' => $admin,
            'menus' => $menus
        ]);
    }

    /**
     * @Route("/menu/create", name="menu_create")
     */
    public function createAction(Request $request, MenuService $menuService)
    {
        $admin = $this->getUser();

        $name = $request->request->get('menuName');
        
        try {
            $menuService->create($name);

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
     * @Route("/menu/detail/{menuId}", name="menu_detail")
     */
    public function detailAction($menuId, MenuService $menuService, CategoryService $categoryService)
    {
        $admin = $this->getUser();

        $menuDetail = $menuService->detail($menuId);
        $categories = $categoryService->getAll();

        $allCategories = [];
        foreach ($categories as $value) {
            $allCategories[$value['id']] = [
                'slug' => $value['slug'],
                'name' => $value['name'],
            ];
        }

        return $this->render('Admin/Menu/detail.html.php', [
            'admin'         => $admin,
            'menuDetail'    => $menuDetail,
            'categories'    => $allCategories,
            'menuId'        => $menuId
        ]);
    }

    /**
     * @Route("/menu/delete/{menuId}", name="menu_delete")
     */
    public function deleteAction($menuId, MenuService $menuService)
    {
        try {
            $menuService->delete($menuId);

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
     * @Route("/menu/update", name="menu_update")
     */
    public function updateAction(Request $request, MenuService $menuService)
    {
        $admin = $this->getUser();

        $menuId = $request->request->get('menuId');
        $name = $request->request->get('name');
        $category = $request->request->get('category');

        try {
            $menuService->update($menuId, $name, $category);

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
