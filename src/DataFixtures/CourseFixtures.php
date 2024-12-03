<?php

namespace App\DataFixtures;

use App\Entity\Level;
use App\Entity\Category;
use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Cocur\Slugify\Slugify;

class CourseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $slugify = new Slugify();
        $categories = $manager->getRepository(Category::class)->findAll();
        $levels = $manager->getRepository(Level::class)->findAll();
        for ($i = 1; $i <= 25; $i++) {
            $course = new Course();
            $course->setName($faker->sentence(4,true))
                ->setDescription($faker->paragraph())
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', 'now')))
                ->setPrice($faker->biasedNumberBetween())
                ->setProgram($faker->paragraph(2, false))
                ->setDuration($faker->biasedNumberBetween(10,250))
                ->setCategory($categories[array_rand($categories)])
                ->setLevel($levels[array_rand($levels)])
                ->setSlug($slugify->slugify($course->getName()));
            $manager->persist($course);
        }
        $manager->flush();
    }
    public function getDependencies():array
    {
        return [
            CategoryFixtures::class,
            LevelFixtures::class,
        ];
    }
}
