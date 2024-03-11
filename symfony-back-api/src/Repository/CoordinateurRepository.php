<?php

namespace App\Repository;

use App\Entity\Coordinateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coordinateur>
 *
 * @method Coordinateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coordinateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coordinateur[]    findAll()
 * @method Coordinateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoordinateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coordinateur::class);
    }

    public function add(Coordinateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Coordinateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUtilisateur(int $id)
    {
        $query = $this->createQueryBuilder('coord')
            ->where('coord.utilisateur = :user_id')
            ->setParameter('user_id', $id)
            ->getQuery();

        return $query->execute();
    }
}
