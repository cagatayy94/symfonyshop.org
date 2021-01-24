<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Account
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

    public function getAll()
    {
        $this->authorize('account_list_show');

        $connection = $this->connection;

        $sql = "
            SELECT
                aa.id,
                aa.email,
                aa.name,
                aa.surname,
                aa.mobile,

                ap.name profile_name
            FROM
                admin_account aa
            LEFT JOIN
                admin_account_profile aap ON  aa.id = aap.admin_account_id
            LEFT JOIN
                admin_profile ap ON aap.admin_profile_id = ap.id
            WHERE
                aa.is_deleted = false
            ORDER BY
                aa.id 
            DESC
                ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Create admin account
     *
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $password
     * @param string $passwordRepeat
     * @param string $mobile
     * @param string $title
     * @throws \Exception
     */
    public function create($name, $surname, $email, $password, $passwordRepeat, $mobile, $profileId)
    {
        $this->authorize('account_create');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logDetails['password'] = null;
        $logDetails['passwordRepeat'] = null;

        $logFullDetails = [
            'entity' => 'Admin',
            'activity' => 'create',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $name = trim($name);
        $surname = trim($surname);
        $email = trim($email);
        $mobile = trim($mobile);
        $profileId = intval ($profileId);

        try {
            if (empty($name)) {
                throw new \InvalidArgumentException('Ad belirtilmemiş');
            }

            if (empty($surname)) {
                throw new \InvalidArgumentException('Soyad belirtilmemiş');
            }

            if (empty($profileId)) {
                throw new \InvalidArgumentException('Profil belirtilmemiş');
            }

            if (empty($email)) {
                throw new \InvalidArgumentException('E-posta adresi belirtilmemiş');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('E-posta adresini hatalı girdiniz');
            }

            if (empty($mobile)) {
                throw new \InvalidArgumentException("Lütfen telefon numaranızı giriniz");
            }

            if (empty($password) || empty($passwordRepeat)) {
                throw new \InvalidArgumentException("Lütfen parolanızı ve tekrarını giriniz");
            }

            if ($password != $passwordRepeat) {
                throw new \InvalidArgumentException('Yeni parolalar birbiri ile uyuşmuyor');
            }
            
            $emailCount = $connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        admin_account ua
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

            $accountEntity = new AdminAccount();
            $encodedPassword = $this->passwordEncoder->encodePassword($accountEntity, $password);

            try {
                $connection->beginTransaction();
                $statement = $connection->prepare('
                    INSERT INTO admin_account
                        (name, surname, email, mobile, password)
                    VALUES
                        (:name, :surname, :email, :mobile, :password)
                ');


                $statement->bindValue(':name', $name);
                $statement->bindValue(':surname', $surname);
                $statement->bindValue(':email', $email);
                $statement->bindValue(':mobile', $mobile);
                $statement->bindValue(':password', $encodedPassword);
                $statement->execute();

                $adminId = $connection->lastInsertId();

                $statement = '';

                $statement = $connection->prepare('
                    INSERT INTO admin_account_profile
                        (admin_account_id, admin_profile_id)
                    VALUES
                        (:admin_account_id, :admin_profile_id)
                ');

                $statement->bindValue(':admin_account_id', $adminId);
                $statement->bindValue(':admin_profile_id', $profileId);
                $statement->execute();

                $connection->commit();
                
            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $adminId = $connection->lastInsertId();

            $logFullDetails['activityId'] = $adminId;
            $this->logger->info('Created the admin account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create admin account', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create admin account', $logFullDetails);

            throw $exception;
        }
    }

    public function getAllProfile()
    {
        $this->authorize('account_list_show');

        $connection = $this->connection;

        $sql = "
            SELECT
                ap.id,
                ap.name
            FROM
                admin_profile ap
            WHERE
                ap.is_deleted = false
            ORDER BY
                ap.id
                ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll();

        foreach ($result as $value) {
            $finalResult[$value['id']]['name'] = $value['name'];

            $sql = "
                SELECT
                    aa.id,
                    aa.name,
                    aa.surname
                FROM
                    admin_account aa
                LEFT JOIN
                    admin_account_profile acp ON acp.admin_account_id = aa.id
                WHERE
                    is_deleted = false
                AND
                    acp.admin_profile_id = :admin_profile_id
                ORDER BY
                    aa.name, aa.surname
                DESC
                    ";

            $statement = $connection->prepare($sql);

            $statement->bindValue(':admin_profile_id', $value['id']);

            $statement->execute();

            $result2 = $statement->fetchAll();

            if (!$result2) {
                $finalResult[$value['id']]['admins'] = null;
            }

            foreach ($result2 as $value2) {
                $finalResult[$value['id']]['admins'][$value2['id']] = $value2['name'].' '.$value2['surname'];
            }

        }

        return $finalResult;
    }

    /**
     * Get Account Detail
     *
     * @param int $id identifier of admin
     * @throws \Exception
     */
    public function getAccountDetail($id)
    {
        $this->authorize('account_detail_show');

        $connection = $this->connection;

        $sql ="
            SELECT
                aa.id AS id,
                aa.email AS email,
                aa.name AS name,
                aa.surname AS surname,
                aa.mobile AS mobile,
                aa.email AS email,

                ap.name AS profile_name,
                ap.id AS profile_id
            FROM
                admin_account aa
            LEFT JOIN
                admin_account_profile aap ON aa.id = aap.admin_account_id
            LEFT JOIN
                admin_profile ap ON aap.admin_profile_id = ap.id
            WHERE
                aa.is_deleted = FALSE
            AND
                aa.id =:id";

        $statement = $connection->prepare($sql);

        $statement->bindValue(':id', $id);

        $statement->execute();

        $adminDetails = $statement->fetch();

        $sql ="
            SELECT
                ap.name AS name,
                ap.slug AS slug
            FROM
                admin_permission ap
            LEFT JOIN
                admin_profile_permission app on ap.id = app.admin_permission_id
            WHERE
                app.admin_profile_id = :admin_profile_id
            ORDER BY
                ap.name
        ";

        $statement = $connection->prepare($sql);

        $statement->bindValue(':admin_profile_id', $adminDetails['profile_id']);

        $statement->execute();

        $allPermissions = $statement->fetchAll();

        $result = [
            'details' => $adminDetails,
            'allPermissions' => $allPermissions
        ];

        return $result;
    }

    /**
     * Update admin account
     *
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $password
     * @param string $passwordRepeat
     * @param string $mobile
     * @param string $title
     * @throws \Exception
     */
    public function update($id, $name, $surname, $email, $password, $passwordRepeat, $mobile, $profileId)
    {
        $this->authorize('account_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logDetails['password'] = null;
        $logDetails['passwordRepeat'] = null;

        $logFullDetails = [
            'entity' => 'Admin',
            'activity' => 'update',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);
        $name = trim($name);
        $surname = trim($surname);
        $email = trim($email);
        $mobile = trim($mobile);
        $profileId = intval ($profileId);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Admin Id belirtilmemiş');
            }
            if (empty($name)) {
                throw new \InvalidArgumentException('Ad belirtilmemiş');
            }

            if (empty($surname)) {
                throw new \InvalidArgumentException('Soyad belirtilmemiş');
            }

            if (empty($profileId)) {
                throw new \InvalidArgumentException('Profil belirtilmemiş');
            }

            if (empty($email)) {
                throw new \InvalidArgumentException('E-posta adresi belirtilmemiş');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('E-posta adresini hatalı girdiniz');
            }

            if (empty($mobile)) {
                throw new \InvalidArgumentException("Telefon numarası belirtilmemiş");
            }

            if ($password != $passwordRepeat) {
                throw new \InvalidArgumentException('Yeni parolalar birbiri ile uyuşmuyor');
            }

            $emailCount = $connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        admin_account ua
                    WHERE
                        ua.email = :email
                    AND
                        ua.id != :id
                    AND
                        ua.is_deleted = FALSE
                ', [
                    'email' => $email,
                    'id' => $id
                ]
            )->fetchColumn();

            if ($emailCount > 0) {
                throw new \InvalidArgumentException('Belirttiğiniz e-posta adresi sisteme zaten kayıtlı');
            }

            $accountEntity = new AdminAccount();
            $encodedPassword = $this->passwordEncoder->encodePassword($accountEntity, $password);

            try {
                $connection->beginTransaction();

                $sql = '
                    UPDATE 
                        admin_account
                    SET 
                        name = :name, 
                        surname = :surname, 
                        email = :email, 
                        mobile = :mobile 
                ';

                if ($password && $passwordRepeat) {
                    $sql .= ',password = :password';
                }


                $sql .= '
                    WHERE
                        id = :id';

                $statement = $connection->prepare($sql);


                $statement->bindValue(':id', $id);
                $statement->bindValue(':name', $name);
                $statement->bindValue(':surname', $surname);
                $statement->bindValue(':email', $email);
                $statement->bindValue(':mobile', $mobile);


                if ($password && $passwordRepeat) {
                    $statement->bindValue(':password', $encodedPassword);
                }


                $statement->execute();

                $statement = '';

                $statement = $connection->prepare('
                    UPDATE 
                        admin_account_profile
                    SET
                        admin_profile_id = :admin_profile_id
                    WHERE
                        admin_account_id = :admin_account_id
                ');

                $statement->bindValue(':admin_account_id', $id);
                $statement->bindValue(':admin_profile_id', $profileId);
                $statement->execute();

                $connection->commit();
                
            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Updated the admin account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update admin account', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update admin account', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Create admin profile
     *
     * @param string $name
     * @throws \Exception
     */
    public function profileCreate($name)
    {
        $this->authorize('profile_create');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());


        $logFullDetails = [
            'entity' => 'Admin',
            'activity' => 'profileCreate',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $name = trim($name);

        try {
            if (empty($name)) {
                throw new \InvalidArgumentException('Profil ismi belirtin');
            }

            $nameCount = $connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        admin_profile ap
                    WHERE
                        ap.name = :name
                ', [
                    'name' => $name
                ]
            )->fetchColumn();

            if ($nameCount > 0) {
                throw new \InvalidArgumentException('Belirttiğiniz isimde bir profil mevcut farklı bir isim deneyin');
            }

            $statement = $connection->prepare('
                INSERT INTO admin_profile
                    (name)
                VALUES
                    (:name)
            ');

            $statement->bindValue(':name', $name);
            $statement->execute();

            $profileId = $connection->lastInsertId();

            $logFullDetails['activityId'] = $profileId;
            $this->logger->info('Created the admin profile', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create admin profile', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create admin profile', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Get Profile Detail
     *
     * @param int $id
     * @throws \Exception
     */
    public function getProfileDetail($id)
    {
        $this->authorize('profile_detail_show');

        $connection = $this->connection;

        $sql ="
            SELECT
                a.name permission_name,
                a.id permission_id,
                ap.name profile_name,
                ap.id profile_id
            FROM
                admin_profile ap
            LEFT JOIN
                admin_profile_permission app ON ap.id = app.admin_profile_id
            LEFT JOIN
                admin_permission a on app.admin_permission_id = a.id
            WHERE
                ap.id = :id";

        $statement = $connection->prepare($sql);

        $statement->bindValue(':id', $id);

        $statement->execute();

        $result = $statement->fetchAll();

        $finalResult = [];

        foreach ($result as $value) {
            $finalResult['id'] = $value['profile_id'];
            $finalResult['name'] = $value['profile_name'];
            $finalResult['permissions'][$value['permission_id']] = $value['permission_name'];
        }

        return $finalResult;
    }

    public function getAllPermissions()
    {
        $connection = $this->connection;

        $statement = $connection->prepare("
            SELECT
                *
            FROM
                admin_permission ap
            ORDER BY
                ap.name");

        $statement->execute();
        
        return $statement->fetchAll();
    }

    /**
     * Update profile 
     *
     * @param string $id
     * @param string $name
     * @param string $permission
     * @throws \Exception
     */
    public function profileUpdate($id, $name, $permission)
    {
        $this->authorize('profile_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Admin',
            'activity' => 'profileUpdate',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);
        $name = trim($name);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }
            if (empty($name)) {
                throw new \InvalidArgumentException('Ad belirtilmemiş');
            }
            if (!count($permission)) {
                throw new \InvalidArgumentException('İzin seçiniz');
            }

            try {
                $connection->beginTransaction();


                $sql = '
                    UPDATE 
                        admin_profile
                    SET 
                        name = :name
                    WHERE
                        id = :id';

                $statement = $connection->prepare($sql);

                $statement->bindValue(':id', $id);
                $statement->bindValue(':name', $name);

                $statement->execute();

                $statement = '';

                $statement = $connection->prepare('
                    DELETE FROM
                        admin_profile_permission
                    WHERE
                        admin_profile_id = :admin_profile_id
                ');

                $statement->bindValue(':admin_profile_id', $id);
                $statement->execute();

                foreach ($permission as $value) {
                    $statement = '';

                    $statement = $connection->prepare('
                        INSERT INTO 
                            admin_profile_permission ( admin_profile_id, admin_permission_id )
                        VALUES
                            (:admin_profile_id, :admin_permission_id)
                    ');

                    $statement->bindValue(':admin_profile_id', $id);
                    $statement->bindValue(':admin_permission_id', $value);
                    $statement->execute();
                       
                }

                $connection->commit();
                
            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Updated the profile', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update profile', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update profile', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Delete admin account
     *
     * @param int $id
     * @throws \Exception
     */
    public function delete($id)
    {
        $this->authorize('account_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Admin',
            'activity' => 'delete',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Admin Id belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    admin_account
                SET 
                    is_deleted = TRUE
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Updated the admin account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update admin account', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update admin account', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Delete the admin profile
     *
     * @param int $profileId
     * @throws \Exception
     */
    public function profileDelete($profileId)
    {
        $this->authorize('profile_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'AdminProfile',
            'activity' => 'delete',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $profileId = intval($profileId);

        try {
            if (!$profileId) {
                throw new \InvalidArgumentException('Profil Id belirtilmemiş');
            }

            $sql = '
                SELECT 
                    aa.id 
                FROM
                    admin_account_profile aap
                LEFT JOIN
                    admin_account aa ON aa.id = aap.admin_account_id
                WHERE
                    aap.admin_profile_id = :profile_id
                AND
                    aa.is_deleted = FALSE';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':profile_id', $profileId);

            $statement->execute();

            $result = $statement->fetchAll();

            if ($result) {
                throw new \InvalidArgumentException('Profile atanmış kişiler varken profili silemezsiniz');
            }

            $sql = '
                UPDATE 
                    admin_profile
                SET 
                    is_deleted = TRUE
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $profileId);

            $statement->execute();

            $logFullDetails['activityId'] = $profileId;
            $this->logger->info('Updated the profile account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update profile account', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update profile account', $logFullDetails);

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

        $sqlFile = fopen("globals.sql", "r") or die("Unable to open file!");
        $sql = fread($sqlFile,filesize("globals.sql")); 
        fclose($sqlFile);

        $accountEntity = new AdminAccount();
        $encodedPassword = $this->passwordEncoder->encodePassword($accountEntity, $adminPass);

        $sql = str_replace(":admin_email", $adminEmail, $sql);
        $sql = str_replace(":admin_password", $encodedPassword, $sql);

        $sqlArray = explode(';', $sql);

        array_pop($sqlArray);

        foreach ($sqlArray as $value) {
            $statement = $connection->prepare($value);

            $statement->execute();
        }
    }
}
