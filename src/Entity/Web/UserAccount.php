<?php

namespace App\Entity\Web;

use Symfony\Component\Security\Core\User\UserInterface;

class UserAccount implements UserInterface
{

    /**
     * @var int Identifier
     */
    protected $id;

    /**
     * @var string The hashed password
     */
    private $password;

    /**
     * @var string The email of user
     */
    private $email;

    /**
     * @var string The name of user
     */
    private $name;

    /**
     * @var bool The status of user is deleted
     */
    private $isDeleted;

    /**
     * @var string The mobile of user
     */
    private $mobile;

    /**
     * @var bool The status of user mobile approved
     */
    private $isMobileApproved;

    /**
     * @var bool The status of user email approved
     */
    private $isEmailApproved;

    /**
     * @var string The activation code of user
     */
    private $activationCode;

    /**
     * @var bool The status of user is unsubscribe for mails
     */
    private $isUnsubscribe;

    /**
     * @var \DateTime Account creation date/time
     */
    protected $createdAt;

    /**
     * @var array The roles of user
     */
    private $roles = [];

    /**
     * @param int $id Identifier
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Gets the identifier
     *
     * @return int Identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the identifier
     *
     * @param int $id Identifier
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password
     *
     * @param string $password encoded string
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Gets the email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email address
     *
     * @param string $email Email address
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Gets the username
     */
    public function getUsername(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the username 
     *
     * @param string $email Email address
     */
    public function setUsername($email)
    {
        $this->email = $email;
    }

    /**
     * Gets user name
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets user name
     *
     * @param string $name Name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the soft-delete flag
     *
     * @return bool True if the account is soft-deleted; false otherwise
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Sets the soft-delete flag
     *
     * @param bool $isDeleted True if the account is soft-deleted; false otherwise
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = (bool)$isDeleted;
    }


    /**
     * Gets the mobile number
     *
     * @return string Mobile number
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Sets the mobile number
     *
     * @param string $mobile Mobile number
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * Gets the account is mobile approved
     *
     * @return bool status
     */
    public function getIsMobileApproved()
    {
        return $this->isMobileApproved;
    }

    /**
     * Sets the mobile approved
     *
     * @param bool $isMobileApproved
     */
    public function setIsMobileApproved($isMobileApproved)
    {
        $this->isMobileApproved = $isMobileApproved;
    }

    /**
     * Gets the account is email approved
     *
     * @return bool isEmailApproved
     */
    public function getIsEmailApproved()
    {
        return $this->isEmailApproved;
    }

    /**
     * Sets the is email approved
     *
     * @param bool $isEmailApproved
     */
    public function setIsEmailApproved($isEmailApproved)
    {
        $this->isEmailApproved = $isEmailApproved;
    }

    /**
     * Gets the account email activation code
     *
     * @return string activationCode
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * Sets the activation code
     *
     * @param string $activationCode activation code
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }

    /**
     * Gets the account is Unsubscribe
     *
     * @return bool isUnsubscribe
     */
    public function getIsUnsubscribe()
    {
        return $this->isUnsubscribe;
    }

    /**
     * Sets the is unsubsribe 
     *
     * @param bool $isUnsubscribe
     */
    public function setIsUnsubscribe($isUnsubscribe)
    {
        $this->isUnsubscribe = $isUnsubscribe;
    }

    /**
     * Gets the account creation date/time
     *
     * @return \DateTime Account creation date/time
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the account creation date/time
     *
     * @param \DateTime $createdAt Account creation date/time
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Sets roles
     *
     * @param string[] $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
