<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function add(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeById($id): void
    {
         $entityUser = $this->getEntityManager()->find(Utilisateur::class, $id);
         $this->getEntityManager()->remove($entityUser);
         $this->getEntityManager()->flush();
    }

    public function getUtilisateurRights(AdminRepository $adminRepository): array
    {
        $utilisateurRights = array();

        $admins = $adminRepository->findAll();

        foreach ($admins as $admin) {
            if ($admin->getUtilisateur()->getId() == $this->getId()) {
                $utilisateurRights[] = 'admin';
            }
        }

        return $utilisateurRights;
    }

    /**
     * @param $email
     * @param $password
     * @return array if credentials are correct
     */
    public function connexion($email, $password): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->andWhere('u.motDePasse = :password')
            ->setParameter('email', $email)
            ->setParameter('password', $password)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function getUtilisateurApprentis()
    {
        $query = $this->createQueryBuilder('u')
            ->join("App\Entity\Apprenti", "a", "WITH", "a.utilisateur = u.id")
            ->getQuery();

        return $query->execute();
    }

    public function getUtilisateurEnseignants()
    {
        $query = $this->createQueryBuilder('u')
            ->join("App\Entity\Enseignant", "e", "WITH", "e.utilisateur = u.id")
            ->getQuery();

        return $query->execute();
    }

    public function updateUtilisateur(Utilisateur $entity): Utilisateur
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}
