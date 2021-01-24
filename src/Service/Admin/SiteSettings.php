<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;
use App\Sdk\Upload;

class SiteSettings
{
    use ServiceTrait;
    use AdminServiceTrait;

    public function getAll()
    {
        $this->authorize('settings_general');
        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                settings ss
            WHERE
                ss.is_deleted = false
                ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Update site settings
     *
     * @param string $name
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param string $copyright
     * @param string $mail
     * @param string $link
     * @param string $address
     * @param string $phone
     * @param string $footerText
     * @param string $facebook
     * @param string $instagram
     * @param string $linkedin
     * @param string $twitter
     * @param string $youtube
     * @param string $pinterest
     *
     * @throws \Exception
     */
    public function siteSettingsGeneralUpdate($name, $title, $description, $keywords, $copyright, $mail, $link, $address, $phone, $footerText, $facebook, $instagram, $linkedin, $twitter, $youtube, $pinterest)
    {
        $this->authorize('settings_general_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'siteSettingsGeneralUpdate',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$name) {
                throw new \InvalidArgumentException('Site ismi belirtilmemiş');
            }

            if (!$title) {
                throw new \InvalidArgumentException('Site başlık belirtilmemiş');
            }

            if (!$description) {
                throw new \InvalidArgumentException('Site açıklama belirtilmemiş');
            }

            if (!$keywords) {
                throw new \InvalidArgumentException('Site keywords belirtilmemiş');
            }

            if (!$copyright) {
                throw new \InvalidArgumentException('Site copyright belirtilmemiş');
            }

            if (!$mail) {
                throw new \InvalidArgumentException('Site mail adresi belirtilmemiş');
            }

            if (!$link) {
                throw new \InvalidArgumentException('Site linki belirtilmemiş');
            }

            if (!$address) {
                throw new \InvalidArgumentException('Site adresi belirtilmemiş');
            }

            if (!$phone) {
                throw new \InvalidArgumentException('Site açıklama belirtilmemiş');
            }

            if (!$footerText) {
                throw new \InvalidArgumentException('Site keywords belirtilmemiş');
            }

            if ($facebook == "") {
                $facebook = null;
            }

            if ($instagram == "") {
                $instagram = null;
            }

            if ($linkedin == "") {
                $linkedin = null;
            }

            if ($twitter == "") {
                $twitter = null;
            }

            if ($youtube == "") {
                $youtube = null;
            }

            if ($pinterest == "") {
                $pinterest = null;
            }

            try {
                $statement = $connection->prepare('
                    UPDATE 
                        settings
                    SET
                        name = :name,
                        title = :title,
                        description = :description,
                        keywords = :keywords,
                        copyright = :copyright,
                        mail = :mail,
                        link = :link,
                        address = :address,
                        phone = :phone,
                        footer_text = :footer_text,
                        facebook = :facebook,
                        instagram = :instagram,
                        linkedin = :linkedin,
                        twitter = :twitter,
                        youtube = :youtube,
                        pinterest = :pinterest
                ');

                $statement->bindValue(':name', $name);
                $statement->bindValue(':title', $title);
                $statement->bindValue(':description', $description);
                $statement->bindValue(':keywords', $keywords);
                $statement->bindValue(':copyright', $copyright);
                $statement->bindValue(':mail', $mail);
                $statement->bindValue(':link', $link);
                $statement->bindValue(':address', $address);
                $statement->bindValue(':phone', $phone);
                $statement->bindValue(':footer_text', $footerText);
                $statement->bindValue(':facebook', $facebook);
                $statement->bindValue(':instagram', $instagram);
                $statement->bindValue(':linkedin', $linkedin);
                $statement->bindValue(':twitter', $twitter);
                $statement->bindValue(':youtube', $youtube);
                $statement->bindValue(':pinterest', $pinterest);
                $statement->execute();

            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $this->logger->info('Updated site general settings', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update site general settings', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update site general settings', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * get site agreements and strings
     *
     * @return array Mixed
     */
    public function getAgreementsAndStrings()
    {
        $this->authorize('settings_strings');

        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                agreements_strings
            LIMIT 1
                ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Update site agreements and strings
     *
     * @param text $aboutUs
     * @param text $signUpAgreement
     * @param text $termsofUse
     * @param text $confidentialityAgreement
     * @param text $distantSalesAgreement
     * @param text $deliverables
     * @param text $cancelRefundChange
     *
     * @throws \Exception
     */
    public function agreementsAndStringsUpdate($aboutUs, $signUpAgreement, $termsofUse, $confidentialityAgreement, $distantSalesAgreement, $deliverables, $cancelRefundChange)
    {
        $this->authorize('settings_strings_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'agreementsAndStringsUpdate',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$aboutUs) {
                throw new \InvalidArgumentException('Hakkımızda metni belirtilmemiş');
            }

            if (!$signUpAgreement) {
                throw new \InvalidArgumentException('Kayıt Sözleşmesi metni belirtilmemiş');
            }

            if (!$termsofUse) {
                throw new \InvalidArgumentException('Kullanım Koşulları metni belirtilmemiş');
            }

            if (!$confidentialityAgreement) {
                throw new \InvalidArgumentException('Gizlilik Sözleşmesi metni belirtilmemiş');
            }

            if (!$distantSalesAgreement) {
                throw new \InvalidArgumentException('Uzak Mesafeli Satış Sözleşmesi metni belirtilmemiş');
            }

            if (!$deliverables) {
                throw new \InvalidArgumentException('Teslimatlar metni belirtilmemiş');
            }

            if (!$cancelRefundChange) {
                throw new \InvalidArgumentException('İptal İade Değişiklik metni belirtilmemiş');
            }

            try {
                $statement = $connection->prepare('
                    UPDATE 
                        agreements_strings
                    SET
                        about_us = :about_us,
                        sign_up_agreement = :sign_up_agreement,
                        terms_of_use = :terms_of_use,
                        confidentiality_agreement = :confidentiality_agreement,
                        distant_sales_agreement = :distant_sales_agreement,
                        deliverables = :deliverables,
                        cancel_refund_change = :cancel_refund_change
                ');

                $statement->bindValue(':about_us', $aboutUs);
                $statement->bindValue(':sign_up_agreement', $signUpAgreement);
                $statement->bindValue(':terms_of_use', $termsofUse);
                $statement->bindValue(':confidentiality_agreement', $confidentialityAgreement);
                $statement->bindValue(':distant_sales_agreement', $distantSalesAgreement);
                $statement->bindValue(':deliverables', $deliverables);
                $statement->bindValue(':cancel_refund_change', $cancelRefundChange);
                $statement->execute();

            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $this->logger->info('Updated the site strings', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update strings', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update strings', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * get bank settings
     *
     * @return array Mixed
     */
    public function bankSettings()
    {
        $this->authorize('settings_bank');
        
        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                bank_accounts ba
            ORDER BY
                ba.name";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Delete bank
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function deleteBank($id)
    {
        $this->authorize('settings_bank_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'deleteBank',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                DELETE FROM 
                    bank_accounts
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the bank', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the bank', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the bank', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Add new bank
     *
     * @param string $name
     * @param string $city
     * @param string $country
     * @param string $branchName
     * @param string $branchCode
     * @param string $currency
     * @param string $accountOwner
     * @param string $accountNumber
     * @param string $iban
     * @param string $logo
     *
     * @throws \Exception
     */
    public function newBankAdd($name, $city, $country, $branchName, $branchCode, $currency, $accountOwner, $accountNumber, $iban, $logo)
    {
        $this->authorize('settings_bank_create');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'newBankAdd',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$name) {
                throw new \InvalidArgumentException('Banka adı belirtilmemiş');
            }
            if (!$city) {
                throw new \InvalidArgumentException('Şehir belirtilmemiş');
            }
            if (!$country) {
                throw new \InvalidArgumentException('Ülke belirtilmemiş');
            }
            if (!$branchName) {
                throw new \InvalidArgumentException('Şube adı belirtilmemiş');
            }
            if (!$branchCode) {
                throw new \InvalidArgumentException('Şube kodu belirtilmemiş');
            }
            if (!$currency) {
                throw new \InvalidArgumentException('Para birimi belirtilmemiş');
            }
            if (!$accountOwner) {
                throw new \InvalidArgumentException('Hesap sahibi adı belirtilmemiş');
            }
            if (!$accountNumber) {
                throw new \InvalidArgumentException('Hesap no belirtilmemiş');
            }
            if (!$iban) {
                throw new \InvalidArgumentException('IBAN belirtilmemiş');
            }
            if ($logo->getError() != 0) {
                throw new \InvalidArgumentException('Logo yüklenmemiş');
            }            

            $filename = md5(time());
            //Save Bank Logo
            $foo = new upload($logo,"tr-TR");
            if ($foo->uploaded) {
                // save uploaded image with a new name,
                $foo->file_new_name_body    = $filename;
                $foo->file_overwrite        = true;
                $foo->image_resize          = true;
                $foo->allowed               = array("image/*");
                $foo->image_convert         ='png' ;
                $foo->image_resize          = true;
                $foo->image_ratio_fill      = true;
                $foo->image_y               = 30;
                $foo->process($_SERVER["DOCUMENT_ROOT"].'/web/img/bank');
                if ($foo->processed) {
                    $foo->clean();
                } else {
                    throw new \InvalidArgumentException($foo->error);
                }
            }

            $statement = $connection->prepare('
                INSERT INTO 
                    bank_accounts
                    (name, city, country, branch_name, branch_code, currency, account_owner, account_number, iban, logo)
                VALUES
                    (:name, :city, :country, :branch_name, :branch_code, :currency, :account_owner, :account_number, :iban, :logo)
            ');

            $statement->bindValue(':logo', $filename.".png");
            $statement->bindValue(':name', $name);
            $statement->bindValue(':city', $city);
            $statement->bindValue(':country', $country);
            $statement->bindValue(':branch_name', $branchName);
            $statement->bindValue(':branch_code', $branchCode);
            $statement->bindValue(':currency', $currency);
            $statement->bindValue(':account_owner', $accountOwner);
            $statement->bindValue(':account_number', $accountNumber);
            $statement->bindValue(':iban', $iban);
            $statement->execute();

            $this->logger->info('Added new bank', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not added new bank', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not added new bank', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Update the bank
     *
     * @param string $id
     * @param string $name
     * @param string $city
     * @param string $country
     * @param string $branchName
     * @param string $branchCode
     * @param string $currency
     * @param string $accountOwner
     * @param string $accountNumber
     * @param string $iban
     * @param string $logo
     *
     * @throws \Exception
     */
    public function updateBank($id, $name, $city, $country, $branchName, $branchCode, $currency, $accountOwner, $accountNumber, $iban, $logo = null)
    {
        $this->authorize('settings_bank_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'updateBank',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$id) {
                throw new \InvalidArgumentException('Banka id belirtilmemiş');
            }
            if (!$name) {
                throw new \InvalidArgumentException('Banka adı belirtilmemiş');
            }
            if (!$city) {
                throw new \InvalidArgumentException('Şehir belirtilmemiş');
            }
            if (!$country) {
                throw new \InvalidArgumentException('Ülke belirtilmemiş');
            }
            if (!$branchName) {
                throw new \InvalidArgumentException('Şube adı belirtilmemiş');
            }
            if (!$branchCode) {
                throw new \InvalidArgumentException('Şube kodu belirtilmemiş');
            }
            if (!$currency) {
                throw new \InvalidArgumentException('Para birimi belirtilmemiş');
            }
            if (!$accountOwner) {
                throw new \InvalidArgumentException('Hesap sahibi adı belirtilmemiş');
            }
            if (!$accountNumber) {
                throw new \InvalidArgumentException('Hesap no belirtilmemiş');
            }
            if (!$iban) {
                throw new \InvalidArgumentException('IBAN belirtilmemiş');
            }
            if ($logo && $logo->getError() != 0) {
                throw new \InvalidArgumentException('Logo yüklenirken bir hata oluştu');
            }

            if ($logo) {
                $filename = md5(time());
                //Save Bank Logo
                $foo = new upload($logo,"tr-TR");
                if ($foo->uploaded) {
                    // save uploaded image with a new name,
                    $foo->file_new_name_body    = $filename;
                    $foo->file_overwrite        = true;
                    $foo->image_resize          = true;
                    $foo->allowed               = array("image/*");
                    $foo->image_convert         ='png' ;
                    $foo->image_resize          = true;
                    $foo->image_ratio_fill      = true;
                    $foo->image_y               = 30;
                    $foo->process($_SERVER["DOCUMENT_ROOT"].'/web/img/bank');
                    if ($foo->processed) {
                        $foo->clean();
                    } else {
                        throw new \InvalidArgumentException($foo->error);
                    }
                }
            }

            $sql = '
                UPDATE 
                    bank_accounts
                SET 
                    name = :name, 
                    city = :city, 
                    country = :country, 
                    branch_name = :branch_name, 
                    branch_code = :branch_code, 
                    currency = :currency, 
                    account_owner = :account_owner, 
                    account_number = :account_number, 
                    iban = :iban';

            if ($logo) {
                $sql .= '
                    ,logo = :logo
                ';
            }

            $sql .= '
                WHERE 
                    id = :id
            ';

            $statement = $connection->prepare($sql);

            if ($logo) {
                $statement->bindValue(':logo', $filename.".png");
            }

            $statement->bindValue(':name', $name);
            $statement->bindValue(':city', $city);
            $statement->bindValue(':country', $country);
            $statement->bindValue(':branch_name', $branchName);
            $statement->bindValue(':branch_code', $branchCode);
            $statement->bindValue(':currency', $currency);
            $statement->bindValue(':account_owner', $accountOwner);
            $statement->bindValue(':account_number', $accountNumber);
            $statement->bindValue(':iban', $iban);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $this->logger->info('Updated the bank', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the bank', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the bank', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * get all faqs
     *
     * @return array Mixed
     */
    public function getAllFaqs()
    {
        $this->authorize('faq_list');
        
        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                faq 
            ORDER BY
                id";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Add new faq
     *
     * @param string $question
     * @param string $answer
     *
     * @throws \Exception
     */
    public function faqCreate($question, $answer)
    {
        $this->authorize('faq_create');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'faqCreate',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$question) {
                throw new \InvalidArgumentException('Soru belirtilmemiş');
            }
            if (!$answer) {
                throw new \InvalidArgumentException('Cevap belirtilmemiş');
            }

            $statement = $connection->prepare('
                INSERT INTO 
                    faq
                    (question, answer)
                VALUES
                    (:question, :answer)
            ');

            $statement->bindValue(':answer', $answer);
            $statement->bindValue(':question', $question);
            $statement->execute();

            $this->logger->info('Added new faq', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not added new faq', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not added new faq', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Delete faq
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function deleteFaq($id)
    {
        $this->authorize('faq_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'deleteFaq',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                DELETE FROM 
                    faq
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the faq', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the faq', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the faq', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Update the faq
     *
     * @param string $id
     * @param string $question
     * @param string $answer
     *
     * @throws \Exception
     */
    public function updateFaq($id, $question, $answer)
    {
        $this->authorize('faq_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'updateFaq',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }
            if (!$question) {
                throw new \InvalidArgumentException('Soru belirtilmemiş');
            }
            if (!$answer) {
                throw new \InvalidArgumentException('Cevap belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    faq
                SET 
                    question = :question, 
                    answer = :answer
                WHERE 
                    id = :id
            ';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':question', $question);
            $statement->bindValue(':answer', $answer);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $this->logger->info('Updated the faq', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the faq', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the faq', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Update the favicon and logos
     *
     * @param image $lightLogo
     * @param image $darkLogo
     * @param image $favicon
     *
     * @throws \Exception
     */
    public function updateLogo($darkLogo = null, $lightLogo = null, $favicon = null)
    {
        $this->authorize('update_logo');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'updateLogo',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if ($darkLogo && $darkLogo->getError() != 0) {
                throw new \InvalidArgumentException('Dark logo yüklenirken bir hata oluştu');
            }

            if ($lightLogo && $lightLogo->getError() != 0) {
                throw new \InvalidArgumentException('Light logo yüklenirken bir hata oluştu');
            }

            if ($favicon && $favicon->getError() != 0) {
                throw new \InvalidArgumentException('Favicon yüklenirken bir hata oluştu');
            }

            if ($darkLogo) {
                //Save the dark logo
                $foo = new upload($darkLogo,"tr-TR");
                if ($foo->uploaded) {
                    // save uploaded image with a new name,
                    $foo->file_new_name_body    = 'logo-shop';
                    $foo->file_overwrite        = true;
                    $foo->image_resize          = true;
                    $foo->allowed               = array("image/*");
                    $foo->image_convert         ='png' ;
                    $foo->image_resize          = true;
                    $foo->image_y               = 64;
                    $foo->image_x               = 256;
                    $foo->process($_SERVER["DOCUMENT_ROOT"].'/web/img');
                    if ($foo->processed) {
                        $foo->clean();
                    } else {
                        throw new \InvalidArgumentException($foo->error);
                    }
                }
            }

            if ($lightLogo) {
                //Save the light logo
                $foo = new upload($lightLogo,"tr-TR");
                if ($foo->uploaded) {
                    // save uploaded image with a new name,
                    $foo->file_new_name_body    = 'logo-shop-light';
                    $foo->file_overwrite        = true;
                    $foo->image_resize          = true;
                    $foo->allowed               = array("image/*");
                    $foo->image_convert         ='png' ;
                    $foo->image_resize          = true;
                    $foo->image_y               = 64;
                    $foo->image_x               = 256;
                    $foo->process($_SERVER["DOCUMENT_ROOT"].'/web/img');
                    if ($foo->processed) {
                        $foo->clean();
                    } else {
                        throw new \InvalidArgumentException($foo->error);
                    }
                }
            }

            if ($favicon) {
                //Save the favicon
                $foo = new upload($favicon,"tr-TR");
                if ($foo->uploaded) {
                    // save uploaded image with a new name,
                    $foo->file_new_name_body    = 'favicon';
                    $foo->file_overwrite        = true;
                    $foo->image_resize          = true;
                    $foo->allowed               = array("image/*");
                    $foo->image_convert         ='ico' ;
                    $foo->image_resize          = true;
                    $foo->image_y               = 16;
                    $foo->image_x               = 16;
                    $foo->process($_SERVER["DOCUMENT_ROOT"].'/web/img');
                    if ($foo->processed) {
                        $foo->clean();
                    } else {
                        throw new \InvalidArgumentException($foo->error);
                    }
                }
            }

            $this->logger->info('Updated the logo', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the logo', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the logo', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * get banners
     *
     * @return array Mixed
     */
    public function getBanners()
    {
        $this->authorize('all_banners');
        
        $connection = $this->connection;

        $sql = "
            SELECT
                id,
                name,
                pic,
                number_of_show
            FROM
                banner b
            ORDER BY
                b.name";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Delete banner
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function deleteBanner($id)
    {
        $this->authorize('delete_banner');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'deleteBanner',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                DELETE FROM 
                    banner
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the banner', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the banner', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the banner', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Add new banner
     *
     * @param string $name
     * @param image $img
     *
     * @throws \Exception
     */
    public function newBannerAdd($name, $img)
    {
        $this->authorize('create_banner');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'newBannerAdd',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$name) {
                throw new \InvalidArgumentException('Banner adı belirtilmemiş');
            }

            if ($img->getError() != 0) {
                throw new \InvalidArgumentException('Görsel yüklenmemiş');
            }            

            $filename = md5(time());
            //Save Banner Logo
            $foo = new upload($img,"tr-TR");
            if ($foo->uploaded) {
                // save uploaded image with a new name,
                $foo->file_new_name_body    = $filename;
                $foo->file_overwrite        = true;
                $foo->image_resize          = true;
                $foo->allowed               = array("image/*");
                $foo->image_convert         ='png' ;
                $foo->image_resize          = true;
                $foo->image_y               = 1800;
                $foo->image_x               = 1500;
                $foo->process($_SERVER["DOCUMENT_ROOT"].'/web/img/banner');
                if ($foo->processed) {
                    $foo->clean();
                } else {
                    throw new \InvalidArgumentException($foo->error);
                }
            }

            $statement = $connection->prepare('
                INSERT INTO 
                    banner
                    (name, pic)
                VALUES
                    (:name, :pic)
            ');

            $statement->bindValue(':pic', $filename.".png");
            $statement->bindValue(':name', $name);
            $statement->execute();

            $this->logger->info('Added new banner', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not added new banner', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not added new banner', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Update the banner
     *
     * @param string $id
     * @param string $name
     * @param image $img
     *
     * @throws \Exception
     */
    public function updateBanner($id, $name, $img = null)
    {
        $this->authorize('update_banner');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'updateBanner',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$id) {
                throw new \InvalidArgumentException('Banka id belirtilmemiş');
            }
            if (!$name) {
                throw new \InvalidArgumentException('Banka adı belirtilmemiş');
            }
            if ($img && $img->getError() != 0) {
                throw new \InvalidArgumentException('Görsel yüklenirken bir hata oluştu');
            }

            if ($img) {
                $filename = md5(time());
                //Save Bank Logo
                $foo = new upload($img,"tr-TR");
                if ($foo->uploaded) {
                    // save uploaded image with a new name,
                    $foo->file_new_name_body    = $filename;
                    $foo->file_overwrite        = true;
                    $foo->image_resize          = true;
                    $foo->allowed               = array("image/*");
                    $foo->image_convert         ='png' ;
                    $foo->image_resize          = true;
                    $foo->image_y               = 1800;
                    $foo->image_x               = 1500;
                    $foo->process($_SERVER["DOCUMENT_ROOT"].'/web/img/banner');
                    if ($foo->processed) {
                        $foo->clean();
                    } else {
                        throw new \InvalidArgumentException($foo->error);
                    }
                }
            }

            $sql = '
                UPDATE 
                    banner
                SET 
                    name = :name';

            if ($img) {
                $sql .= '
                    ,pic = :pic
                ';
            }

            $sql .= '
                WHERE 
                    id = :id
            ';

            $statement = $connection->prepare($sql);

            if ($img) {
                $statement->bindValue(':pic', $filename.".png");
            }

            $statement->bindValue(':name', $name);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $this->logger->info('Updated the banner', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the banner', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the banner', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * get cargo list
     *
     * @return array Mixed
     */
    public function cargoList()
    {
        $this->authorize('cargo_list');
        
        $connection = $this->connection;

        $sql = "
            SELECT
                id,
                name
            FROM
                cargo_company ca
            ORDER BY
                ca.name";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Delete cargo company
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function deleteCargoCompany($id)
    {
        $this->authorize('cargo_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'deleteCargoCompany',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                DELETE FROM 
                    cargo_company
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the cargo company', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the cargo company', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the cargo company', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Add new cargo company
     *
     * @param string $name
     *
     * @throws \Exception
     */
    public function newCargoCompanyAdd($name)
    {
        $this->authorize('cargo_create');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'newCargoCompanyAdd',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$name) {
                throw new \InvalidArgumentException('Firma adı belirtilmemiş');
            }           

            $statement = $connection->prepare('
                INSERT INTO 
                    cargo_company
                    (name)
                VALUES
                    (:name)
            ');

            $statement->bindValue(':name', $name);
            $statement->execute();

            $this->logger->info('Added new cargo company', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not added new cargo company', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not added new cargo company', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Update the cargo company
     *
     * @param string $id
     * @param string $name
     *
     * @throws \Exception
     */
    public function updateCargoCompany($id, $name)
    {
        $this->authorize('cargo_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'updateCargoCompany',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }
            if (!$name) {
                throw new \InvalidArgumentException('Firma adı belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    cargo_company
                SET 
                    name = :name
                WHERE 
                    id = :id
            ';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':name', $name);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $this->logger->info('Updated the cargo company', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the cargo company', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the cargo company', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * get iyzico settings
     *
     * @return array Mixed
     */
    public function getIyzicoSettings()
    {
        $this->authorize('iyzico_settings_show');
        
        $connection = $this->connection;

        $sql = "
            SELECT
                iyzico_api_key,
                iyzico_secret_key,
                iyzico_base_url
            FROM
                iyzico
            LIMIT 1";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetch();
    }

     /**
     * Update the iyzico settings
     *
     * @param string $iyzicoApiKey
     * @param string $iyzicoSecretKey
     * @param string $iyzicoBaseUrl
     *
     * @throws \Exception
     */
    public function updateIyzicoSettings($iyzicoApiKey, $iyzicoSecretKey, $iyzicoBaseUrl)
    {
        $this->authorize('iyzico_settings_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'updateIyzicoSettings',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {

            if (!$iyzicoApiKey) {
                throw new \InvalidArgumentException('Api Key belirtilmemiş');
            }
            if (!$iyzicoSecretKey) {
                throw new \InvalidArgumentException('Secret Key belirtilmemiş');
            }
            if (!$iyzicoBaseUrl) {
                throw new \InvalidArgumentException('Base Url belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    iyzico
                SET 
                    iyzico_api_key = :iyzico_api_key,
                    iyzico_secret_key = :iyzico_secret_key,
                    iyzico_base_url = :iyzico_base_url
                WHERE 
                    id = 1
            ';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':iyzico_api_key', $iyzicoApiKey);
            $statement->bindValue(':iyzico_secret_key', $iyzicoSecretKey);
            $statement->bindValue(':iyzico_base_url', $iyzicoBaseUrl);
            $statement->execute();

            $this->logger->info('Updated the iyzico settings', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the iyzico settings', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the iyzico settings', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Init the project
     *
     * @param string $adminEmail
     * @param string $adminPass
     *
     * @throws \Exception
     */
    public function initTheProject($adminEmail, $adminPass)
    {
        $connection = $this->connection;

        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);

        $sqlFile = fopen("globals.sql", "r") or die("Unable to open file!");
        $sql = fread($sqlFile,filesize("globals.sql")); 
        fclose($sqlFile);

        $sql = str_replace(":admin_email", $adminEmail, $sql);
        $sql = str_replace(":admin_password", $adminPass, $sql);

        $statement = $connection->prepare($sql);

        $statement->execute();
    }
}
