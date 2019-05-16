<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformRest\Tests\Output\Generator\Json;

use EzSystems\EzPlatformRest;
use EzSystems\EzPlatformRest\Tests\Output\Generator\FieldTypeHashGeneratorBaseTest;

class FieldTypeHashGeneratorTest extends FieldTypeHashGeneratorBaseTest
{
    /**
     * Initializes the field type hash generator.
     *
     * @return \EzSystems\EzPlatformRest\Output\Generator\Json\FieldTypeHashGenerator
     */
    protected function initializeFieldTypeHashGenerator()
    {
        return new EzPlatformRest\Output\Generator\Json\FieldTypeHashGenerator();
    }

    /**
     * Initializes the generator.
     *
     * @return \EzSystems\EzPlatformRest\Output\Generator
     */
    protected function initializeGenerator()
    {
        return new EzPlatformRest\Output\Generator\Json(
            $this->getFieldTypeHashGenerator()
        );
    }
}
