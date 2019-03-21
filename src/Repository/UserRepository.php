<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $id
     *  Retourne le nombre  de série faite par l'utilisateur
     *
     * @return mixed
     */
    public function getSeriesCount($id)
    {
        $user = $this->find($id);
        $datas = $this->createQueryBuilder('e')
            ->innerJoin('e.results', 'r')
            ->addSelect('count(r.id)')
            ->leftJoin('r.serie_id', 's')
            ->where('e.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();

        return $datas['0']['1'];
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
//    public function findByExampleField($value)
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


//    public function findOneBySomeField($value): ?User
//    {
//        try {
//            return $this->createQueryBuilder('u')
//                ->andWhere('u.exampleField = :val')
//                ->setParameter('val', $value)
//                ->getQuery()
//                ->getOneOrNullResult();
//        } catch (NonUniqueResultException $e) {
//        }
//    }
}
