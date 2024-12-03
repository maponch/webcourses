<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 1; $i <= 10; $i++) {
            $new = new News();
            $new->setContent($faker->text(400))
                ->setTitle($faker->paragraph(1,true))
                ->setImage($faker->image);
            $manager->persist($new);
        }

            $manager->flush();
    }
}
