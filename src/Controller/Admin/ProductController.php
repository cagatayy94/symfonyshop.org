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
class ProductController extends AbstractController
{
    /**
     * @Route("/product-create/view", name="product_create_view")
     */
    public function productAddPage(CategoryService $categoryService)
    {
        $admin = $this->getUser();

        $allCategories = $categoryService->getAll();

        return $this->render('Admin/Product/add.html.php', [
            'admin' => $admin,
            'allCategories' => $allCategories,
        ]);
    }

    /**
     * @Route("/product-create", name="product_create")
     */
    public function productCreate(Request $request, ProductService $productService)
    {
        $productName = $request->request->get('productName');
        $productPrice = $request->request->get('productPrice');
        $cargoPrice = $request->request->get('cargoPrice');
        $categoryId = $request->request->get('categoryId');
        $variantName = $request->request->get('variantName');
        $variantTitle = $request->request->get('variantTitle');
        $variantStock = $request->request->get('variantStock');
        $description = $request->request->get('description');
        $tax = $request->request->get('tax');
        $files = $request->files->get('img');

        try {
            $productService->create($productName, $productPrice, $cargoPrice, $description, $categoryId, $variantTitle, $variantName, $variantStock, $tax, $files);

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
