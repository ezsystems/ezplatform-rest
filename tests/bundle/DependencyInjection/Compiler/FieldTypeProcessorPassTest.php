<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Bundle\Rest\DependencyInjection\Compiler;

use Ibexa\Bundle\Rest\DependencyInjection\Compiler\FieldTypeProcessorPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class FieldTypeProcessorPassTest extends TestCase
{
    /**
     * @dataProvider dataProviderForProcess
     */
    public function testProcess(string $tag): void
    {
        $processorDefinition = new Definition();
        $processorDefinition->addTag($tag, ['alias' => 'test']);

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions(
            [
                'ezpublish_rest.field_type_processor_registry' => new Definition(),
                'ezpublish_rest.field_type_processor.test' => $processorDefinition,
            ]
        );

        $compilerPass = new FieldTypeProcessorPass();
        $compilerPass->process($containerBuilder);

        $dispatcherMethodCalls = $containerBuilder->getDefinition('ezpublish_rest.field_type_processor_registry')->getMethodCalls();
        self::assertTrue(isset($dispatcherMethodCalls[0][0]), 'Failed asserting that dispatcher has a method call');
        self::assertEquals('registerProcessor', $dispatcherMethodCalls[0][0], "Failed asserting that called method is 'addVisitor'");
        self::assertInstanceOf(Reference::class, $dispatcherMethodCalls[0][1][1], 'Failed asserting that method call is to a Reference object');
        self::assertEquals('ezpublish_rest.field_type_processor.test', $dispatcherMethodCalls[0][1][1]->__toString(), "Failed asserting that Referenced service is 'ezpublish_rest.output.value_object_visitor.test'");
    }

    public function dataProviderForProcess(): iterable
    {
        yield ['ibexa.rest.field_type.processor'];
        yield ['ezpublish_rest.field_type_processor'];
    }
}

class_alias(FieldTypeProcessorPassTest::class, 'EzSystems\EzPlatformRestBundle\Tests\DependencyInjection\Compiler\FieldTypeProcessorPassTest');
