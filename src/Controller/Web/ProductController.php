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
     * @Route("/product-detail/{id}", name="product_detail" )
     */
    public function productDetail(ProductService $productService, SiteSettings $siteSettings, Request $request)
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
}
