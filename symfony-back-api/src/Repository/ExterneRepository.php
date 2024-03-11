<?php

namespace App\Repository;

use App\Entity\Externe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Externe>
 *
 * @method Externe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Externe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Externe[]    findAll()
 * @method Externe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExterneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Externe::class);
    }

    public function save(Externe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Externe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
