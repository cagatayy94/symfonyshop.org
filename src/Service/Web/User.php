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

    /**
     * Unsubscribe the user email
     *
     * @param string $email
     * @throws \Exception
     */
    public function unsubscribe($email)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'unsubscribe',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Email hatalı");
            }

            $statement = $connection->prepare('
                UPDATE
                    user_account 
                SET
                    is_unsubscribe = true
                WHERE
                    email = :email;
                UPDATE
                    email_marketing_leads 
                SET
                    is_unsubscribe = true
                WHERE
                    email = :email;

            ');

            $statement->bindValue(':email', $email);
            $statement->execute();

            $logFullDetails['activityId'] = $email;
            $this->logger->info('Email unsubscribed the user account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not email unsubscribed the user account', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not Email unsubscribed the user account', $logFullDetails);
            throw new \Exception("Bir sorun oluştu.");            
        }
    }

    /**
     * Subscribe the email
     *
     * @param string $email
     * @throws \Exception
     */
    public function subscribe($email)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'subscribe',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Email hatalı");
            }

            $statement = $connection->prepare('
                INSERT INTO email_marketing_leads
                    (email)
                VALUES
                    (:email)
            ');

            $statement->bindValue(':email', $email);
            $statement->execute();

            $logFullDetails['activityId'] = $email;
            $this->logger->info('Email subscribed', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not email subscribed', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not email subscribed', $logFullDetails);
            throw new \Exception("Bir sorun oluştu.");            
        }
    }

    /**
     * Change Password
     *
     * @param user object $user
     * @param current password $currentPassword
     * @param new password $newPassword
     * @param new password repeat $newPasswordRepeat
     * @throws \Exception
     */
    public function changePasswordOnProfile($user, $currentPassword, $newPassword, $newPasswordRepeat)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'changePasswordOnProfile',
            'activityId' => 0,
            'details' => $logDetails
        ];

        try {
            if (empty($user)) {
                throw new \InvalidArgumentException("Kullanıcı bulunamadı");
            }

            if (empty($currentPassword)) {
                throw new \InvalidArgumentException("Lütfen mevcut parolanızı giriniz");
            }

            if (empty($newPassword)) {
                throw new \InvalidArgumentException("Lütfen yeni parolanızı giriniz");
            }

            if (empty($newPasswordRepeat)) {
                throw new \InvalidArgumentException("Lütfen yeni parolanızın tekrarını giriniz");
            }

            if ($newPasswordRepeat != $newPassword) {
                throw new \InvalidArgumentException("Parolalar uyuşmuyor");
            }

            $isPasswordValid = $this->passwordEncoder->isPasswordValid($user, $currentPassword);

            if (!$isPasswordValid) {
                throw new \InvalidArgumentException("Parolanızı yanlış girdiniz");
            }

            $encodedNewPassword = $this->passwordEncoder->encodePassword($user, $newPassword);

            $user->setPassword($encodedNewPassword);

            $statement = $this->connection->prepare('
                UPDATE
                    user_account
                SET
                    password = :password
                WHERE
                    id = :id
            ');

            $statement->bindValue(':password', $encodedNewPassword);
            $statement->bindValue(':id', $user->getId());
            $statement->execute();

            $logFullDetails['activityId'] = $user->getId();
            $this->logger->info('Changed user password', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not changed user password', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not changed user password', $logFullDetails);
            throw new \Exception("Bir sorun oluştu.");            
        }
    }

    /**
     * Change Password
     *
     * @param user object $user
     * @param mobile $mobile
     * @throws \Exception
     */
    public function changeMobileOnProfile($user, $mobile)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'changeMobileOnProfile',
            'activityId' => 0,
            'details' => $logDetails
        ];

        try {

            $mobile = $this->formatMobileNumber($mobile);

            if (empty($user)) {
                throw new \InvalidArgumentException("Kullanıcı bulunamadı");
            }

            if (empty($mobile)) {
                throw new \InvalidArgumentException("Lütfen mevcut telefon belirtiniz");
            }

            //there is a phone ?
            $mobileCount = $this->connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        user_account ua
                    WHERE
                        ua.mobile = :mobile
                    AND
                        ua.is_deleted = FALSE
                ', [
                    'mobile' => $mobile,
                ]
            )->fetchColumn();

            if ($mobileCount > 0) {
                throw new \InvalidArgumentException('Belirttiğiniz numara başka bir kişiye kayıtlı');
            }

            $user->setMobile($mobile);

            $statement = $this->connection->prepare('
                UPDATE
                    user_account
                SET
                    mobile = :mobile
                WHERE
                    id = :id
            ');

            $statement->bindValue(':mobile', $mobile);
            $statement->bindValue(':id', $user->getId());
            $statement->execute();

            $logFullDetails['activityId'] = $user->getId();
            $this->logger->info('Changed user mobile', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not changed user mobile', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not changed user mobile', $logFullDetails);
            throw new \Exception($exception->getMessage());            
            throw new \Exception("Bir sorun oluştu.");            
        }
    }

    /**
     * Get user addresses
     *
     * @param obj $user
     * @throws \Exception
     */
    public function getUserAccountAddresses($user)
    {
        return $this->connection->executeQuery('
            SELECT
                uaa.address_id address_id,
                a.address_name address_name,
                a.full_name full_name,
                a.address address,
                a.county county,
                a.city city,
                a.mobile mobile
            FROM
                address a
            LEFT JOIN
                user_account_address uaa ON a.id = uaa.address_id
            WHERE
                uaa.user_account_id = :user_account_id
                ', [
                    'user_account_id' => $user->getId(),
                ]
            )->fetchAll();
    }

    /**
     * Create address
     *
     * @param string $user
     * @param string $addressName
     * @param string $fullName
     * @param string $address
     * @param string $county
     * @param string $city
     * @param string $mobile
     * @throws \Exception
     */
    public function addUserAddresses($user, $addressName, $fullName, $address, $county, $city, $mobile)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'addUserAddresses',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $addressName = trim($addressName);
        $fullName = trim($fullName);
        $address = trim($address);
        $county = trim($county);
        $city = trim($city);
        $mobile = $this->formatMobileNumber($mobile);

        try {

            if (!$user) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (empty($addressName)) {
                throw new \InvalidArgumentException('Adres ismi belirtilmemiş');
            }

            if (empty($fullName)) {
                throw new \InvalidArgumentException('Adresteki isim belirtilmemiş');
            }

            if (empty($address)) {
                throw new \InvalidArgumentException('Adres belirtilmemiş');
            }

            if (empty($county)) {
                throw new \InvalidArgumentException('İlçe belirtilmemiş');
            }

            if (empty($city)) {
                throw new \InvalidArgumentException('Şehir belirtilmemiş');
            }

            if (empty($mobile)) {
                throw new \InvalidArgumentException('Telefon belirtilmemiş');
            }

            try {
                $connection->beginTransaction();

                $statement = $connection->prepare('
                    INSERT INTO address
                        (address_name, full_name, address, county, city, mobile)
                    VALUES
                        (:address_name, :full_name, :address, :county, :city, :mobile)
                    RETURNING id;
                ');

                $statement->bindValue(':address_name', $addressName);
                $statement->bindValue(':full_name', $fullName);
                $statement->bindValue(':address', $address);
                $statement->bindValue(':county', $county);
                $statement->bindValue(':city', $city);
                $statement->bindValue(':mobile', $mobile);

                $statement->execute();

                $addressId = $connection->lastInsertId();

                $statement = $connection->prepare('
                    INSERT INTO user_account_address
                        (user_account_id, address_id)
                    VALUES
                        (:user_account_id, :address_id)
                ');

                $statement->bindValue(':user_account_id', $user->getId());
                $statement->bindValue(':address_id', $addressId);
                $statement->execute();

                $connection->commit();
            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $logFullDetails['activityId'] = $addressId;
            $this->logger->info('Created the address', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not created the address', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not created the address', $logFullDetails);
            throw new \Exception("Kayıt başarısız bir sorun oluştu lütfen daha sonra tekrar deneyiniz");            
        }
    }

    /**
     * Remove address
     *
     * @param string $user
     * @param string $id
     * @throws \Exception
     */
    public function removeUserAddress($user, $id)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'removeUserAddress',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $addressName = intval($id);

        try {

            if (!$user) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (empty($id)) {
                throw new \InvalidArgumentException('Adres belirtilmemiş');
            }

            $statement = $connection->prepare('
                DELETE FROM 
                    user_account_address
                WHERE 
                    address_id = :id
                AND
                    user_account_id = :user_account_id
            ');

            $statement->bindValue(':id', $id);
            $statement->bindValue(':user_account_id', $user->getId());
            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the address', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not deleted the address', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not deleted the address', $logFullDetails);
            throw new \Exception("İşlem başarısız bir sorun oluştu lütfen daha sonra tekrar deneyiniz");            
        }
    }

    /**
     * Update address
     *
     * @param obj $user
     * @param int $addressId
     * @param string $addressName
     * @param string $fullName
     * @param string $address
     * @param string $county
     * @param string $city
     * @param string $mobile
     * @throws \Exception
     */
    public function updateUserAddresses($user, $addressId, $addressName, $fullName, $address, $county, $city, $mobile)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'updateUserAddresses',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $addressId = intval($addressId);
        $addressName = trim($addressName);
        $fullName = trim($fullName);
        $address = trim($address);
        $county = trim($county);
        $city = trim($city);
        $mobile = $this->formatMobileNumber($mobile);

        try {

            if (!$user) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (!$addressId) {
                throw new \InvalidArgumentException('Adres bulunamadı');
            }

            if (empty($addressName)) {
                throw new \InvalidArgumentException('Adres ismi belirtilmemiş');
            }

            if (empty($fullName)) {
                throw new \InvalidArgumentException('Adresteki isim belirtilmemiş');
            }

            if (empty($address)) {
                throw new \InvalidArgumentException('Adres belirtilmemiş');
            }

            if (empty($county)) {
                throw new \InvalidArgumentException('İlçe belirtilmemiş');
            }

            if (empty($city)) {
                throw new \InvalidArgumentException('Şehir belirtilmemiş');
            }

            if (empty($mobile)) {
                throw new \InvalidArgumentException('Telefon belirtilmemiş');
            }

            $isUserHasThatAddress = $connection->executeQuery('
                SELECT
                    count(id)
                FROM
                    user_account_address
                WHERE
                    address_id = :address_id
                AND
                    user_account_id = :user_account_id
                ', [
                    'address_id' => $addressId,
                    'user_account_id' => $user->getId(),
                ]
            )->fetchColumn();

            if (!$isUserHasThatAddress) {
                throw new \InvalidArgumentException('Adres bulunamadı');
            }

            $statement = $connection->prepare('
                UPDATE
                    address
                SET
                    address_name = :address_name,
                    full_name = :full_name,
                    address = :address,
                    county = :county,
                    city = :city,
                    mobile = :mobile
                WHERE
                    id = :id
            ');

            $statement->bindValue(':address_name', $addressName);
            $statement->bindValue(':full_name', $fullName);
            $statement->bindValue(':address', $address);
            $statement->bindValue(':county', $county);
            $statement->bindValue(':city', $city);
            $statement->bindValue(':mobile', $mobile);
            $statement->bindValue(':id', $addressId);

            $statement->execute();

            $logFullDetails['activityId'] = $addressId;
            $this->logger->info('Updated the address', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not updated the address', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not updated the address', $logFullDetails);
            throw new \Exception("Güncelleme başarısız bir sorun oluştu lütfen daha sonra tekrar deneyiniz");            
        }
    }

    /**
     * Get user favorites
     *
     * @param obj $user
     * @throws \Exception
     */
    public function getUserAccountFavorites($user, $limit, $currentPage)
    {
        return $this->connection->executeQuery('
            SELECT
                COUNT(*) OVER() AS total_count,
                uaf.id fav_id,
                p.id product_id,
                p.name product_name,
                p.price product_price,
                (
                    SELECT
                        ROUND(AVG(pc.rate))
                    FROM
                        product_comment pc
                    WHERE
                        pc.product_id = p.id
                ) rate,
                (
                    SELECT
                        path
                    FROM
                        product_photo pc
                    WHERE
                        pc.product_id = p.id
                    LIMIT 1
                ) path
            FROM
                user_account_favorite uaf
            LEFT JOIN
                product p on p.id = uaf.product_id
            WHERE
                p.is_deleted = false
            AND
                uaf.user_account_id = :user_account_id
            LIMIT
                :limit
            OFFSET
                :offset

                ', [
                    'user_account_id' => $user->getId(),
                    'limit' => $limit,
                    'offset' => $limit * ($currentPage - 1)
                ]
        )->fetchAll();
    }

     /**
     * Remove favorite
     *
     * @param string $user
     * @param string $id
     * @throws \Exception
     */
    public function removeUserFavorite($user, $id)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'removeUserFavorite',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {

            if (!$user) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (empty($id)) {
                throw new \InvalidArgumentException('Favori belirtilmemiş');
            }

            $statement = $connection->prepare('
                DELETE FROM 
                    user_account_favorite
                WHERE 
                    id = :id
                AND
                    user_account_id = :user_account_id
            ');

            $statement->bindValue(':id', $id);
            $statement->bindValue(':user_account_id', $user->getId());
            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the favorite', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not deleted the favorite', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not deleted the favorite', $logFullDetails);
            throw new \Exception("İşlem başarısız bir sorun oluştu lütfen daha sonra tekrar deneyiniz");            
        }
    }

    /**
     * Get user comments
     *
     * @param obj $user
     * @throws \Exception
     */
    public function getUserAccountComments($user, $limit, $currentPage)
    {
        return $this->connection->executeQuery('
            SELECT
                COUNT(*) OVER() AS total_count,
                pc.id comment_id,
                pc.created_at,
                pc.comment,
                pc.rate,
                p.name product_name,
                p.id product_id,
                (
                    SELECT
                        path
                    FROM
                        product_photo pc
                    WHERE
                        pc.product_id = p.id
                    LIMIT 1
                ) path
            FROM
                product_comment pc
            LEFT JOIN
                product p ON pc.product_id = p.id
            WHERE
                pc.user_id = :user_account_id
            AND
                p.is_deleted = false
            AND
                pc.is_deleted = false
            LIMIT
                :limit
            OFFSET
                :offset

                ', [
                    'user_account_id' => $user->getId(),
                    'limit' => $limit,
                    'offset' => $limit * ($currentPage - 1)
                ]
        )->fetchAll();
    }

    /**
     * Remove favorite
     *
     * @param user object $user
     * @param string $id
     * @throws \Exception
     */
    public function removeUserComment($user, $id)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'User',
            'activity' => 'removeUserComment',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {

            if (!$user) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (empty($id)) {
                throw new \InvalidArgumentException('Yorum belirtilmemiş');
            }

            $statement = $connection->prepare('
                UPDATE
                    product_comment
                SET
                    is_deleted = true
                WHERE 
                    id = :id
                AND
                    user_id = :user_id
            ');

            $statement->bindValue(':id', $id);
            $statement->bindValue(':user_id', $user->getId());
            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the comment', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not deleted the comment', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not deleted the comment', $logFullDetails);
            throw new \Exception("İşlem başarısız bir sorun oluştu lütfen daha sonra tekrar deneyiniz");            
        }
    }
}
