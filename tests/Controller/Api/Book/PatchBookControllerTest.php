<?php

namespace App\Tests\Controller\Api\Book;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Service\Book\GetBook;
use App\Service\Utils\Security;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PatchBookControllerTest extends WebTestCase
{
    private $requestClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requestClient = static::createClient();
        $container = $this->requestClient->getContainer();

        $user = $this->createMock(User::class);
        $book = $this->createMock(Book::class);
        $book->expects($this->any())
            ->method('getUser')
            ->willReturn($user);

        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->any())
            ->method('find')
            ->with('dca07735-2dae-4a9f-b72e-5c22e83f1fc5')
            ->willReturn($book);

        $securityServiceMock = $this->createMock(Security::class);
        $securityServiceMock->expects($this->once())
            ->method('getCurrentUser')
            ->willReturn($user);

        $container->set(BookRepository::class, $bookRepository);
        $container->set(Security::class, $securityServiceMock);
    }

    /**
     *
     * Error en el test:
     * filemtime(): stat failed for /var/www/librarify/vendor/phpunit/phpunit/src/Framework/MockObject/MockClass.php(51) : eval()'d code
     *
     * @return void
     */
    public function testUpdateBookWithComment()
    {
        $commentText = 'Lorem ipsum';

        $this->requestClient->request(
            'PATCH',
            '/api/books/dca07735-2dae-4a9f-b72e-5c22e83f1fc5',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode([
                'comment' => $commentText
            ])
        );
        $response = $this->requestClient->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}