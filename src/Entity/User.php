<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sonata\UserBundle\Entity\BaseUser;

class User extends BaseUser
{
    protected $id;

    public function __construct(
        private ?Collection $comments = new ArrayCollection()
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = 'ROLE_SUPER_ADMIN';

        return $roles;
    }
}
