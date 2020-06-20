<?php
namespace App\Service\Web;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Web\UserAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class User
{
    use ServiceTrait;
    use AdminServiceTrait;

    /**
     * @var EncoderFactoryInterface
     */
    private $passwordEncoder;

    public function setEncoderFactory(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Create user account
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $agreements
     * @param string $mobile
     * @throws \Exception
     */
    public function create($name, $email, $password, $agreements, $ipAddress, $mobile = null)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logDetails['password'] = null;

        $logFullDetails = [
            'entity' => 'Admin',
            'activity' => 'create',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $name = trim($name);
        $email = trim($email);
        $mobile = trim($mobile);

        try {

            if (!$agreements) {
                throw new \InvalidArgumentException('Sözleşme onaylanmamış');
            }

            if (empty($name)) {
                throw new \InvalidArgumentException('Ad belirtilmemiş');
            }

            if (empty($email)) {
                throw new \InvalidArgumentException('E-posta adresi belirtilmemiş');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('E-posta adresini hatalı girdiniz');
            }

            if (empty($password)) {
                throw new \InvalidArgumentException("Lütfen parolanızı giriniz");
            }

            if (empty($ipAddress)) {
                throw new \InvalidArgumentException("İp adresiniz belirlenemedi");
            }
            
            $emailCount = $connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        user_account ua
                    WHERE
                        ua.email = :email
                    AND
                        ua.is_deleted = FALSE
                ', [
                    'email' => $email,
                ]
            )->fetchColumn();

            if ($emailCount > 0) {
                throw new \InvalidArgumentException('Belirttiğiniz e-posta adresi sisteme zaten kayıtlı');
            }

            $userEntity = new UserAccount();
            $encodedPassword = $this->passwordEncoder->encodePassword($userEntity, $password);
            $activationCode = $this->uId();

            $statement = $connection->prepare('
                INSERT INTO user_account
                    (name, email, mobile, password, activation_code, ip_address)
                VALUES
                    (:name, :email, :mobile, :password, :activation_code, :ip_address)
            ');

            $statement->bindValue(':name', $name);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':mobile', $mobile ? $mobile : null);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':activation_code', $activationCode);
            $statement->bindValue(':ip_address', $ipAddress);
            $statement->execute();

            $userId = $connection->lastInsertId();

            $logFullDetails['activityId'] = $userId;
            $this->logger->info('Created the user account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not create user account', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not create admin account', $logFullDetails);
            throw new \Exception("Kayıt başarısız bir sorun oluştu");            
        }
    }
}
