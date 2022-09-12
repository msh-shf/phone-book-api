<?php

namespace App\Controller;

use App\Entity\Contact;
use App\EntityFactory\ContactFactory;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ContactController extends BaseController
{
    #[Route('/contact', name: 'contact_index', methods: ["GET"])]
    public function index(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $searchParams = $this->_getSearchParams($request);

        /** @var ContactRepository $contactRepository */
        $contactRepository = $doctrine->getRepository(Contact::class);
        $paginatedData = $contactRepository->findByName(...$searchParams);

        return $this->successJsonResponse($paginatedData->asArray());
    }

    #[Route('/contact', name: 'contact_create', methods: ["POST"])]
    public function create(Request $request, ManagerRegistry $doctrine) : JsonResponse
    {
        $postData = json_decode($request->getContent(), true);
        $contact = ContactFactory::create($postData);

        /** @var ContactRepository $contactRepository */
        $contactRepository = $doctrine->getRepository(Contact::class);
        $contactRepository->add($contact, true);

        return $this->successJsonResponse($contact->asArray(), 201);
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

        return $this->successJsonResponse($contact->asArray());
    }

    #[Route('/contact/{id}', name: 'contact_show', methods: ["GET"])]
    public function show(Request $request, int $id, ManagerRegistry $doctrine) : JsonResponse
    {
        /** @var Contact $contact */
        $contact = $doctrine->getRepository(Contact::class)->find($id);

        if (!$contact) {
            return $this->notFoundJsonResponse($id);
        }

        return $this->successJsonResponse($contact->asArray());
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

        return $this->successJsonResponse($id, 204);
    }

    private function _getSearchParams($request): array {
        $paramKeys = ['name', 'page', 'size'];
        $params = array_merge(array_fill_keys($paramKeys, null), $request->query->all());

        return array_filter($params);
    }
}
