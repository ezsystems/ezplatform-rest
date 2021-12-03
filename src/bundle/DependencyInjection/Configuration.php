<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends SiteAccessConfiguration
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(IbexaRestExtension::EXTENSION_NAME);

        $this->addRestRootResourcesSection($treeBuilder->getRootNode());

        return $treeBuilder;
    }

    public function addRestRootResourcesSection($rootNode)
    {
        $systemNode = $this->generateScopeBaseNode($rootNode);
        $systemNode
            ->arrayNode('rest_root_resources')
                ->prototype('array')
                    ->children()
                        ->scalarNode('mediaType')->isRequired()->end()
                        ->scalarNode('href')->isRequired()->end()
                    ->end()
                ->end()
            ->end();
    }
}

class_alias(Configuration::class, 'EzSystems\EzPlatformRestBundle\DependencyInjection\Configuration');
