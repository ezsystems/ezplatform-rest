<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\FieldTypeProcessor;

use EzSystems\EzPlatformRest\FieldTypeProcessor\TimeProcessor;
use PHPUnit\Framework\TestCase;

class TimeProcessorTest extends TestCase
{
    protected $constants = [
        'DEFAULT_EMPTY',
        'DEFAULT_CURRENT_TIME',
    ];

    public function fieldSettingsHashes()
    {
        return array_map(
            function ($constantName) {
                return [
                    ['defaultType' => $constantName],
                    ['defaultType' => constant("eZ\\Publish\\Core\\FieldType\\Time\\Type::{$constantName}")],
                ];
            },
            $this->constants
        );
    }

    /**
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\TimeProcessor::preProcessFieldSettingsHash
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
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\TimeProcessor::postProcessFieldSettingsHash
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
     * @return \EzSystems\EzPlatformRest\FieldTypeProcessor\TimeProcessor
     */
    protected function getProcessor()
    {
        return new TimeProcessor();
    }
}
