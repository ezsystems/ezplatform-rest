<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests;

use EzSystems\EzPlatformRest\FieldTypeProcessorRegistry;
use EzSystems\EzPlatformRest\FieldTypeProcessor;
use EzSystems\EzPlatformRest\Tests\Server\BaseTest;
use RuntimeException;

class FieldTypeProcessorRegistryTest extends BaseTest
{
    public function testRegisterProcessor()
    {
        $registry = new FieldTypeProcessorRegistry();

        $processor = $this->getAProcessorMock();

        $registry->registerProcessor('my-type', $processor);

        $this->assertTrue($registry->hasProcessor('my-type'));
    }

    public function testRegisterMultipleProcessors()
    {
        $registry = new FieldTypeProcessorRegistry();

        $processorA = $this->getAProcessorMock();
        $processorB = $this->getAProcessorMock();

        $registry->registerProcessor('my-type', $processorA);
        $registry->registerProcessor('your-type', $processorB);

        $this->assertTrue($registry->hasProcessor('my-type'));
        $this->assertTrue($registry->hasProcessor('your-type'));
    }

    public function testHasProcessorFailure()
    {
        $registry = new FieldTypeProcessorRegistry();

        $this->assertFalse($registry->hasProcessor('my-type'));
    }

    public function testGetProcessor()
    {
        $registry = new FieldTypeProcessorRegistry();

        $processor = $this->getAProcessorMock();

        $registry->registerProcessor('my-type', $processor);

        $this->assertSame(
            $processor,
            $registry->getProcessor('my-type')
        );
    }

    public function testGetProcessorNotFoundException()
    {
        $this->expectException(RuntimeException::class);

        $registry = new FieldTypeProcessorRegistry();

        $registry->getProcessor('my-type');
    }

    public function testRegisterProcessorsOverwrite()
    {
        $registry = new FieldTypeProcessorRegistry();

        $processorA = $this->getAProcessorMock();
        $processorB = $this->getAProcessorMock();

        $registry->registerProcessor('my-type', $processorA);
        $registry->registerProcessor('my-type', $processorB);

        $this->assertSame(
            $processorB,
            $registry->getProcessor('my-type')
        );
    }

    /**
     * Get FieldTypeProcessor mock object.
     *
     * @return \EzSystems\EzPlatformRest\FieldTypeProcessor
     */
    protected function getAProcessorMock()
    {
        return $this->createMock(FieldTypeProcessor::class);
    }
}
