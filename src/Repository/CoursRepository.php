<?php

namespace App\Repository;

use App\Entity\Cours;
use App\Entity\Matiere;
use App\Entity\Intervenant;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Cours>
 *
 * @method Cours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cours[]    findAll()
 * @method Cours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }

    public function save(Cours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getMinutesSumGroupedByMatieres(): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('SUM(timestamp_diff(MINUTE, c.dateHeureCour_debut, c.dateHeureCour_fin)) AS minutes')
           ->addSelect('m.id')
           ->join('c.id_matiere', 'm')
           ->groupBy('m.id');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMinutesConsumedByMatiere(Matiere $matiere)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('SUM(timestamp_diff(MINUTE, c.dateHeureCour_debut, c.dateHeureCour_fin)) AS minutes')
           ->addSelect('m.id')
           ->join('c.id_matiere', 'm')
           ->where('m.id = :matiere')
           ->setParameter(':matiere', $matiere)
           ->groupBy('m.id');
        
        return $queryBuilder->getQuery()->getOneOrNullResult() ?: ['minutes' => 0];
    }

    public function getCoursByIntervenants(Intervenant $intervenant)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c')
           ->join('c.id_matiere', 'm')
           ->join('m.intervenant', 'i')
           ->where('i.id_intervenant = :intervenant')
           ->setParameter(':intervenant', $intervenant);
        
        return $queryBuilder->getQuery()->getResult();
    }
}
