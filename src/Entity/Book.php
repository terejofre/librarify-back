<?php

namespace App\Entity;

use App\Entity\Book\Score;
use App\Event\Book\BookCreatedEvent;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

class Book
{
    private array $domainEvents = [];
    private DateTimeInterface $createdAt;

    /**
     * @param Collection|Author[]|null $authors
     * @param Collection|Category[] |null $categories
     */
    public function __construct(
        private UuidInterface $id,
        private string $title,
        private User $user,
        private ?string $image = null,
        private ?string $description = null,
        private Score $score = new Score(),
        private ?DateTimeInterface $readAt = null,
        private ?Collection $authors = new ArrayCollection(),
        private ?Collection $categories = new ArrayCollection(),
        private ?Collection $comments = new ArrayCollection()
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @param array|Author[] $authors
     * @param array|Category[] $categories
     * @return self
     */
    public static function create(
        string $title,
        User $user,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        array $authors,
        array $categories,
        array $comments
    ): self {
        $book = new self(
            Uuid::uuid4(),
            $title,
            $user,
            $image,
            $description,
            $score ?? new Score(),
            $readAt,
            new ArrayCollection($authors),
            new ArrayCollection($categories),
            new ArrayCollection($comments)
        );
        $book->addDomainEvent(new BookCreatedEvent($book->getId()));
        return $book;
    }

    public function addDomainEvent(Event $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function pullDomainEvents(): array
    {
        return $this->domainEvents;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return array_values($this->comments->toArray());
    }

    public function addComment(Comment $comment): self
    {
        $this->comments[] = $comment;
        return $this;
    }

    public function updateComments(Comment ...$newComments)
    {
        foreach ($newComments as $newComment) {
            $this->addComment($newComment);
        }
    }

    public function removeComment(Comment $comment): self
    {
        $this->comments->removeElement($comment);
        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return array_values($this->categories->toArray());
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function updateCategories(Category ...$newCategories)
    {
        /** @var ArrayCollection<Category> */
        $originalCategories = new ArrayCollection();
        foreach ($this->categories as $category) {
            $originalCategories->add($category);
        }

        // Remove categories
        foreach ($originalCategories as $originalCategory) {
            if (!\in_array($originalCategory, $newCategories, true)) {
                $this->removeCategory($originalCategory);
            }
        }

        // Add categories
        foreach ($newCategories as $newCategory) {
            if (!$originalCategories->contains($newCategory)) {
                $this->addCategory($newCategory);
            }
        }
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

        return $this;
    }

    public function updateAuthors(Author ...$authors)
    {
        /** @var Author[]|ArrayCollection */
        $originalAuthors = new ArrayCollection();
        foreach ($this->authors as $author) {
            $originalAuthors->add($author);
        }

        // Remove authors
        foreach ($originalAuthors as $originalAuthor) {
            if (!\in_array($originalAuthor, $authors)) {
                $this->removeAuthor($originalAuthor);
            }
        }

        // Add authors
        foreach ($authors as $newAuthor) {
            if (!$originalAuthors->contains($newAuthor)) {
                $this->addAuthor($newAuthor);
            }
        }
    }

    /**
     * @param array|Author[] $authors
     * @param array|Category[] $categories
     * @return void
     */
    public function update(
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        array $authors,
        array $categories,
        array $comments
    ) {
        $this->title = $title;
        if ($image !== null) {
            $this->image = $image;
        }
        $this->description = $description;
        $this->score = $score;
        $this->readAt = $readAt;
        $this->updateCategories(...$categories);
        $this->updateAuthors(...$authors);
        $this->updateComments(...$comments);
    }

    public function patch(array $data): self
    {
        if (\array_key_exists('score', $data)) {
            $this->score = Score::create($data['score']);
        }
        if (\array_key_exists('title', $data)) {
            $title = $data['title'];
            if ($title === null) {
                throw new DomainException('Title cannot be null');
            }
            $this->title = $title;
        }

        if (\array_key_exists('comment', $data)) {
            $comment = $data['comment'];
            if ($comment === null) {
                throw new DomainException('Comment cannot be null');
            }
            $commentEntity = Comment::create($comment, $this);

        }

        return $this;
    }

    public function setScore(Score $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getScore(): Score
    {
        return $this->score;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function isRead(): ?bool
    {
        return $this->readAt === null ? false : true;
    }

    public function markAsRead(DateTimeInterface $readAt): self
    {
        $this->readAt = $readAt;
        return $this;
    }

    public function getReadAt(): ?DateTimeInterface
    {
        return $this->readAt;
    }

    /**
     * @param DateTimeInterface|null $readAt
     */
    public function setReadAt(?DateTimeInterface $readAt): void
    {
        $this->readAt = $readAt;
    }

    public function getReadAtAsString(): ?string
    {
        if ($this->readAt === null) {
            return null;
        }
        return $this->readAt->format('Y-m-d');
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param Comment|null $comment
     */

    public function __toString()
    {
        return $this->title ?? 'Libro';
    }
}
