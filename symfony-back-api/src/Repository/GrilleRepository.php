<?php

namespace App\Repository;

use App\Entity\Grille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grille>
 *
 * @method Grille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grille[]    findAll()
 * @method Grille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grille::class);
    }

    public function add(Grille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Grille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
