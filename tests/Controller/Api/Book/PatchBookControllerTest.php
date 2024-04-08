<?php

namespace App\Tests\Controller\Api\Book;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\Book\GetBook;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PatchBookControllerTest extends WebTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $bookRepository = $this->getMockBuilder(BookRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bookRepository->expects($this->any())
            ->method('find');

        $container = self::getContainer();
        $container->set(BookRepository::class, $bookRepository);

    }

    public function testUpdateBookWithComment()
    {
        $commentText = 'Lorem ipsum';
        $client = static::createClient();
        $client->request(
            'PATCH',
            '/api/books/' . Uuid::uuid4(),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode([
                'comment' => $commentText
            ])
        );
        $response = $client->getResponse();
        var_dump(json_decode($response->getContent())->message);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}