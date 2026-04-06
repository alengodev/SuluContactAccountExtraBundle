<?php

declare(strict_types=1);

namespace Alengo\SuluContactAccountExtraBundle\DependencyInjection;

use Alengo\SuluContactAccountExtraBundle\Admin\ContactAccountExtraAdmin;
use Alengo\SuluContactAccountExtraBundle\Controller\Admin\AccountAdditionalDataController;
use Alengo\SuluContactAccountExtraBundle\Controller\Admin\ContactAdditionalDataController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

class AlengoContactAccountExtraExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $contactClass = \Alengo\SuluContactAccountExtraBundle\Entity\Contact::class;
        $accountClass = \Alengo\SuluContactAccountExtraBundle\Entity\Account::class;
        $contactResourceKey = 'contact_additional_data';
        $accountResourceKey = 'account_additional_data';

        foreach ($configs as $c) {
            if (isset($c['contact']['entity_class'])) {
                $contactClass = $c['contact']['entity_class'];
            }
            if (isset($c['account']['entity_class'])) {
                $accountClass = $c['account']['entity_class'];
            }
            if (isset($c['contact']['resource_key'])) {
                $contactResourceKey = $c['contact']['resource_key'];
            }
            if (isset($c['account']['resource_key'])) {
                $accountResourceKey = $c['account']['resource_key'];
            }
        }

        if ($container->hasExtension('sulu_contact')) {
            $container->prependExtensionConfig('sulu_contact', [
                'objects' => [
                    'contact' => ['model' => $contactClass],
                    'account' => ['model' => $accountClass],
                ],
            ]);
        }

        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig('sulu_admin', [
                'resources' => [
                    $contactResourceKey => [
                        'routes' => ['detail' => 'alengo_contact_account_extra_get_contact_additional_data'],
                    ],
                    $accountResourceKey => [
                        'routes' => ['detail' => 'alengo_contact_account_extra_get_account_additional_data'],
                    ],
                ],
            ]);
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Admin tab
        $adminDef = new Definition(ContactAccountExtraAdmin::class);
        $adminDef->addArgument(new Reference('sulu_admin.view_builder_factory'));
        $adminDef->addArgument($config['contact']['form_key']);
        $adminDef->addArgument($config['contact']['resource_key']);
        $adminDef->addArgument($config['contact']['tab_title']);
        $adminDef->addArgument($config['account']['form_key']);
        $adminDef->addArgument($config['account']['resource_key']);
        $adminDef->addArgument($config['account']['tab_title']);
        $adminDef->addTag('sulu.admin');
        $adminDef->addTag('sulu.context', ['context' => 'admin']);
        $container->setDefinition(ContactAccountExtraAdmin::class, $adminDef);

        // Account controller
        $accountControllerDef = new Definition(AccountAdditionalDataController::class);
        $accountControllerDef->addArgument(new Reference('doctrine.orm.entity_manager'));
        $accountControllerDef->setPublic(true);
        $container->setDefinition(AccountAdditionalDataController::class, $accountControllerDef);

        // Contact controller
        $contactControllerDef = new Definition(ContactAdditionalDataController::class);
        $contactControllerDef->addArgument(new Reference('doctrine.orm.entity_manager'));
        $contactControllerDef->setPublic(true);
        $container->setDefinition(ContactAdditionalDataController::class, $contactControllerDef);
    }

    public function getAlias(): string
    {
        return 'alengo_contact_account_extra';
    }
}
