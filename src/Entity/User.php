<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sonata\UserBundle\Entity\BaseUser;

class User extends BaseUser
{
    public const ROLE_DEFAULT = 'ROLE_MEMBER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    protected $id;

    public function __construct(
        private ?Collection $comments = new ArrayCollection()
    ) {
        $this->createdAt = new DateTimeImmutable();
    }
}
