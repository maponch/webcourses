<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;


class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $courses = $manager->getRepository(Course::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        for($i = 1;$i <= 25;$i++) {
            $com = new Comment();
            $com->setContent($faker->paragraph(4))
                ->setDate(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', 'now')))
                ->setRate($faker->biasedNumberBetween(1,5))
                ->setUser($users[array_rand($users)])
                ->setCourse($courses[array_rand($courses)]);

            $manager->persist($com);
        }

        $manager->flush();
    }
    public function getDependencies():array
    {
        return [
            UserFixtures::class,
            CourseFixtures::class,
        ];
    }
}
