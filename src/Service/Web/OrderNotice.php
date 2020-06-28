<?php
namespace App\Service\Web;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class OrderNotice
{
    use ServiceTrait;

    public function createOrderNotice($name, $email, $bankId, $mobile, $message)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'OrderNotice',
            'activity' => 'createOrderNotice',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $name = trim($name);
        $email = trim($email);
        $bankId = intval ($bankId);
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

            if (empty($bankId)) {
                throw new \InvalidArgumentException('Banka belirtilmemiş');
            }

            if (empty($mobile)) {
                throw new \InvalidArgumentException("Lütfen telefon numaranızı giriniz");
            }

            $statement = $connection->prepare('
                INSERT INTO order_notice
                    (name, email, mobile, message, bank_id)
                VALUES
                    (:name, :email, :mobile, :message, :bank_id)
            ');


            $statement->bindValue(':name', $name);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':mobile', $mobile);
            $statement->bindValue(':message', $message);
            $statement->bindValue(':bank_id', $bankId);

            $statement->execute();

            $this->logger->info('Created the order notice', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not create the order notice', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not create the order notice', $logFullDetails);
            throw new \Exception("Şu an bu talebinizi gerçekleştiremiyoruz lütfen daha sonra tekrar deneyiniz.");            
        }
    }
}
