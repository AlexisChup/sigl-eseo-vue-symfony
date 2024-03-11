<?php

namespace App\Repository;

use App\Entity\CentreFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CentreFormation>
 *
 * @method CentreFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CentreFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CentreFormation[]    findAll()
 * @method CentreFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentreFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CentreFormation::class);
    }

    public function add(CentreFormation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CentreFormation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
