<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\ResponseQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class ResponsesFactory extends Fixture
{


    public function load(ObjectManager $manager)
    {
//        for($i = 0 ; $i < 1600 ; $i++)
//        {
//            for($j = 0 ; $j < 4 ; $j++)
//            {
//                $question = new ResponseQuestion();
//                $question->setContent('ResponseContent'.$i);
//                $question->setGoodAnswer(random_int(0,1));
//                $question->setQuestion($manager->getRepository(Question::class)->findOneById($j));
//                $manager->persist($question);
//            }
//        }
//        $manager->flush();
    }

}
