<?php


namespace BrandonlinU\CvsUpdaterBundler\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('brandonlinu_csv_updater');
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('github')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('signature_secret')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('%env(default::GITHUB_SECRET)%')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}