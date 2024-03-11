<?php

namespace App\Repository;

use App\Entity\MaitreApprentissage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaitreApprentissage>
 *
 * @method MaitreApprentissage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaitreApprentissage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaitreApprentissage[]    findAll()
 * @method MaitreApprentissage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaitreApprentissageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaitreApprentissage::class);
    }

    public function add(MaitreApprentissage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MaitreApprentissage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUtilisateur(int $id)
    {

        $query = $this->createQueryBuilder('ma')
            ->where('ma.utilisateur = :user_id')
            ->setParameter('user_id', $id)
            ->getQuery();

        return $query->execute();
    }

    public function removeById($id): void
    {
        $entityMa = $this->getEntityManager()->find(MaitreApprentissage::class, $id);
        $this->getEntityManager()->remove($entityMa);
        $this->getEntityManager()->flush();
    }
}
