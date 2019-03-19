<?php

namespace App\Repository;

use App\Entity\ResponseQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ResponseQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResponseQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResponseQuestion[]    findAll()
 * @method ResponseQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponseQuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ResponseQuestion::class);
    }

    // /**
    //  * @return ResponseQuestion[] Returns an array of ResponseQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResponseQuestion
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
