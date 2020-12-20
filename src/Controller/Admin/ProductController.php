<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\Product as ProductService;
use App\Service\Admin\Category as CategoryService;
use App\Sdk\Excel as ExcelService;


/**
 * @Route("/admin", name="admin_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/product-create/view", name="product_create_view")
     */
    public function productAddPageAction(CategoryService $categoryService)
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
    public function productCreateAction(Request $request, ProductService $productService)
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

    /**
     * @Route("/product-list", name="product_list")
     */
    public function productListAction(Request $request, ProductService $productService, ExcelService $excelService)
    {
        $admin = $this->getUser();

        $perPage = 50;
        $pageCount = 0;
        $currentPage = (int) $request->query->get('currentPage', 1);

        $productName = $request->query->get('productName');
        $productId = $request->query->get('productId');
        $createdAtStart = $request->query->get('createdAtStart');
        $createdAtEnd = $request->query->get('createdAtEnd');
        $excelExport = $request->query->get('excelExport');

        if ($currentPage == "") {
            $currentPage = 1;
        }

        if ($excelExport) {
            $perPage = PHP_INT_MAX;
        }

        $products = $productService->getAll($currentPage, $pageCount, $perPage, false, $productName, $productId, $createdAtStart, $createdAtEnd);

        if (!empty($products['total']) && $products['total'] > $perPage) {
            $pageCount = ceil($products['total'] / $perPage);
        }

        $data = [
            'admin' => $admin,
            'products' => $products,
            'productName' => $productName,
            'productId' => $productId,
            'createdAtStart' => $createdAtStart,
            'createdAtEnd' => $createdAtEnd,
            'pageCount' => $pageCount,
            'currentPage' => $currentPage,
            'excelExport' => $excelExport,
        ];

        $view = 'Admin/Product/list.html.php';

        if ($excelExport) {
            return $excelService->renderExcel($view, $data, 'urun-listesi');
        }

        return $this->render($view, $data);
    }

    /**
     * @Route("/product/delete/{productId}", name="product_delete")
     */
    public function deleteProductAction($productId, ProductService $productService)
    {
        try {
            $productService->deleteProduct($productId);

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
     * @Route("/product/undelete/{productId}", name="product_undelete")
     */
    public function undeleteProductAction($productId, ProductService $productService)
    {
        try {
            $productService->undeleteProduct($productId);

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
     * @Route("/product-list-deleted", name="product_list_deleted")
     */
    public function productListDeletedAction(Request $request, ProductService $productService, ExcelService $excelService)
    {
        $admin = $this->getUser();

        $perPage = 50;
        $pageCount = 0;
        $currentPage = (int) $request->query->get('currentPage', 1);

        $productName = $request->query->get('productName');
        $productId = $request->query->get('productId');
        $createdAtStart = $request->query->get('createdAtStart');
        $createdAtEnd = $request->query->get('createdAtEnd');
        $excelExport = $request->query->get('excelExport');

        if ($currentPage == "") {
            $currentPage = 1;
        }

        if ($excelExport) {
            $perPage = PHP_INT_MAX;
        }

        $products = $productService->getAll($currentPage, $pageCount, $perPage, true, $productName, $productId, $createdAtStart, $createdAtEnd);

        if (!empty($products['total']) && $products['total'] > $perPage) {
            $pageCount = ceil($products['total'] / $perPage);
        }

        $data = [
            'admin' => $admin,
            'products' => $products,
            'productName' => $productName,
            'productId' => $productId,
            'createdAtStart' => $createdAtStart,
            'createdAtEnd' => $createdAtEnd,
            'pageCount' => $pageCount,
            'currentPage' => $currentPage,
            'excelExport' => $excelExport,
        ];

        $view = 'Admin/Product/list-deleted.html.php';

        if ($excelExport) {
            return $excelService->renderExcel($view, $data, 'silinmis-urun-listesi');
        }

        return $this->render($view, $data);
    }

    /**
     * @Route("/product/detail/{id}", name="product_detail")
     */
    public function productDetailAction($id, ProductService $productService)
    {
        $admin = $this->getUser();

        $product = $productService->getProduct($id);

        return $this->render('Admin/Product/detail.html.php', [
            'admin' => $admin,
            'product' => $product,
            'id' => $id,
        ]);
    }

    /**
     * @Route("/product-update", name="product_update")
     */
    public function productUpdateAction(Request $request, ProductService $productService)
    {
        $productId      = $request->request->get('id');
        $productName    = $request->request->get('productName');
        $productPrice   = $request->request->get('productPrice');
        $cargoPrice     = $request->request->get('cargoPrice');
        $categoryId     = $request->request->get('categoryId');
        $variantName    = $request->request->get('variantName');
        $variantTitle   = $request->request->get('variantTitle');
        $variantStock   = $request->request->get('variantStock');
        $description    = $request->request->get('description');
        $tax            = $request->request->get('tax');
        $files          = $request->files->get('img');

        try {
            $productService->update($productId, $productName, $productPrice, $cargoPrice, $description, $categoryId, $variantTitle, $variantName, $variantStock, $tax, $files);

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
     * @Route("/product/image/delete/{id}", name="product_image_delete")
     */
    public function deleteProductImgAction($id, ProductService $productService)
    {
        try {
            $productService->deleteProductImage($id);

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
