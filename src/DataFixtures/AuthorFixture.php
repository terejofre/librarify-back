<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $author = Author::create(
            'Autor de libro',
            $this->getReference('user')
        );

        $manager->persist($author);
        $manager->flush();

        $this->addReference('author', $author);
    }
}