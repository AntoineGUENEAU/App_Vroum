<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        (new UsersFactory)->load($manager);
        (new SeriesFactory)->load($manager);
        (new QuestionsFactory)->load($manager);
        (new ResponsesFactory)->load($manager);
    }
}
