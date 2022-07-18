<?php

namespace App\Repository;

use App\Entity\Column;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Column|null find($id, $lockMode = null, $lockVersion = null)
 * @method Column|null findOneBy(array $criteria, array $orderBy = null)
 * @method Column[]    findAll()
 * @method Column[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColumnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Column::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Column $entity, bool $flush = true): void
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
    public function remove(Column $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Column[] Returns an array of Column objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneByOrder($value): ?Column
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ordre = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function getheaderName()
    {
        return $this->createQueryBuilder('c')
            ->select('c.headerName')
            ->getQuery()
            ->getResult();
    }
}
