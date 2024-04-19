<?php

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Comment
{
    private \DateTimeInterface $createdAt;

    public function __construct(
        private UuidInterface $id,
        private string $comment,
        private ?User $user = null,
        private ?Book $book = null
    )
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function create(string $comment, ?User $user = null, ?Book $book = null): self
    {
        return new self(Uuid::uuid4(), $comment, $user, $book);
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook(Book $book): void
    {
        $this->book = $book;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function __toString(): string
    {
        return $this->comment ?? 'Comentario';
    }
}
