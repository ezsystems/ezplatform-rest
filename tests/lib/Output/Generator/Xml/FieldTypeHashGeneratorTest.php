<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Output\Generator\Xml;

use Ibexa\Rest\Output\Generator\Xml;
use Ibexa\Rest\Output\Generator\Xml\FieldTypeHashGenerator;
use Ibexa\Tests\Rest\Output\Generator\FieldTypeHashGeneratorBaseTest;

class FieldTypeHashGeneratorTest extends FieldTypeHashGeneratorBaseTest
{
    /**
     * Initializes the field type hash generator.
     *
     * @return \Ibexa\Rest\Output\Generator\Xml\FieldTypeHashGenerator
     */
    protected function initializeFieldTypeHashGenerator()
    {
        return new FieldTypeHashGenerator();
    }

    /**
     * Initializes the generator.
     *
     * @return \Ibexa\Contracts\Rest\Output\Generator
     */
    protected function initializeGenerator()
    {
        $generator = new Xml(
            $this->getFieldTypeHashGenerator()
        );
        $generator->setFormatOutput(true);

        return $generator;
    }
}

class_alias(FieldTypeHashGeneratorTest::class, 'EzSystems\EzPlatformRest\Tests\Output\Generator\Xml\FieldTypeHashGeneratorTest');
