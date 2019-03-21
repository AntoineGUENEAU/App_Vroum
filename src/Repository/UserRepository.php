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
     * Find All Students
     * @return mixed
     */
    public function findByRoleStudent()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles NOT LIKE :roles')
            ->setParameter('roles', '%ROLE_MONITOR%');

        return $qb->getQuery()->getResult();
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
//
//    public function getStudentResults( User $user)
//    {
//        return $this->createQueryBuilder('u')
//            ->innerJoin('u.results', 'r')
//            ->where('u.id = :userId')
//            ->setParameter('userId', $user->getId())
//            ->addSelect('r.result')
//            ->RIGHTJOIN('r.serie_id', 's')
//            ->addSelect('s.libelle')
//            ->getQuery()
//            ->getSQL();
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
    }




}
