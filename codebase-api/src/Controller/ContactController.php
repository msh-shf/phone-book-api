<?php

namespace App\Controller;

use App\Entity\Contact;
use App\EntityFactory\ContactFactory;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/api', name: 'api')]
class ContactController extends BaseController
{
    #[Route('/contact', name: 'contact_index', methods: ["GET"])]
    public function index(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $name = $request->query->get('name') ?? '';
        $page = $request->query->get('page') ?? 1;
        $size = $request->query->get('size') ?? 10;

        /** @var ContactRepository $contactRepository */
        $contactRepository = $doctrine->getRepository(Contact::class);
        $data = $contactRepository->findByName($name, $page, $size);

        return $this->json($data->asArray());
    }

    #[Route('/contact', name: 'contact_create', methods: ["POST"])]
    public function create(Request $request, ManagerRegistry $doctrine) : JsonResponse
    {
        $postData = json_decode($request->getContent(), true);
        $contact = ContactFactory::create($postData);

        /** @var ContactRepository $contactRepository */
        $contactRepository = $doctrine->getRepository(Contact::class);
        $contactRepository->add($contact, true);

        return $this->json([
            'message' => 'The Contact created successfully!',
            'data' => $contact->getId()
        ], 201);
    }

    #[Route('/contact/{id}', name: 'contact_update', methods: ["PUT"])]
    public function update(Request $request, int $id, ManagerRegistry $doctrine) : JsonResponse
    {
        /** @var Contact $contact */
        $contact = $doctrine->getRepository(Contact::class)->find($id);

        if (!$contact) {
            return $this->notFoundJsonResponse($id);
        }

        $postData = json_decode($request->getContent(), true);
        ContactFactory::setAttributes($contact, $postData);

        $doctrine->getManager()->flush();

        return $this->json($contact->asArray());
    }

    #[Route('/contact/{id}', name: 'contact_show', methods: ["GET"])]
    public function show(Request $request, int $id, ManagerRegistry $doctrine) : JsonResponse
    {
        /** @var Contact $contact */
        $contact = $doctrine->getRepository(Contact::class)->find($id);

        if (!$contact) {
            return $this->notFoundJsonResponse($id);
        }

        return $this->json($contact->asArray());
    }

    #[Route('/contact/{id}', name: 'contact_delete', methods: ["DELETE"])]
    public function delete(Request $request, int $id, ManagerRegistry $doctrine) : JsonResponse
    {
        /** @var ContactRepository $contactRepository */
        $contactRepository = $doctrine->getRepository(Contact::class);

        /** @var Contact $contact */
        $contact = $contactRepository->find($id);

        if (!$contact) {
            return $this->notFoundJsonResponse($id);
        }

        $contactRepository->remove($contact, true);

        return $this->json([
            'message' => 'The Contact deleted successfully!',
            'data' => $id
        ], 204);
    }
}
