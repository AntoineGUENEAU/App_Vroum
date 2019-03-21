<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function getStudentSeriesWithResultsMonitor( User $user)
    {

        $query = $this->createQueryBuilder('u')
            ->innerJoin('u.results', 'r')
            ->where('u.id = :userId')
            ->addSelect('r.result')
            ->RIGHTJOIN('r.serie_id', 's')
            ->addSelect('s.libelle')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getDQL();
        dump($query);
        die();

//        return $query;
    }



//    public function getStudentSeriesWithResults2( User $user) {
//
//        $query = $this->createQueryBuilder('r.result')
//            ->leftJoin('r.serie_id', 's')
//            ->addSelect('s.libelle')
//            ->innerJoin('r.user_id', 'u')
//            ->addSelect('r.result')
//            ->where('u.id = :userId')
//            ->setParameter('userId', $user->getId())
//            ->getQuery()
//            ->getResult();
//        dump($query);
//        die();

//            return $query;

//    }

    public function getStudentSeriesWithResultsJson( $id)
    {
        $userId = $this->find($id);

        $query = $this->createQueryBuilder('u')
            ->innerJoin('u.results', 'r')
            ->addSelect('r.result')
            ->leftJoin('r.serie_id', 's')
            ->where('u.id = :userId')
            ->addSelect('s.libelle')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSQL();
        dump($query);
        die();

//        return $query;
    }




}
