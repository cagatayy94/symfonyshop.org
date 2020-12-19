<?php
namespace App\Service\Web;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class SiteSettings
{
    use ServiceTrait;

    public function getFooterData()
    {
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

    public function getAgreementString($columnName)
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                $columnName
            FROM
                agreements_strings
            LIMIT 1
                ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchColumn();
    }

    public function getBankAccounts()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                bank_accounts
            ORDER BY
                name";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getFaq()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                question,
                answer
            FROM
                faq
            ORDER BY
                id";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getBanner()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                id,
                pic
            FROM
                banner
            ORDER BY
                number_of_show
            LIMIT 1";

        $statement = $connection->prepare($sql);

        $statement->execute();

        $banner = $statement->fetch();

        if ($banner) {
            $statement = $connection->prepare('
                UPDATE 
                    banner
                SET
                    number_of_show = number_of_show + 1
                WHERE 
                    id = :id

            ');

            $statement->bindValue(':id', $banner['id']);
            $statement->execute();

            return $banner['pic'];

        }

        return NULL;
    }

    /**
     * Contact form submit
     *
     * @param string $name
     * @param string $email
     * @param string $subject
     * @param string $mobile
     * @param string $message
     * @throws \Exception
     */
    public function contactFormSubmit($name, $email, $subject, $mobile, $message)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'SiteSettings',
            'activity' => 'contactFormSubmit',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $name = trim($name);
        $email = trim($email);
        $subject = trim($subject);
        $mobile = $this->formatMobileNumber(trim($mobile));
        $message = trim($message);

        try {
            if (empty($name)) {
                throw new \InvalidArgumentException('Ad belirtilmemiş');
            }

            if (empty($email)) {
                throw new \InvalidArgumentException('E-posta adresi belirtilmemiş');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('E-posta adresini hatalı girdiniz');
            }

            if (empty($subject)) {
                throw new \InvalidArgumentException('Konu belirtilmemiş');
            }

            if (empty($mobile)) {
                throw new \InvalidArgumentException("Lütfen telefon numaranızı giriniz");
            }

            if (empty($message)) {
                throw new \InvalidArgumentException("Lütfen mesaj yazınız");
            }

            //send notification email
            $this->mailer->send(
                $_ENV['MAIN_MAIL_ADDRESS'], //to address 
                'Yeni iletişim talebi', 
                'Web/Mail/contact.html.php', 
                [
                    'name'=> $name,
                    'email'=> $email,
                    'subject'=> $subject,
                    'mobile'=> $mobile,
                    'message'=> $message,
                ]);

            $this->logger->info('Sent new contact form submit', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not sent new contact form submit', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not sent new contact form submit', $logFullDetails);
            throw new \Exception("Şu an bu talebinizi gerçekleştiremiyoruz lütfen daha sonra tekrar deneyiniz.");            
        }
    }
}
