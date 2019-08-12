<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRestBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends SiteAccessConfiguration
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ez_publish_rest');

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
