<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    private array $categories = ['Français', 'Math', 'Programmation', 'Anglais'];
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach($this->categories as $category) {
            $cat = new Category();
            $cat->setName($category);
            $cat->setDescription($faker->text(200));
            $manager->persist($cat);
        }
        $manager->flush();
    }
}
