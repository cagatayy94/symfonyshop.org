<?php

namespace App\Sdk;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

use App\Entity\Admin\AdminAccount;

trait AdminServiceTrait
{
    /**
     * @var security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * Authorizes the user
     *
     * @param string $operation Permission to authorize the user for
     *
     * @throws AccessDeniedException
     */
    public function authorize($operation)
    {
        $user = $this->security->getUser();

        if (null == $user || !$user instanceof AdminAccount) {
            throw new AccessDeniedException('Bu işlemi gerçekleştirmek için sisteme giriş yapmalısınız');
        }

        if (!$user->hasRole($operation)) {
            //$this->logger->error('Admin not authorized', 'admin', $admin->getId(), 'authorize', ['operation' => $operation]);
            //throw new AccessDeniedException('Bu işlemi gerçekleştirmek için gerekli yetkiye sahip değilsiniz');
            throw new AccessDeniedException('Bu işlemi gerçekleştirmek için gerekli yetkiye sahip değilsiniz (' . $operation . ')');
        }
    }
}