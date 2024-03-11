<?php

namespace App\Controller;

use App\Entity\Apprenti;
use App\Entity\NotesPeriode;
use App\Repository\NotesPeriodeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JsonException;
use OpenApi\Annotations as OAnn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/notes-periode', name: 'api_notes_periode')]
class NotesPeriodeController extends AbstractController
{
    #[Route('/create', name: 'apprenti_create_note', methods: 'POST')]
    /**
     * @OAnn\Tag(name="NotesPeriode")
     * @throws JsonException
     */
    public function createNotesPeriode(NotesPeriodeRepository $notesPeriodeRepository, Request $request, ManagerRegistry $manager): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $note = new NotesPeriode();

        $appRepo = $manager->getRepository(Apprenti::class);

        try {
            $note->setTitre($data["titre"]);
            $note->setContenu($data["contenu"]);
            $note->setSemestre($data["semestre"]);
            $note->setApprenti($appRepo->findOneBy(array("utilisateur" => $data["user_id"])));
            $note->setDate(new \DateTime());

            $notesPeriodeRepository->add($note, true);

            return $this->json($note);
        } catch (Exception) {
            return $this->json(["ERROR" => "Erreur lors de la création de la note périodique"], 400);
        }
    }

    #[Route('/{id}', name: 'get_note', requirements: ['id' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="NotesPeriode")
     */
    public function getNotePeriode(int $id, NotesPeriodeRepository $notesRepo): Response
    {
        return $this->json($notesRepo->find($id));
    }

    #[Route('/from/{id_user}', name: 'apprenti_get_notes', requirements: ['id_user' => '\d+'], methods: 'GET')]
    /**
     * @OAnn\Tag(name="NotesPeriode")
     */
    public function getNotesPeriode(int $id_user, NotesPeriodeRepository $notesRepo , ManagerRegistry $manager): Response
    {
        $appRepo = $manager->getRepository(Apprenti::class);
        $app = $appRepo->findOneBy(array("utilisateur" => $id_user));

        if ($app !== null) {
            $notes = $notesRepo->getNotesFromApp($app->getId());

            if ($notes !== null) {
                $response = array();
                $index = 0;

                foreach ($notes as $note) {
                    $response[$index]["titre"] = $note->getTitre();
                    $response[$index]["contenu"] = $note->getContenu();
                    $response[$index]["date"] = $note->getDate();
                    $response[$index]["id"] = $note->getId();
                    $response[$index]["semestre"] = $note->getSemestre();
                    $index++;
                }
                return $this->json($response);
            }
        }
        return $this->json([], 400);
    }

    #[Route('/update/{id}', name: 'update_note', requirements: ['id' => '\d+'], methods: 'PUT')]
    /**
     * @OAnn\Tag(name="NotesPeriode")
     * @throws JsonException
     */
    public function updateNotePeriode(int $id, NotesPeriodeRepository $notesRepo, Request $request): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $note = $notesRepo->findOneBy(array("id" => $id));

        if ($note !== null) {
            $note->setTitre($data['titre']);
            $note->setContenu($data['contenu']);
            $note->setDate(new \DateTime());

            $notesRepo->add($note, true);

            return $this->json($note);
        }
        return $this->json([], 400);
    }

    #[Route('/delete/{id}', name: 'delete_note', requirements: ['id' => '\d+'], methods: 'DELETE')]
    /**
     * @OAnn\Tag(name="NotesPeriode")
     */
    public function deleteNotePeriode(int $id, NotesPeriodeRepository $notesRepo): Response
    {
        $note = $notesRepo->find($id);

        if ($note !== null) {
            $notesRepo->remove($note, true);
            return $this->json([], 204);
        }
        return $this->json([], 400);
    }
}
