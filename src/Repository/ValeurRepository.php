<?php

namespace App\Repository;

use App\Entity\Valeur;
use App\Service\FilterService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use function MongoDB\BSON\toJSON;

/**
 * @method Valeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Valeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Valeur[]    findAll()
 * @method Valeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValeurRepository extends ServiceEntityRepository
{
    private $filterService;
    public function __construct(ManagerRegistry $registry,FilterService $filterService)
    {
        $this->filterService = $filterService;
        parent::__construct($registry, Valeur::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Valeur $entity, bool $flush = true): void
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
    public function remove(Valeur $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Valeur[] Returns an array of Valeur objects
    // projects version draft personne
    //  */

    public function findByOrderCol($valeur)
    {
        return $this->createQueryBuilder('v')
            ->select('v')
            ->innerJoin('v.col','col')
            ->where('col.ordre = :val')
            ->setParameter('val', $valeur)
            ->distinct(true)
            ->groupBy('v.val')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($Valeur): ?Valeur
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $valeur)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findProjectByBureau($bureau)
    {
        //chercher les ids des Entry
        $entryIdsQuery = $this->createQueryBuilder('v')
                        ->select('entry.id')
                        ->innerJoin('v.col','col')
                        ->innerJoin('v.entry','entry')
                        ->distinct(true)
                        ->andWhere('v.val = :bureau')
                        ->andWhere('col.headerName = :name')
                        ->andWhere('col.ordre = 0')->getQuery();

        $resultats = $entryIdsQuery->execute(array('bureau'=>$bureau,'name'=>'bureau'));
        $ids =array_column($resultats,'id');
        // chercher les projets  qui correspondent au entry
        $projectQuery = $this->createQueryBuilder('v')
            ->select(' col.headerName as headerName ,v.val as val,entry.id as ligne')
            ->innerJoin('v.col','col')
            ->innerJoin('v.entry','entry')
            ->ANDwhere("entry.id IN(:ids)")
            ->setParameter('ids', $ids)
            ->groupBy('v.entry,v.col')
            ->getQuery();

        $tab=$projectQuery->getArrayResult();
        $resultats = $this->filterService->redesign($tab);
        //var_dump($resultats);
        return $resultats;
    }
    public function findProjectBy($pjcts,$name)
    {
        $expr = $this->_em->getExpressionBuilder();
        $query = $this->createQueryBuilder('vl')
            ->select('entry.id,column.headerName,vl.val')
            ->innerJoin('vl.col','column')
            ->innerJoin('vl.entry','entry')->distinct(true)
            ->andwhere(
                $expr->in(
                    'entry.id',
                    $this->createQueryBuilder('v')
                        ->select('entry')
                        ->innerJoin('v.col','col')
                        ->innerJoin('v.entry','en')
                        ->innerJoin('en.file','file')
                        ->andwhere('v.val IN (:pjcts)')
                        ->andWhere('col.headerName = :name')
                        ->andWhere('file.draft = true')->distinct(true)
                        ->getDQL()
                )
            )->getQuery();
        $resultats = $query->execute(array('pjcts'=>$pjcts,'name'=>$name));
        return $resultats;
    }

}
