<?php

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LevelFixtures extends Fixture
{
    private array $name = [
        'Debutant'=> 'Idéal pour découvrir la matières',
        'Intermédiaire' => 'Convient aux personnes ayant déjà des notions ou celles qui aiment le challenge',
        'Avancé' => 'Recommander de connaître le sujet du cours',
        'Expert' => 'Approfondir les connaissances, se référer aux programmes du cours'];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->name as $n => $desc){
            $level = new Level();
            $level->setName($n)
                ->setPrerequisite($desc);
            $manager->persist($level);
        }
        $manager->flush();
    }
}
