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
use App\Sdk\Iyzico;

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
     * @Route("/hakkimizda", name="about_us")
     */
    public function aboutUs(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $aboutUs = $siteSettings->getAgreementString('about_us');

        return $this->render('Web/Strings/about-us.html.php', [
            'aboutUs' => $aboutUs,
            'user' => $user
        ]);
    }

    /**
     * @Route("/uyelik-sozlesmesi", name="sign_up_aggreement")
     */
    public function signUpAgreement(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $signUpAgreement = $siteSettings->getAgreementString('sign_up_agreement');

        return $this->render('Web/Strings/sign_up_agreement.html.php', [
            'signUpAgreement' => $signUpAgreement,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/kullanim-kosullari", name="terms_of_use")
     */
    public function termsOfUse(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $termsOfUse = $siteSettings->getAgreementString('terms_of_use');

        return $this->render('Web/Strings/terms_of_use.html.php', [
            'termsOfUse' => $termsOfUse,
            'user' => $user
        ]);
    }

    /**
     * @Route("/gizlilik-sozlesmesi", name="confidentiality_agreement")
     */
    public function confidentialityAgreement(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $confidentialityAgreement = $siteSettings->getAgreementString('confidentiality_agreement');

        return $this->render('Web/Strings/confidentiality_agreement.html.php', [
            'confidentialityAgreement' => $confidentialityAgreement,
            'user' => $user
        ]);
    }

    /**
     * @Route("/mesafeli-satis-sozlesmesi", name="distant_sale_agreement")
     */
    public function distantSaleAgreement(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $distantSaleAgreement = $siteSettings->getAgreementString('distant_sales_agreement');

        return $this->render('Web/Strings/distant_sale_agreement.html.php', [
            'distantSaleAgreement' => $distantSaleAgreement,
            'user' => $user
        ]);
    }

    /**
     * @Route("/teslimatlar", name="deliverables")
     */
    public function deliverables(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $deliverables = $siteSettings->getAgreementString('deliverables');
        
        return $this->render('Web/Strings/deliverables.html.php', [
            'deliverables' => $deliverables,
            'user' => $user
        ]);
    }

    /**
     * @Route("/iptal-iade-degisiklik", name="cancel_refund_change")
     */
    public function cancelRefundChange(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $cancelRefundChange = $siteSettings->getAgreementString('cancel_refund_change');

        return $this->render('Web/Strings/cancel_refund_change.html.php', [
            'cancelRefundChange' => $cancelRefundChange,
            'user' => $user
        ]);
    }

    /**
     * @Route("/banka-hesaplarimiz", name="bank_accounts")
     */
    public function bankAccounts(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $bankAccounts = $siteSettings->getBankAccounts();

        return $this->render('Web/Strings/bank_accounts.html.php', [
            'bankAccounts' => $bankAccounts,
            'user' => $user
        ]);
    }

    /**
     * @Route("/sikca-sorulan-sorular", name="sss")
     */
    public function faq(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $sss = $siteSettings->getfaq();
        
        return $this->render('Web/Strings/faq.html.php', [
            'sss' => $sss,
            'user' => $user
        ]);
    }

    /**
     * @Route("/iletisim", name="contact")
     */
    public function contactAction(SiteSettings $siteSettings)
    {
        $user = $this->getUser();
        $siteSettings = $siteSettings->getFooterData();

        return $this->render('Web/Strings/contact.html.php', [
            'siteSettings' => $siteSettings,
            'user' => $user
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
