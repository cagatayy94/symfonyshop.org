<?php
namespace App\Security\Admin;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AdminProvider implements UserProviderInterface, PasswordUpgraderInterface
{

    use ServiceTrait;
    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $connection = $this->connection;

        $statement = $connection->prepare('
            SELECT
                a.*
            FROM 
                admin_account a
            WHERE
                a.email = :email
            AND 
                a.is_deleted = FALSE
        ');

        $statement->bindValue(':email', $username);
        $statement->execute();

        $record = $statement->fetch();

        if ($record) {
            $account = new AdminAccount($record['id']);
            $account->setEmail($record['email']);
            $account->setPassword($record['password']);
            $account->setName($record['name']);
            $account->setSurname($record['surname']);
            $account->setMobile($record['mobile']);
            $account->setIsDeleted($record['is_deleted']);

            $roles = ['ROLE_ADMIN'];

            try {
                $selectRolesStatement = $connection->prepare('
                    SELECT
                        ap.slug
                    FROM
                        admin_account aa
                    LEFT JOIN
                        admin_account_profile aap ON aa.id = aap.admin_account_id
                    LEFT JOIN
                        admin_profile_permission aprp ON aprp.admin_profile_id =  aap.admin_profile_id
                    LEFT JOIN
                        admin_permission ap ON ap.id = aprp.admin_permission_id
                    WHERE
                        aa.id = :admin_account_id
                ');

                $selectRolesStatement->bindValue(':admin_account_id', $record['id']);
                $selectRolesStatement->execute();      

                while ($role = $selectRolesStatement->fetch()) {
                    $roles[] = $role['slug'];
                }
            } catch (\Exception $exception) {                
            }

            $account->setRoles($roles);

            return $account;
        }
        
        throw new UsernameNotFoundException('Your username or password is invalid.');
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof AdminAccount) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
        return $this->loadUserByUsername($user->getEmail());
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return AdminAccount::class === $class;
    }

    /**
     * Upgrades the encoded password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        // TODO: when encoded passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newEncodedPassword);
    }
}
