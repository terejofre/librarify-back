<?php

namespace App\Manager;

use Sonata\AdminBundle\Util\AdminAclUserManagerInterface;
use Sonata\UserBundle\Model\UserManagerInterface;

class AclUserManager implements AdminAclUserManagerInterface
{

    public function __construct(
        private UserManagerInterface $userManager
    )
    {
    }

    public function findUsers(): iterable
    {
        return $this->userManager->findUsers();
    }
}