<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\Web\SiteSettings as SiteSettings;
use App\Service\Web\Menu as MenuService;
use App\Service\Web\Product as ProductService;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SiteSettings $siteSettings, MenuService $menuService, ProductService $productService, Request $request)
    {
        $banner = $siteSettings->getBanner();
        $user = $this->getUser();
        $menuCategories = $menuService->getAllCategory();

        $perPage = 12;
        $pageCount = 0;
        $currentPage = (int) $request->request->get('currentPage', 1);

        $order = $request->request->get('order');
        $categoryId = $request->request->get('categoryId');
        $search = $request->request->get('search');
        $priceLow = $request->request->get('priceLow');
        $priceHigh = $request->request->get('priceHigh');

        if ($currentPage == "") {
            $currentPage = 1;
        }

        if (!$order) {
            $order = 'date';
        }

        if ($priceLow == "") {
            $priceLow = null;
        }

        if ($priceHigh == "") {
            $priceHigh = null;
        }

        $products = $productService->getAll($currentPage, $pageCount, $perPage, $order, null, $categoryId, $search, $priceHigh, $priceLow);

        if (!empty($products['total']) && $products['total'] > $perPage) {
            $pageCount = ceil($products['total'] / $perPage);
        }

        return $this->render('Web/Default/index.html.php', [
            'banner'            => $banner,
            'slug'              => null,
            'user'              => $user,
            'menuCategories'    => $menuCategories,
            'maxPrice'          => $productService->getMaxPrice(),
            'products'          => $products,
            'pageCount'         => $pageCount,
            'currentPage'       => $currentPage,
            'perPage'           => $perPage,
            'order'             => $order,
            'categoryId'        => $categoryId,
            'search'            => $search,
            'priceLow'          => $priceLow,
            'priceHigh'         => $priceHigh,
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('authenticate')->getValue();
        // get the login error if there is one

        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            $this->addFlash('error', $error->getMessageKey());
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Web/Default/login.html.php', 
            [
                'last_username' => $lastUsername, 
                'error' => $error,
                'token' => $token,
            ]
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/footer/data", name="footer_data")
     */
    public function footerData(SiteSettings $siteSettings)
    {
        $footerData = $siteSettings->getFooterData();

        return $this->render('Web/footer.html.php', [
            'footerData' => $footerData
        ]);
    }

    /**
     * @Route("/navigation/data", name="navigation_data")
     */
    public function navigationData(SiteSettings $siteSettings, MenuService $menuService)
    {
        $user = $this->getUser();
        $menus = $menuService->getAll();

        return new JsonResponse([
            'user' => $user,
            'menus' => $menus,
        ]);
    }

    /**
     * @Route("/hakkimizda", name="hakkimizda")
     */
    public function aboutUs(SiteSettings $siteSettings)
    {
        $aboutUs = $siteSettings->getAgreementString('about_us');

        return $this->render('Web/Strings/about-us.html.php', [
            'aboutUs' => $aboutUs
        ]);
    }

    /**
     * @Route("/uyelik-sozlesmesi", name="uyelik-sozlesmesi")
     */
    public function signUpAgreement(SiteSettings $siteSettings)
    {
        $signUpAgreement = $siteSettings->getAgreementString('sign_up_agreement');

        return $this->render('Web/Strings/sign_up_agreement.html.php', [
            'signUpAgreement' => $signUpAgreement
        ]);
    }

    /**
     * @Route("/kullanim-kosullari", name="kullanim-kosullari")
     */
    public function termsOfUse(SiteSettings $siteSettings)
    {
        $termsOfUse = $siteSettings->getAgreementString('terms_of_use');

        return $this->render('Web/Strings/terms_of_use.html.php', [
            'termsOfUse' => $termsOfUse
        ]);
    }

    /**
     * @Route("/gizlilik-sozlesmesi", name="gizlilik-sozlesmesi")
     */
    public function confidentialityAgreement(SiteSettings $siteSettings)
    {
        $confidentialityAgreement = $siteSettings->getAgreementString('confidentiality_agreement');

        return $this->render('Web/Strings/confidentiality_agreement.html.php', [
            'confidentialityAgreement' => $confidentialityAgreement
        ]);
    }

    /**
     * @Route("/mesafeli-satis-sozlesmesi", name="mesafeli-satis-sozlesmesi")
     */
    public function distantSaleAgreement(SiteSettings $siteSettings)
    {
        $distantSaleAgreement = $siteSettings->getAgreementString('distant_sales_agreement');

        return $this->render('Web/Strings/distant_sale_agreement.html.php', [
            'distantSaleAgreement' => $distantSaleAgreement
        ]);
    }

    /**
     * @Route("/teslimatlar", name="teslimatlar")
     */
    public function deliverables(SiteSettings $siteSettings)
    {
        $deliverables = $siteSettings->getAgreementString('deliverables');

        return $this->render('Web/Strings/deliverables.html.php', [
            'deliverables' => $deliverables
        ]);
    }

    /**
     * @Route("/iptal-iade-degisiklik", name="iptal-iade-degisiklik")
     */
    public function cancelRefundChange(SiteSettings $siteSettings)
    {
        $cancelRefundChange = $siteSettings->getAgreementString('cancel_refund_change');

        return $this->render('Web/Strings/cancel_refund_change.html.php', [
            'cancelRefundChange' => $cancelRefundChange
        ]);
    }

    /**
     * @Route("/banka-hesaplarimiz", name="banka-hesaplarimiz")
     */
    public function bankAccounts(SiteSettings $siteSettings)
    {
        $bankAccounts = $siteSettings->getBankAccounts();

        return $this->render('Web/Strings/bank_accounts.html.php', [
            'bankAccounts' => $bankAccounts
        ]);
    }

    /**
     * @Route("/sikca-sorulan-sorular", name="sss")
     */
    public function faq(SiteSettings $siteSettings)
    {
        $sss = $siteSettings->getfaq();
        
        return $this->render('Web/Strings/faq.html.php', [
            'sss' => $sss
        ]);
    }

    /**
     * @Route("/iletisim", name="iletisim")
     */
    public function contactAction(SiteSettings $siteSettings)
    {
        $siteSettings = $siteSettings->getFooterData();
        
        return $this->render('Web/Strings/contact.html.php', [
            'siteSettings' => $siteSettings
        ]);
    }

    /**
     * @Route("/contact-submit", name="contact_submit")
     */
    public function contactResultAction(SiteSettings $siteSettings, Request $request)
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $subject = $request->request->get('subject');
        $mobile = $request->request->get('mobile');
        $message = $request->request->get('message');

        try {
            $siteSettings->contactFormSubmit($name, $email, $subject, $mobile, $message);

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
     * @Route("/urunler/{slug}", name="urunler")
     */
    public function productsAction($slug, SiteSettings $siteSettings, MenuService $menuService, ProductService $productService, Request $request)
    {
        $banner = $siteSettings->getBanner();
        $user = $this->getUser();

        $perPage = 12;
        $pageCount = 0;
        $currentPage = (int) $request->request->get('currentPage', 1);

        $order = $request->request->get('order');
        $categoryId = $request->request->get('categoryId');
        $search = $request->request->get('search');
        $priceLow = $request->request->get('priceLow');
        $priceHigh = $request->request->get('priceHigh');

        if ($currentPage == "") {
            $currentPage = 1;
        }

        if (!$order) {
            $order = 'date';
        }

        if ($priceLow == "") {
            $priceLow = null;
        }

        if ($priceHigh == "") {
            $priceHigh = null;
        }

        $menu = $menuService->getFromSlug($slug);

        $products = $productService->getAll($currentPage, $pageCount, $perPage, $order, $menu['id'], $categoryId, $search, $priceHigh, $priceLow);

        if (!empty($products['total']) && $products['total'] > $perPage) {
            $pageCount = ceil($products['total'] / $perPage);
        }

        $menuCategories = $menuService->getMenuCategory($menu['id']);

        $maxPrice = $productService->getMaxPrice();

        return $this->render('Web/Default/index.html.php', [
            'slug'              => $slug,
            'banner'            => $banner,
            'menu'              => $menu,
            'menuCategories'    => $menuCategories,
            'maxPrice'          => $maxPrice,
            'products'          => $products,
            'user'              => $user,
            'pageCount'         => $pageCount,
            'currentPage'       => $currentPage,
            'perPage'           => $perPage,
            'order'             => $order,
            'categoryId'        => $categoryId,
            'search'            => $search,
            'priceLow'          => $priceLow,
            'priceHigh'         => $priceHigh,
        ]);
    }
}
