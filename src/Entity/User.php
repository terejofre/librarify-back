<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private readonly int $id;
    private array $roles = [];
    private string $password;

    private \DateTimeInterface $createdAt;

    public function __construct(
        private string       $email,
        private ?bool $enabled = true,
        private ?Collection  $comments = new ArrayCollection()
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        if (isset($this->id)) {
            return $this->id;
        }

        return null;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUserIdentifier()
    {
        return $this->getEmail();
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEnabled()
    {
        return true;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->email ?? 'User';
    }
}
