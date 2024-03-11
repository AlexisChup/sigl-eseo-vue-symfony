<?php

namespace App\Repository;

use App\Entity\Apprenti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Apprenti>
 *
 * @method Apprenti|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apprenti|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apprenti[]    findAll()
 * @method Apprenti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprentiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apprenti::class);
    }

    public function add(Apprenti $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Apprenti $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUtilisateur(int $id)
    {

        $query = $this->createQueryBuilder('app')
            ->where('app.utilisateur = :user_id')
            ->setParameter('user_id', $id)
            ->getQuery();

        return $query->getResult();
    }

    public function getApprentisMA(int $idMA)
    {

        $query = $this->createQueryBuilder('app')
            ->where('app.maitreApprentissage = :maitre_apprentissage_id')
            ->setParameter('maitre_apprentissage_id', $idMA)
            ->getQuery();

        return $query->execute();
    }

    public function removeById($id): void
    {
        $entityApp = $this->getEntityManager()->find(Apprenti::class, $id);
        $this->getEntityManager()->remove($entityApp);
        $this->getEntityManager()->flush();
    }
}
