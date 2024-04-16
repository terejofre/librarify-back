<?php

namespace App\Form\Model;

use App\Entity\Book;
use DateTimeInterface;

class BookDto
{
    public ?string $title = null;
    public ?string $description = null;
    public ?int $score = null;
    public ?string $base64Image = null;
    /** @var \App\Event\Form\Model\CategoryDto[]|null */
    public ?array $categories = [];
    /** @var \App\Event\Form\Model\AuthorDto[]|null */
    public ?array $authors = [];
    public ?array $comments = [];
    public ?DateTimeInterface $readAt = null;

    public function __construct()
    {
        $this->categories = [];
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromBook(Book $book): self
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        $dto->score = $book->getScore()->getValue();
        $dto->description = $book->getDescription();
        $dto->readAt = $book->getReadAt();
        return $dto;
    }
}
