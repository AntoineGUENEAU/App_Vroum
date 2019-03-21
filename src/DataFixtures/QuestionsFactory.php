<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionsFactory extends Fixture
{

    public function load(ObjectManager $manager)
    {
//        for($i = 0 ; $i < 40 ; $i++)
//        {
//            for($j = 0 ; $j < 40 ; $j++)
//            {
//                $question = new Question();
//                $question->setContent( 'QuestionContent'.$i);
//                $question->setImage( 'pathToImage'.$i);
//                $question->setSerie($manager->getRepository(Serie::class)->findOneById($j));
//                $manager->persist($question);
//            }
//        }
//        $manager->flush();
    }
}
