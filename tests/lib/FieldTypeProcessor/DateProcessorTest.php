<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\FieldTypeProcessor;

use EzSystems\EzPlatformRest\FieldTypeProcessor\DateProcessor;
use PHPUnit\Framework\TestCase;

class DateProcessorTest extends TestCase
{
    protected $constants = [
        'DEFAULT_EMPTY',
        'DEFAULT_CURRENT_DATE',
    ];

    public function fieldSettingsHashes()
    {
        return array_map(
            function ($constantName) {
                return [
                    ['defaultType' => $constantName],
                    ['defaultType' => constant("eZ\\Publish\\Core\\FieldType\\Date\\Type::{$constantName}")],
                ];
            },
            $this->constants
        );
    }

    /**
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\DateProcessor::preProcessFieldSettingsHash
     * @dataProvider fieldSettingsHashes
     */
    public function testPreProcessFieldSettingsHash($inputSettings, $outputSettings)
    {
        $processor = $this->getProcessor();

        $this->assertEquals(
            $outputSettings,
            $processor->preProcessFieldSettingsHash($inputSettings)
        );
    }

    /**
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\DateProcessor::postProcessFieldSettingsHash
     * @dataProvider fieldSettingsHashes
     */
    public function testPostProcessFieldSettingsHash($outputSettings, $inputSettings)
    {
        $processor = $this->getProcessor();

        $this->assertEquals(
            $outputSettings,
            $processor->postProcessFieldSettingsHash($inputSettings)
        );
    }

    /**
     * @return \EzSystems\EzPlatformRest\FieldTypeProcessor\DateProcessor
     */
    protected function getProcessor()
    {
        return new DateProcessor();
    }
}
