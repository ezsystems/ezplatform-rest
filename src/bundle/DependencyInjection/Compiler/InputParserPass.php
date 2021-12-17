<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Container compiler processor for the ezpublish_rest.input.parser service tag.
 * Maps input parsers to media types.
 *
 * Tag attributes: mediaType. Ex: application/vnd.ez.api.Content
 */
class InputParserPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ezpublish_rest.input.parsing_dispatcher')) {
            return;
        }

        $definition = $container->getDefinition('ezpublish_rest.input.parsing_dispatcher');

        foreach ($container->findTaggedServiceIds('ezpublish_rest.input.parser') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['mediaType'])) {
                    throw new \LogicException('The ezpublish_rest.input.parser service tag needs a "mediaType" attribute to identify the input parser.');
                }

                $definition->addMethodCall(
                    'addParser',
                    [$attribute['mediaType'], new Reference($id)]
                );
            }
        }
    }
}

class_alias(InputParserPass::class, 'EzSystems\EzPlatformRestBundle\DependencyInjection\Compiler\InputParserPass');
