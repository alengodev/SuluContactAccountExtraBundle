<?php

declare(strict_types=1);

namespace Alengo\SuluContactAccountExtraBundle\Controller\Admin;

use Alengo\SuluContactAccountExtraBundle\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Sulu\Bundle\ContactBundle\Admin\ContactAdmin;
use Sulu\Bundle\ContactBundle\Entity\AccountInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountAdditionalDataController implements SecuredControllerInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getAction(int $id): Response
    {
        $account = $this->entityManager->getRepository(AccountInterface::class)->find($id);
        if (!$account instanceof Account) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(['id' => $account->getId()] + $account->getAdditionalData());
    }

    public function putAction(Request $request, int $id): Response
    {
        $account = $this->entityManager->getRepository(AccountInterface::class)->find($id);
        if (!$account instanceof Account) {
            throw new NotFoundHttpException();
        }

        $data = $request->toArray();
        unset($data['id']);
        $account->setAdditionalData($data);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $account->getId()] + $account->getAdditionalData());
    }

    /**
     * Organizations, like Sulu's own AccountController — not the people context: a role with access to people but
     * none to organizations must not read or overwrite the additional data of organizations.
     */
    public function getSecurityContext(): string
    {
        return ContactAdmin::ACCOUNT_SECURITY_CONTEXT;
    }

    public function getLocale(Request $request): ?string
    {
        return $request->query->get('locale');
    }
}
