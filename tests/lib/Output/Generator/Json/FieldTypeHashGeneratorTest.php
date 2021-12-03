<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Output\Generator\Json;

use Ibexa\Rest\Output\Generator\Json;
use Ibexa\Rest\Output\Generator\Json\FieldTypeHashGenerator;
use Ibexa\Tests\Rest\Output\Generator\FieldTypeHashGeneratorBaseTest;

class FieldTypeHashGeneratorTest extends FieldTypeHashGeneratorBaseTest
{
    /**
     * Initializes the field type hash generator.
     *
     * @return \Ibexa\Rest\Output\Generator\Json\FieldTypeHashGenerator
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
        return new Json(
            $this->getFieldTypeHashGenerator()
        );
    }
}

class_alias(FieldTypeHashGeneratorTest::class, 'EzSystems\EzPlatformRest\Tests\Output\Generator\Json\FieldTypeHashGeneratorTest');
