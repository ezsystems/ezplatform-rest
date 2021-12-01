<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\DependencyInjection\Compiler;

use Ibexa\Core\Base\Container\Compiler\TaggedServiceIdsIterator\BackwardCompatibleIterator;
use LogicException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FieldTypeProcessorPass implements CompilerPassInterface
{
    private const FIELD_TYPE_PROCESSOR_SERVICE_TAG = 'ibexa.rest.field_type.processor';
    private const DEPRECATED_FIELD_TYPE_PROCESSOR_SERVICE_TAG = 'ezpublish_rest.field_type_processor';

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ezpublish_rest.field_type_processor_registry')) {
            return;
        }

        $definition = $container->getDefinition('ezpublish_rest.field_type_processor_registry');

        $iterator = new BackwardCompatibleIterator(
            $container,
            self::FIELD_TYPE_PROCESSOR_SERVICE_TAG,
            self::DEPRECATED_FIELD_TYPE_PROCESSOR_SERVICE_TAG
        );

        foreach ($iterator as $serviceId => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['alias'])) {
                    throw new LogicException(
                        sprintf(
                            'Service "%s" tagged with "%s" or "%s" needs an "alias" attribute to identify the Field Type',
                            $serviceId,
                            self::FIELD_TYPE_PROCESSOR_SERVICE_TAG,
                            self::DEPRECATED_FIELD_TYPE_PROCESSOR_SERVICE_TAG
                        )
                    );
                }

                $definition->addMethodCall(
                    'registerProcessor',
                    [$attribute['alias'], new Reference($serviceId)]
                );
            }
        }
    }
}

class_alias(FieldTypeProcessorPass::class, 'EzSystems\EzPlatformRestBundle\DependencyInjection\Compiler\FieldTypeProcessorPass');
