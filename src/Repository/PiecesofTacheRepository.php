<?php

namespace App\Repository;

use App\Entity\PiecesofTache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PiecesofTache|null find($id, $lockMode = null, $lockVersion = null)
 * @method PiecesofTache|null findOneBy(array $criteria, array $orderBy = null)
 * @method PiecesofTache[]    findAll()
 * @method PiecesofTache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PiecesofTacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PiecesofTache::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PiecesofTache $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PiecesofTache $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PiecesofTache[] Returns an array of PiecesofTache objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PiecesofTache
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
