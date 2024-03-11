<?php

namespace App\DataFixtures;

use App\Entity\Critere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CritereFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $categories[] = ['contenu' => 'Impression générale', 'criteres' => [
            'Le travail en cours de réalisation correspond-il à un travail d\'ingénieur⋅e débutant du champ de compétence de l\'ESEO ?',
            'L\'étudiant a-t-il fait la preuve de son application des concepts et vocabulaires métiers, ainsi que de leurs utilisations ?',
            'L\'étudiant a-t-il fait la preuve de son application des outils métiers ?'
        ]];
        $categories[] = ['contenu' => 'Contexte et problématique', 'criteres' => [
            'L\'étudiant⋅e sait exposer les éléments de contexte (problématique générale de l\'entreprise) qui justifient le projet dontil⋅elle à la charge ?',
            'L\'étudiant⋅e sait exposer la problématique spécifique à traiter dans son projet/PING ?',
        ]];
        $categories[] = ['contenu' => 'Buts et objectifs', 'criteres' => [
            'L\'étudiant⋅e a formalisé la/les solutions ou éléments de solution (les buts) attendus ?',
            'L\'étudiant⋅e a spécifié les buts sous forme d\'objectifs SMART ?',
            'L\'étudiant⋅e a défini les métriques pour savoir si les buts ont été atteints ou non en fin de projet ?',
            'L\'étudiant⋅e a formalisé les attentes de l\'ESEO en terme d\'objectifs et de livrables quant à la validation de ce projet ?',
        ]];
        $categories[] = ['contenu' => 'Choix technologiques et méthodologiques : Démarche de travail', 'criteres' => [
            'L\'apprenti⋅e a défini une démarche conceptuelle adaptée (modélisation/formalisation/...) pour la résolution de son problème ?',
            'L\'apprenti⋅e a identifié les solutions concurrentes/possibles/alternatives ?',
            'L\'apprenti⋅e a justifié a priori les choix de sa solution ?',
        ]];
        $categories[] = ['contenu' => 'Gestion de projet : Planning Initial , Planning effectif et prévisionnel actualisé à mi-parcours', 'criteres' => [
            'L\'apprenti⋅e a défini un lotissement (décomposition en activités/tâches) en cohérence avec les objectifs, la démarche de travail et avec une granularité adaptée ?',
            'L\'apprenti⋅e a proposé un agencement des activités prenant en compte les contraintes associées aux ressources humaines et matérielles impliquées dans le projet?',
            'L\'apprenti⋅e a défini un planning initial en cohérence avec son lotissement ?',
            'L\'apprenti⋅e a défini des jalons et des livrables intermédiaires ?',
            'L\'apprenti⋅e a formalisé la liste des livrables attendus, ainsi que leur échéancier ?',
            'L\'apprenti⋅e a utilisé son planning initial pour coordonner l\'ensemble des acteurs liés au projet, ainsi que ses actions ?',
            'L\'apprenti⋅e a quantifié l\'avancement de la réussite des objectifs initiaux ?',
            'L\'apprenti⋅e a analysé et expliqué les écarts entre le planning prévisionnel et le planning effectif ?',
            'L\'apprenti⋅e a reporté régulièrement (et sous quelle forme) son état d\'avancement ?',
        ]];
        $categories[] = ['contenu' => 'Mise en œuvre et résultats', 'criteres' => [
            'L\'apprenti⋅e sait présenter de manière synthétique les résultats auxquels il⋅elle est actuellement parvenu ?',
            'L\'apprenti⋅e a confronté ses résultats intermédiaires aux buts/objectifs attendus ?',
            'L\'apprenti⋅e a mis en place des moyens de valider/vérifier ses résultats intermédiaires ?',
            'L\'apprenti⋅e a justifié les points précédents sur un exemple représentatif du travail effectué ?',
            'Le travail technique réalisé correspond-il à un travail d\'ingénieur⋅e ?',
        ]];
        $categories[] = ['contenu' => 'Compétences comportementales en situation professionnelle', 'criteres' => [
            'L\'étudiant a-t-il fait preuve d\'un bon état d\'esprit en accord avec les valeurs de l\'entreprise ?',
            'L\'étudiant est-il respectueux des procédures internes (tenue vestimentaire, horaire ...) ?',
            'L\'étudiant a-t-il le sens du collectif, esprit d\'équipe effectif et/ou pluridisciplinaire ?',
            'L\'étudiant est-il autonome, une fois formé aux usages de l\'entreprise et opérationnel au cœur de sa mission ?',
            'L\'étudiant est-il proactif (anticipation et proposition de solutions) ? Prend-il des initiatives ?',
            'L\'étudiant a-t-il fait preuve de persévérance et de détermination ?',
            'L\'étudiant fait-il preuve de rigueur dans son travail ?',
            'L\'étudiant fait-il preuve de respect vis à vis de ses interlocuteurs (collègue, hiérarchie, client...) ?',
            'L\'étudiant est-il capable de communiquer avec des interlocuteurs de différents profils ?',
            'L\'étudiant a-t-il une bonne connaissance du fonctionnement du site de l\'entreprise où il travaille, des produits/services fournis, ainsi que des processus internes ?',
            'L\'étudiant est-il capable d\'organiser une réunion ?',
            'L\'étudiant a-t-il sollicité à bon escient ses collègues de travail ?',
        ]];
        $categories[] = ['contenu' => 'Qualité de la soutenance', 'criteres' => [
            'L\'étudiant a-t-il respecté le temps imparti de présentation ?',
            'L\'étudiant est-il dynamique durant la présentation ?',
            'Les bonnes pratiques sont-elles respectées (Les pages du support de présentation sont numérotées. Le texte et les images sont lisibles pour l\'audience. Les transparents ne sont pas surchargés) ?',
            'Le support de présentation illustre bien le discours de l\'étudiant ?',
            'Les éventuels copyrights des textes, tableaux, figures ou image sont respectés ?',
            'L\'étudiant comprend les questions du jury et ses réponses sont-elles pertinentes ?',
        ]];

        $index = 0;
        foreach ($categories as $categorie) {
            $critere = new Critere();
            $critere->setGrille($this->getReference('grille9'));
            $critere->setContenu($categorie['contenu']);
            $manager->persist($critere);

            $this->addReference('critere' . $index, $critere);

            foreach ($categorie['criteres'] as $c) {
                $critere = new Critere();
                $critere->setGrille($this->getReference('grille9'));
                $critere->setParent($this->getReference('critere' . $index));
                $critere->setContenu($c)
                    ->setCoefficient('1.00');
                $manager->persist($critere);
            }
            $index++;
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            GrilleFixtures::class,
        );
    }
}
