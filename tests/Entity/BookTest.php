<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use Monolog\Test\TestCase;

/**
 * @group BookTest
 */
class BookTest extends TestCase
{
    private User $userMock;
    private Author $authorMock;

    private Category $categoryMock;

    private Comment $commentMock;

    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userMock = $this->createMock(User::class);
        $this->authorMock = $this->createMock(Author::class);
        $this->categoryMock = $this->createMock(Category::class);
        $this->commentMock = $this->createMock(Comment::class);
        $this->book = Book::create(
            'los pilares de la tierra',
            $this->userMock,
            null,
            'Descripcion',
            null,
            null,
            [$this->authorMock],
            [$this->categoryMock],
            [$this->commentMock]
        );
    }

    public function testBookSuccessfullyCreated()
    {
        $this->assertCount(1, $this->book->getComments());
        $this->assertNotEmpty($this->book->getComments());
        $this->assertFalse(count($this->book->getComments()) == 2);
    }

    public function testBookAddNewCommentSuccessfully()
    {
        $newComment = Comment::create(
            'Un libro hermoso',
            $this->book,
            $this->userMock);

        $this->book->addComment($newComment);
        $this->assertCount(2, $this->book->getComments());
    }

    public function testRemoveCommentFromBook()
    {
        $this->book->removeComment($this->commentMock);
        $this->assertCount(0, $this->book->getComments());
    }
}