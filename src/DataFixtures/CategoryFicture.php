<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFicture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $category = Category::create(
            'categoria 1',
            $this->getReference('user')
        );

        $manager->persist($manager);
        $manager->flush();

        $this->addReference('category', $category);
    }
}