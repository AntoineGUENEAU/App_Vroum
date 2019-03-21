<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SeriesFactory extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0 ; $i < 40 ; $i++)
        {
            $serie = new Serie();
            $serie->setLibelle("libelle n°".$i);
            $manager->persist($serie);
        }
        $manager->flush();
    }
}
