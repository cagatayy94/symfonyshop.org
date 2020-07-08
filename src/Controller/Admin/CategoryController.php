<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\Account as AccountService;
use App\Service\Admin\Product as ProductService;
use App\Service\Admin\Category as CategoryService;


/**
 * @Route("/admin", name="admin_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/category/get/all", name="category_get_all")
     */
    public function categoryGetAllAction(CategoryService $categoryService)
    {
        try {
            $data = $categoryService->getAll();

            return new JsonResponse([
                'success' => true,
                'data' => $data,
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
     * @Route("/category/add", name="category_add")
     */
    public function categoryAddAction(Request $request, CategoryService $categoryService)
    {
        try {
            $categoryName = $request->request->get('categoryName');
            
            $categoryService->create($categoryName);

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
