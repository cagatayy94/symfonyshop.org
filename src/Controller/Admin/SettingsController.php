<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Admin\SiteSettings as SiteSettingsService;


/**
 * @Route("/admin", name="admin_settings_")
 */
class SettingsController extends AbstractController
{
    /**
     * @Route("/general-settings", name="general")
     */
    public function generalSettingsAction(SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();
        $siteSettings = $siteSettingsService->getAll();

        return $this->render('Admin/SiteSettings/general.html.php', [
            'admin' => $admin,
            'siteSettings' => $siteSettings
        ]);
    }


    /**
     * @Route("/general/update", name="general_update")
     */
    public function generalSettingsUpdateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $name = $request->request->get('name');
        $phone = $request->request->get('phone');
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $keywords = $request->request->get('keywords');
        $copyright = $request->request->get('copyright');
        $mail = $request->request->get('mail');
        $link = $request->request->get('link');
        $address = $request->request->get('address');
        $footerText = $request->request->get('footer_text');
        $facebook = $request->request->get('facebook');
        $instagram = $request->request->get('instagram');
        $linkedin = $request->request->get('linkedin');
        $twitter = $request->request->get('twitter');
        $youtube = $request->request->get('youtube');
        $pinterest = $request->request->get('pinterest');

        try {
            $siteSettingsService->siteSettingsGeneralUpdate($name, $title, $description, $keywords, $copyright, $mail, $link, $address, $phone, $footerText, $facebook, $instagram, $linkedin, $twitter, $youtube, $pinterest);

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
     * @Route("/strings-settings", name="strings")
     */
    public function stringsSettingsAction(SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();
        $agrementsAndStrings = $siteSettingsService->getAgreementsAndStrings();

        return $this->render('Admin/SiteSettings/strings.html.php', [
            'admin' => $admin,
            'agrementsAndStrings' => $agrementsAndStrings
        ]);
    }

    /**
     * @Route("/strings-settings/update", name="strings_update")
     */
    public function stringsSettingsUpdateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $aboutUs = $request->request->get('about_us');
        $signUpAgreement = $request->request->get('sign_up_agreement');
        $termsofUse = $request->request->get('terms_of_use');
        $confidentialityAgreement = $request->request->get('confidentiality_agreement');
        $distantSalesAgreement = $request->request->get('distant_sales_agreement');
        $deliverables = $request->request->get('deliverables');
        $cancelRefundChange = $request->request->get('cancel_refund_change');

        try {

            $siteSettingsService->agreementsAndStringsUpdate($aboutUs, $signUpAgreement, $termsofUse, $confidentialityAgreement, $distantSalesAgreement, $deliverables, $cancelRefundChange);

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
     * @Route("/bank-settings", name="bank")
     */
    public function bankSettingsAction(SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();

        $bankSettings = $siteSettingsService->bankSettings();

        return $this->render('Admin/SiteSettings/bank-accounts.html.php', [
            'admin' => $admin,
            'bankSettings' => $bankSettings
        ]);
    }

    /**
     * @Route("/bank-settings/create", name="bank_create")
     */
    public function newBankAddAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $name = $request->request->get('name');
        $city = $request->request->get('city');
        $country = $request->request->get('country');
        $branchName = $request->request->get('branchName');
        $branchCode = $request->request->get('branchCode');
        $currency = $request->request->get('currency');
        $accountOwner = $request->request->get('accountOwner');
        $accountNumber = $request->request->get('accountNumber');
        $iban = $request->request->get('iban');
        $logo = $request->files->get('logo');

        try {
            $siteSettingsService->newBankAdd($name, $city, $country, $branchName, $branchCode, $currency, $accountOwner, $accountNumber, $iban, $logo);

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
     * @Route("/bank/delete/{bankId}", name="bank_delete")
     */
    public function deleteBankAction($bankId, SiteSettingsService $siteSettingsService)
    {
        try {
            $siteSettingsService->deleteBank($bankId);

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
     * @Route("/bank-settings/update", name="bank_update")
     */
    public function bankUpdateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $name = $request->request->get('name');
        $city = $request->request->get('city');
        $country = $request->request->get('country');
        $branchName = $request->request->get('branchName');
        $branchCode = $request->request->get('branchCode');
        $currency = $request->request->get('currency');
        $accountOwner = $request->request->get('accountOwner');
        $accountNumber = $request->request->get('accountNumber');
        $iban = $request->request->get('iban');
        $logo = $request->files->get('logo');
        $bankId = $request->request->get('id');

        try {
            $siteSettingsService->updateBank($bankId, $name, $city, $country, $branchName, $branchCode, $currency, $accountOwner, $accountNumber, $iban, $logo);

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
     * @Route("/faq", name="faq")
     */
    public function faqAction(SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();

        $faqs = $siteSettingsService->getAllFaqs();

        return $this->render('Admin/SiteSettings/faq.html.php', [
            'admin' => $admin,
            'faqs' => $faqs
        ]);
    }

    /**
     * @Route("/faq/create", name="faq_create")
     */
    public function faqCreateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $question = $request->request->get('question');
        $answer = $request->request->get('answer');

        try {
            $siteSettingsService->faqCreate($question, $answer);

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
     * @Route("/faq/delete/{faqId}", name="faq_delete")
     */
    public function deleteFaqAction($faqId, SiteSettingsService $siteSettingsService)
    {
        try {
            $siteSettingsService->deleteFaq($faqId);

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
     * @Route("/faq/update", name="faq_update")
     */
    public function faqUpdateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $question = $request->request->get('question');
        $answer = $request->request->get('answer');
        $faqId = $request->request->get('faq_id');

        try {
            $siteSettingsService->updateFaq($faqId, $question, $answer);

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
     * @Route("/logo", name="logo")
     */
    public function logoAction()
    {
        $admin = $this->getUser();

        return $this->render('Admin/SiteSettings/logo.html.php', [
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/logo/update", name="logo_update")
     */
    public function logoUpdateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $darkLogo = $request->files->get('dark_logo');
        $lightLogo = $request->files->get('light_logo');
        $favicon = $request->files->get('favicon');

        try {
            $siteSettingsService->updateLogo($darkLogo, $lightLogo, $favicon);

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
     * @Route("/banner", name="banner")
     */
    public function bannerAction(SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();

        $banners = $siteSettingsService->getBanners();

        return $this->render('Admin/SiteSettings/banner.html.php', [
            'admin' => $admin,
            'banners' => $banners,
        ]);
    }

    /**
     * @Route("/banner-settings/create", name="banner_create")
     */
    public function newBannerAddAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $name = $request->request->get('name');
        $img = $request->files->get('img');

        try {
            $siteSettingsService->newBannerAdd($name, $img);

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
     * @Route("/banner/delete/{bannerId}", name="banner_delete")
     */
    public function deleteBannerAction($bannerId, SiteSettingsService $siteSettingsService)
    {
        try {
            $siteSettingsService->deleteBanner($bannerId);

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
     * @Route("/banner-settings/update", name="banner_update")
     */
    public function bannerUpdateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $name = $request->request->get('name');
        $img = $request->files->get('uploadImg');
        $bannerId = $request->request->get('id');

        try {
            $siteSettingsService->updateBanner($bannerId, $name, $img);

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
     * @Route("/cargo-list", name="cargo_list")
     */
    public function cargoListAction(SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();

        $cargoList = $siteSettingsService->cargoList();

        return $this->render('Admin/SiteSettings/cargo-list.html.php', [
            'admin' => $admin,
            'cargoList' => $cargoList
        ]);
    }

    /**
     * @Route("/cargo/create", name="cargo_create")
     */
    public function newCargoAddAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $name = $request->request->get('name');

        try {
            $siteSettingsService->newCargoCompanyAdd($name);

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
     * @Route("/cargo/delete/{cargoId}", name="cargo_delete")
     */
    public function deleteCargoAction($cargoId, SiteSettingsService $siteSettingsService)
    {
        try {
            $siteSettingsService->deleteCargoCompany($cargoId);

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
     * @Route("/cargo-settings/update", name="cargo_update")
     */
    public function cargoUpdateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $name = $request->request->get('name');
        $cargoCompanyId = $request->request->get('id');

        try {
            $siteSettingsService->updateCargoCompany($cargoCompanyId, $name);

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
     * @Route("/iyzico", name="iyzico")
     */
    public function iyzicoAction(SiteSettingsService $siteSettingsService)
    {
        $admin = $this->getUser();

        $iyzicoSettings = $siteSettingsService->getIyzicoSettings();

        return $this->render('Admin/SiteSettings/iyzico-settings.html.php', [
            'admin' => $admin,
            'iyzicoSettings' => $iyzicoSettings
        ]);
    }

    /**
     * @Route("/iyzico/update", name="iyzico_update")
     */
    public function iyzicoUpdateAction(Request $request, SiteSettingsService $siteSettingsService)
    {
        $iyzicoApiKey = $request->request->get('iyzico_api_key');
        $iyzicoSecretKey = $request->request->get('iyzico_secret_key');
        $iyzicoBaseUrl = $request->request->get('iyzico_base_url');

        try {
            $siteSettingsService->updateIyzicoSettings($iyzicoApiKey, $iyzicoSecretKey, $iyzicoBaseUrl);

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
