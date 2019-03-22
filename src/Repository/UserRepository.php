<?php

namespace App\Repository;

use App\Entity\Result;
use App\Entity\Serie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
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

    public function getResultsByStudent($id) {

        $user = $this->find($id);
        $userMail = $user->getEmail();
        $entityManager = $this->getEntityManager();
        $rsm = new ResultSetMapping();
// build rsm here

        $query = $entityManager->createNativeQuery('SELECT
    serie.*,
   CASE
       WHEN myResult.result is null THEN -1
       ELSE myResult.result
    END
FROM
    serie left outer join
   (select result.result, result.serie_id_id from result inner join user on result.user_id = user.id 
        where user.email = \'user.Mail\') AS myResult   
        on serie.id = myResult.serie_id_id ORDER by serie.id', $rsm);
        $query->setParameter('userMail', $userMail);

        return $query->getResult();
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
//            ->addSelect('r.result')
//            ->RIGHTJOIN('r.serie_id', 's')
//            ->addSelect('s.libelle')
//            ->getQuery()
//            ->getResult();
//        dump($query);
//        die();

//            return $query;

//    }
//
//    public function getStudentSeriesWithResultsJson( $id)
//    {
//
//        $userId = $this->find($id);
//
//        $qb1 = $this->_em->createQueryBuilder();
//        $qb1->select('r')
//            ->from(Result::class,'r')
//            ->leftJoin(Serie::class,'r', 's')
//            ->addSelect('s.libelle')
//            ->from(User::class,'u')
//            ->innerJoin('u.results','r')
//            ->where('u.id = :userId')
//            ->setParameter('userId', $userId);
//
//
//        dump($qb->getQuery()->getSQL());
//        die();
//
////        return $query;
//    }


    public function getStudentSeriesWithResultsJson($id)
    {

        $userId = $this->find($id);

        $qb1 = $this->createQueryBuilder('u')
            ->innerJoin('u.results', 'r')
            ->innerJoin('r.serie', 's')
            ->addSelect('s.libelle')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);


        dump($qb1->getQuery()->getSQL());
        die();

//        return $query;
    }




}
