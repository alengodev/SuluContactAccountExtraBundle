<?php

declare(strict_types=1);

namespace Alengo\SuluContactAccountExtraBundle\Controller\Admin;

use Alengo\SuluContactAccountExtraBundle\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Sulu\Bundle\ContactBundle\Admin\ContactAdmin;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactAdditionalDataController implements SecuredControllerInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getAction(int $id): Response
    {
        $contact = $this->entityManager->getRepository(ContactInterface::class)->find($id);
        if (!$contact instanceof Contact) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(['id' => $contact->getId()] + $contact->getAdditionalData());
    }

    public function putAction(Request $request, int $id): Response
    {
        $contact = $this->entityManager->getRepository(ContactInterface::class)->find($id);
        if (!$contact instanceof Contact) {
            throw new NotFoundHttpException();
        }

        $data = $request->toArray();
        unset($data['id']);
        $contact->setAdditionalData($data);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $contact->getId()] + $contact->getAdditionalData());
    }

    public function getSecurityContext(): string
    {
        return ContactAdmin::CONTACT_SECURITY_CONTEXT;
    }

    public function getLocale(Request $request): ?string
    {
        return $request->query->get('locale');
    }
}
