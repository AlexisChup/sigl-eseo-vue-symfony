<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Promotion;
use App\Entity\Utilisateur;
use App\Repository\DocumentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/api/document', name: 'api_document')]
class DocumentController extends AbstractController
{
    public const TYPE_DOC_PEDAGO = "PEDAGO";
    public const TYPE_DOC_LIVRABLE = "LIVRABLE";

    public const pathDocPedago = "/storage/documents-pedagogiques";
    public const pathUsersDir = "/storage/users";

    public function getPathDestination(string $typeDoc, ?string $userName)
    {
        if (strtoupper($typeDoc) == self::TYPE_DOC_PEDAGO) {

            return $this->getParameter('kernel.project_dir') . self::pathDocPedago;

        } elseif (strtoupper($typeDoc) == self::TYPE_DOC_LIVRABLE) {

            return $this->getParameter('kernel.project_dir') . self::pathUsersDir . '/' . $userName;
        }
    }

    #[Route('/publish', name: 'publish_document', methods: 'POST')]
    public function publishDocument(Request $request, DocumentRepository $docRepo, ManagerRegistry $manager): Response
    {
        $doc = new Document();
        $userRepo = $manager->getRepository(Utilisateur::class);
        $promoRepo = $manager->getRepository(Promotion::class);

        if (!$request->request->has('type') || !$request->request->has('user_id')) {
            return $this->json('Missing body parameters');
        }

        $uploadedFile = $request->files->get('file');
        $typeDoc = $request->request->get('type');
        $userId = (int)$request->request->get('user_id');
        $user = $userRepo->find($userId);

        $promo = null;
        if ($request->request->has('promo_id')) {
            $idPromo = $request->request->get('promo_id');
            $promo = $promoRepo->find($idPromo);
        }

        $destination = $this->getPathDestination($typeDoc,
            strtolower($user->getNom() . substr($user->getPrenom(), 0, 2)));

        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploadedFile->guessExtension();
        $newFileName = Urlizer::urlize($originalFileName) . '-' . uniqid() . '.' . $extension;

        try {
            //store file in storage directory
            $uploadedFile->move($destination, $newFileName);

            $doc->setName($uploadedFile->getClientOriginalName());
            $doc->setPath($destination . '/' . $newFileName);
            $doc->setType(strtoupper($typeDoc));
            $doc->setUtilisateur($user);
            $doc->setExtension($extension);
            $doc->setDate(new \DateTime());
            if ($request->request->has('promo_id')) {
                $doc->setPromotion($promo);
            }

            $docRepo->save($doc, true);

        } catch (e) {
            return $this->json('ERROR');
        }

        return $this->json($doc);
    }


    #[Route('/from-promo/{id_promo}', name: 'get_document_from_promo', methods: 'GET', requirements: ['id_promo' => '\d+'])]
    public function getDocFromPromo(int $id_promo, DocumentRepository $docRepo): Response
    {
        $docs = $docRepo->findBy(array('promotion' => $id_promo || null, 'type' => 'PEDAGO'));
        return $this->json($docs);
    }

    #[Route('/from-user/{id_user}', name: 'get_document_from_user', methods: 'GET', requirements: ['id_user' => '\d+'])]
    public function getDocFromUser(int $id_user, DocumentRepository $docRepo): Response
    {
        $docs = $docRepo->findBy(array('utilisateur' => $id_user));
        return $this->json($docs);
    }

    #[Route('/pedago', name: 'get_docs_pedago', methods: 'GET')]
    public function getDocPedago(DocumentRepository $docRepo): Response
    {
        $docs = $docRepo->findBy(array('type' => 'PEDAGO'));
        return $this->json($docs);
    }

    #[Route('/download/{id}', name: 'download_doc', methods: 'GET', requirements: ['id' => '\d+'])]
    public function downloadDoc(int $id, DocumentRepository $docRepo): Response
    {
        $doc = $docRepo->find($id);

        // This should return the file to the browser as response
        $response = new BinaryFileResponse($doc->getPath());

        // Set the mimetype of the file manually, in this case for a text file is text/plain
        $response->headers->set('Content-Type', 'text/plain');

        // Set content disposition inline of the file
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $doc->getName()
        );

        return $response;
    }

    #[Route('/delete/{id}', name: 'delete_doc', methods: 'DELETE', requirements: ['id' => '\d+'])]
    public function deleteDoc(int $id, DocumentRepository $docRepo): Response
    {
        $doc = $docRepo->find($id);

        try {
            // remove the file on fileSystem
            unlink($doc->getPath(), null);

            // Remove the entity instance
            $docRepo->remove($doc, true);

            return $this->json('DELETED');
        } catch (e) {
            return $this->json('ERROR');
        }
    }
}
