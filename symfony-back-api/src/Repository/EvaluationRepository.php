<?php

namespace App\Repository;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evaluation>
 *
 * @method Evaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluation[]    findAll()
 * @method Evaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluation::class);
    }

    public function add(Evaluation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evaluation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getDataLivrables(int $id) {
        $query = $this->createQueryBuilder('e')
            ->join("App\Entity\Apprenti ", "app", "WITH", "e.idApprenti = app.id")
            ->join("App\Entity\User ", "u", "WITH", "app.idUser = u.id")
            ->join("App\Entity\Event ", "e2", "WITH", "e.idEvent = e2.id")
            ->join("App\Entity\Livrable ", "l", "WITH", "e2.id = l.event")
            ->where('u.id = :u_id')
            ->setParameter('u_id', $id)
            ->getQuery();
        return $query->execute();
    }
}
