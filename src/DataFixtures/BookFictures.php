<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class BookFictures extends AbstractFixture
{

    public function load(ObjectManager $manager)
    {
        $book = Book::create(
            'Un libro',
            $this->getReference('user'),
            null,
            'descripcion',
            null,
            null,
            [$this->getReference('author')],
            [$this->getReference('category')],
            []
        );

        $manager->persist($book);
        $manager->flush();
    }
}