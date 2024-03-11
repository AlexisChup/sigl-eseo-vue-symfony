<?php


namespace App\classes;

use App\Entity\Admin;
use App\Entity\Apprenti;
use App\Entity\Coordinateur;
use App\Entity\Enseignant;
use App\Entity\MaitreApprentissage;
use App\Entity\Utilisateur;
use Doctrine\Persistence\ManagerRegistry;

class PermissionUtil
{

    public function __construct()
    {

    }

    public function getUtilisateurRightString(int $id, ManagerRegistry $doctrine): array
    {
        $adminRepo = $doctrine->getRepository(Admin::class);
        $appRepo = $doctrine->getRepository(Apprenti::class);
        $coordRepo = $doctrine->getRepository(Coordinateur::class);
        $ensRepo = $doctrine->getRepository(Enseignant::class);
        $maRepo = $doctrine->getRepository(MaitreApprentissage::class);

        $perm = array();

        if (!empty($adminRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'ADMIN');
        } elseif (!empty($appRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'APPRENTI');
        } elseif (!empty($coordRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'COORDINATEUR');
        } elseif (!empty($ensRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'ENSEIGNANT');
        } elseif (!empty($maRepo->getUtilisateur($id))) {
            $perm = array('perm' => 'MAITRE-APPRENTISSAGE');
        } else {
            $perm = array('perm' => 'UNIDENTIFIED');
        }

        return $perm;
    }
}
