<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLivrables($promo): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = 0')
            ->andWhere('e.promotion = :promo')
            ->setParameter('promo', $promo)
            ->getQuery()
            ->getResult();
    }

    public function findEntretiens($promo): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = 1')
            ->andWhere('e.promotion = :promo')
            ->setParameter('promo', $promo)
            ->getQuery()
            ->getResult();
    }

    public function findSoutenances($promo): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = 2')
            ->andWhere('e.promotion = :promo')
            ->setParameter('promo', $promo)
            ->getQuery()
            ->getResult();
    }
}
