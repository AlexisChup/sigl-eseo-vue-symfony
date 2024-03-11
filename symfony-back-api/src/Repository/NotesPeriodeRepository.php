<?php

namespace App\Repository;

use App\Entity\NotesPeriode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotesPeriode>
 *
 * @method NotesPeriode|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotesPeriode|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotesPeriode[]    findAll()
 * @method NotesPeriode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotesPeriodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotesPeriode::class);
    }

    public function add(NotesPeriode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NotesPeriode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNotesFromApp(int $appId)
    {

        $query = $this->createQueryBuilder('note')
            ->where('note.apprenti = :user_id')
            ->setParameter('user_id', $appId)
            ->orderBy('note.date', 'DESC')
            ->getQuery();

        return $query->execute();
    }
}
