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
            'entity' => 'User',
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
            $statement->bindValue(':password', $encodedPassword);
            $statement->bindValue(':activation_code', $activationCode);
            $statement->bindValue(':ip_address', $ipAddress);
            $statement->execute();

            $userId = $connection->lastInsertId();

            //send activation email
            $this->mailer->send(
                $email, 
                'Hesap Aktivasyon', 
                'Web/Mail/registration.html.php', 
                [
                    'name'=> $name,
                    'email'=> $email,
                    'activationCode'=> $activationCode,
                ]);

            $logFullDetails['activityId'] = $userId;
            $this->logger->info('Created the user account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not create user account', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not create admin account', $logFullDetails);
            throw new \Exception("Kayıt başarısız bir sorun oluştu lütfen daha sonra tekrar deneyiniz");            
        }
    }

    /**
     * Approve user email
     *
     * @param string $email
     * @param string $code
     * @throws \Exception
     */
    public function approveEmail($email, $code)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'approveEmail',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {
            if (empty($email)) {
                throw new \InvalidArgumentException('invalid_crediantials');
            }

            if (empty($code)) {
                throw new \InvalidArgumentException('invalid_crediantials');
            }
            
            $user = $connection->executeQuery('
                    SELECT
                        ua.id,
                        ua.activation_code,
                        ua.is_email_approved
                    FROM
                        user_account ua
                    WHERE
                        ua.email = :email
                    AND
                        ua.is_deleted = FALSE
                ', [
                    'email' => $email,
                ]
            )->fetch();

            if (!$user) {
                throw new \InvalidArgumentException('invalid_crediantials');
            }

            if ($user['is_email_approved']) {
                throw new \InvalidArgumentException('approved_allready');
            }

            if ($user['activation_code'] != $code) {
                throw new \InvalidArgumentException('invalid_crediantials');
            }

            $statement = $connection->prepare('
                UPDATE user_account 
                SET
                    is_email_approved = true
                WHERE
                    id = :id
            ');

            $statement->bindValue(':id', $user['id']);
            $statement->execute();

            $logFullDetails['activityId'] = $user['id'];
            $this->logger->info('Email approved the user account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not email approved the user account', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not Email approved the user account', $logFullDetails);
            throw new \Exception("Bir sorun oluştu.");            
        }
    }

    /**
     * Send new email approve code
     *
     * @param string $email
     * @throws \Exception
     */
    public function sendNewCode($email)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'sendNewCode',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {
            if (empty($email)) {
                return;
            }
            
            $user = $connection->executeQuery('
                    SELECT
                        ua.id,
                        ua.name,
                        ua.activation_code_created_at,
                        ua.is_email_approved
                    FROM
                        user_account ua
                    WHERE
                        ua.email = :email
                    AND
                        ua.is_deleted = FALSE
                ', [
                    'email' => $email,
                ]
            )->fetch();

            if (!$user) {
                return;
            }

            if ($user['is_email_approved']) {
                return;
            }

            if ($user['activation_code_created_at']) {
                $dateDiff = date_diff(new \DateTime(), new \DateTime($user['activation_code_created_at']));
                $minutes = $dateDiff->days * 24 * 60;
                $minutes += $dateDiff->h * 60;
                $minutes += $dateDiff->i;

                if ($minutes < 15) {
                    return;
                }
            }

            $activationCode = $this->uId();

            $statement = $connection->prepare('
                UPDATE user_account 
                SET
                    activation_code_created_at = NOW(),
                    activation_code = :activation_code
                WHERE
                    id = :id
            ');

            $statement->bindValue(':id', $user['id']);
            $statement->bindValue(':activation_code', $activationCode);
            $statement->execute();

            //send activation email
            $this->mailer->send(
                $email, 
                'Hesap Aktivasyon', 
                'Web/Mail/registration.html.php', 
                [
                    'name'=> $user['name'],
                    'email'=> $email,
                    'activationCode'=> $activationCode,
                ]);

            $logFullDetails['activityId'] = $user['id'];
            $this->logger->info('Resend email approved code', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not resend email approved code', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not resend email approved code', $logFullDetails);
            throw new \Exception("Bir sorun oluştu.");            
        }
    }

    /**
     * Send forgot password email
     *
     * @param string $email
     * @throws \Exception
     */
    public function sendForgotPassword($email)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'sendForgotPassword',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return;
            }
            
            $user = $connection->executeQuery('
                    SELECT
                        ua.id,
                        ua.name,
                        ua.activation_code_created_at,
                        ua.is_email_approved
                    FROM
                        user_account ua
                    WHERE
                        ua.email = :email
                    AND
                        ua.is_deleted = FALSE
                ', [
                    'email' => $email,
                ]
            )->fetch();

            if (!$user) {
                return;
            }

            if ($user['activation_code_created_at']) {
                $dateDiff = date_diff(new \DateTime(), new \DateTime($user['activation_code_created_at']));
                $minutes = $dateDiff->days * 24 * 60;
                $minutes += $dateDiff->h * 60;
                $minutes += $dateDiff->i;

                if ($minutes < 15) {
                    return;
                }
            }

            $activationCode = $this->uId();

            $statement = $connection->prepare('
                UPDATE
                    user_account 
                SET
                    activation_code = :activation_code,
                    activation_code_created_at = NOW()
                WHERE
                    id = :id
            ');

            $statement->bindValue(':id', $user['id']);
            $statement->bindValue(':activation_code', $activationCode);
            $statement->execute();

            //send forgot password email
            $this->mailer->send(
                $email, 
                'Parolanızı Sıfırlayın', 
                'Web/Mail/reset-password.html.php', 
                [
                    'name'=> $user['name'],
                    'email'=> $email,
                    'activationCode'=> $activationCode,
                ]);

            $logFullDetails['activityId'] = $user['id'];
            $this->logger->info('Send reset password email', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not send reset password email', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not send reset password email', $logFullDetails);
            throw new \Exception("Bir sorun oluştu.");            
        }
    }

    /**
     * Control is email and activation code is matching
     *
     * @param string $email
     * @param string $code
     * @throws \Exception
     */
    public function isEmailAndCodeIsMatching($email, $code)
    {
        return $this->connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        user_account ua
                    WHERE
                        ua.email = :email
                    AND
                        ua.is_deleted = FALSE
                    AND
                        ua.activation_code = :code
                ', [
                    'email' => $email,
                    'code' => $code,
                ]
            )->fetchColumn();
    }

    /**
     * Reset forgot password
     *
     * @param string $email
     * @param string $code
     * @param string $password
     * @param string $passwordRepeat
     * @throws \Exception
     */
    public function resetForgotPassword($email, $code, $password, $passwordRepeat)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'resetForgotPassword',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $logDetails['password'] = null;
        $logDetails['passwordRepeat'] = null;

        $connection = $this->connection;

        try {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Email hatalı");
            }

            if ($password != $passwordRepeat) {
                throw new \InvalidArgumentException("Parolalar uyuşmuyor");
                
            }

            $user = $connection->executeQuery('
                    SELECT
                        ua.id,
                        ua.activation_code
                    FROM
                        user_account ua
                    WHERE
                        ua.email = :email
                    AND
                        ua.is_deleted = FALSE
                ', [
                    'email' => $email,
                ]
            )->fetch();

            if (!$user) {
                throw new \InvalidArgumentException("Email veya kod hatalı");
            }

            if ($user['activation_code'] != $code) {
                throw new \InvalidArgumentException("Email veya kod hatalı");
            }

            $userEntity = new UserAccount();
            $encodedPassword = $this->passwordEncoder->encodePassword($userEntity, $password);

            $statement = $connection->prepare('
                UPDATE
                    user_account 
                SET
                    activation_code = NULL,
                    password = :password
                WHERE
                    id = :id
            ');

            $statement->bindValue(':id', $user['id']);
            $statement->bindValue(':password', $encodedPassword);
            $statement->execute();

            $logFullDetails['activityId'] = $user['id'];
            $this->logger->info('Reset user password', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not reset user password', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not reset user password', $logFullDetails);
            throw new \Exception("Bir sorun oluştu.");            
        }
    }
}
