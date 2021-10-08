<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Output\Generator\Xml;

use Ibexa\Rest;
use Ibexa\Tests\Rest\Output\Generator\FieldTypeHashGeneratorBaseTest;

class FieldTypeHashGeneratorTest extends FieldTypeHashGeneratorBaseTest
{
    /**
     * Initializes the field type hash generator.
     *
     * @return \EzSystems\EzPlatformRest\Output\Generator\Xml\FieldTypeHashGenerator
     */
    protected function initializeFieldTypeHashGenerator()
    {
        return new EzPlatformRest\Output\Generator\Xml\FieldTypeHashGenerator();
    }

    /**
     * Initializes the generator.
     *
     * @return \EzSystems\EzPlatformRest\Output\Generator
     */
    protected function initializeGenerator()
    {
        $generator = new EzPlatformRest\Output\Generator\Xml(
            $this->getFieldTypeHashGenerator()
        );
        $generator->setFormatOutput(true);

        return $generator;
    }
}

class_alias(FieldTypeHashGeneratorTest::class, 'EzSystems\EzPlatformRest\Tests\Output\Generator\Xml\FieldTypeHashGeneratorTest');
