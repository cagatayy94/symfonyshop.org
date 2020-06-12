<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\Web\SiteSettings as SiteSettings;


class DefaultController extends AbstractController
{


    /**
     * @Route("/", name="index")
     */
    public function index(SiteSettings $siteSettings)
    {
        $banner = $siteSettings->getBanner();

        return $this->render('Web/Default/index.html.php', [
            'banner' => $banner
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
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
}
