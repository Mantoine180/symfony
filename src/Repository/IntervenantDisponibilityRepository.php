<?php

namespace App\Repository;

use App\Entity\IntervenantDisponibility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IntervenantDisponibility>
 *
 * @method IntervenantDisponibility|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntervenantDisponibility|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntervenantDisponibility[]    findAll()
 * @method IntervenantDisponibility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantDisponibilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntervenantDisponibility::class);
    }

    public function save(IntervenantDisponibility $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IntervenantDisponibility $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    //public function getAllDisponibilitiesGroupByIntervenant()
    //{
    //    $queryBuilder = $this->createQueryBuilder('di')
    //        ->select('di.id', 'di.intervenant')
    //        ->groupBy('di.intervenant');
//
    //    dump($queryBuilder->getQuery()->getResult());
//
    //    return  $queryBuilder->getQuery()->getResult();
    //}

    //    /**
    //     * @return IntervenantDisponibility[] Returns an array of IntervenantDisponibility objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?IntervenantDisponibility
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
