<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Web\Product as ProductService;
use App\Service\Web\SiteSettings as SiteSettings;

class ProductController extends AbstractController
{
    /**
     * @Route("/product-detail/{slug}/{id}", name="product_detail" )
     */
    public function productDetailAction(ProductService $productService, SiteSettings $siteSettings, Request $request)
    {
        $id = $request->attributes->get('id');
        $productDetail = $productService->getDetail($id);
        $banner = $siteSettings->getBanner();

        if (!$productDetail) {
            return $this->redirect($this->generateUrl('index'));
        }

        $user = $this->getUser();

        return $this->render('Web/Product/product_detail.html.php', [
            'productDetail' => $productDetail,
            'banner' => $banner,
            'id' => $id,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/product-detail-comments", name="product_detail_comments" )
     */
    public function productDetailCommentsAction(ProductService $productService, Request $request)
    {
        $id = $request->query->get('productId');
        $offset = $request->query->get('offset');

        $comments = $productService->getProductComments($id, $offset);

        return $this->render('Web/Product/product_detail_comments.html.php', [
            'comments' => $comments,
            'productId' => $id,
        ]);
    }

    /**
     * @Route("/add/favorite", name="add_favorite")
     */
    public function addFavoriteAction(Request $request, ProductService $productService)
    {
        $productId = $request->request->get('productId');
        $user = $this->getUser();
        try {
            $productService->addFavorite($productId, $user->getId());

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
     * @Route("/add/comment", name="add_comment")
     */
    public function addCommentAction(Request $request, ProductService $productService)
    {
        $user = $this->getUser();

        $productId = $request->request->get('productId');
        $rate = $request->request->get('rate');
        $comment = $request->request->get('comment');
        $ipAddress = $request->getClientIp();

        if ($comment == "") {
            $comment = null;
        }

        try {
            $data = $productService->addComment($productId, $user->getId(), $rate, $ipAddress, $comment);

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
}
