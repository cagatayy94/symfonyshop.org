<?php
namespace App\Security\Web;

use App\Entity\Web\UserAccount;
use App\Sdk\ServiceTrait;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
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
                user_account a
            WHERE
                a.email = :email
            AND 
                a.is_deleted = FALSE
        ');

        $statement->bindValue(':email', $username);
        $statement->execute();

        $record = $statement->fetch();

        if ($record) {
            $account = new UserAccount($record['id']);
            $account->setEmail($record['email']);
            $account->setPassword($record['password']);
            $account->setName($record['name']);
            $account->setIsDeleted($record['is_deleted']);
            $account->setMobile($record['mobile']);
            $account->setIsMobileApproved($record['is_mobile_approved']);
            $account->setIsEmailApproved($record['is_mobile_approved']);
            $account->setActivationCode($record['activation_code']);
            $account->setIsUnsubscribe($record['is_unsubscribe']);
            $account->setCreatedAt(new \DateTime($record['created_at']));

            $roles = ['ROLE_ADMIN'];

            return $account;
        } else {
            throw new UsernameNotFoundException('Your username or password is invalid.');
        }
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
        if (!$user instanceof UserAccount) {
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
        return UserAccount::class === $class;
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
