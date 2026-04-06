<?php

declare(strict_types=1);

namespace Alengo\SuluContactAccountExtraBundle\DependencyInjection;

use Alengo\SuluContactAccountExtraBundle\Entity\Account;
use Alengo\SuluContactAccountExtraBundle\Entity\Contact;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('alengo_contact_account_extra');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('contact')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('entity_class')
                            ->defaultValue(Contact::class)
                        ->end()
                        ->scalarNode('form_key')
                            ->defaultValue('contact_additional_data')
                        ->end()
                        ->scalarNode('resource_key')
                            ->defaultValue('contact_additional_data')
                        ->end()
                        ->scalarNode('tab_title')
                            ->defaultValue('sulu_admin.app.additional_data')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('account')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('entity_class')
                            ->defaultValue(Account::class)
                        ->end()
                        ->scalarNode('form_key')
                            ->defaultValue('account_additional_data')
                        ->end()
                        ->scalarNode('resource_key')
                            ->defaultValue('account_additional_data')
                        ->end()
                        ->scalarNode('tab_title')
                            ->defaultValue('sulu_admin.app.additional_data')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
