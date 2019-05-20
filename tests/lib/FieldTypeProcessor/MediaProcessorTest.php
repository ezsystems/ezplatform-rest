<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\FieldTypeProcessor;

use EzSystems\EzPlatformRest\FieldTypeProcessor\MediaProcessor;

class MediaProcessorTest extends BinaryInputProcessorTest
{
    protected $constants = [
        'TYPE_FLASH',
        'TYPE_QUICKTIME',
        'TYPE_REALPLAYER',
        'TYPE_SILVERLIGHT',
        'TYPE_WINDOWSMEDIA',
        'TYPE_HTML5_VIDEO',
        'TYPE_HTML5_AUDIO',
    ];

    public function fieldSettingsHashes()
    {
        return array_map(
            function ($constantName) {
                return [
                    ['mediaType' => $constantName],
                    ['mediaType' => constant("eZ\\Publish\\Core\\FieldType\\Media\\Type::{$constantName}")],
                ];
            },
            $this->constants
        );
    }

    /**
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\MediaProcessor::preProcessFieldSettingsHash
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
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\MediaProcessor::postProcessFieldSettingsHash
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
     * @return \EzSystems\EzPlatformRest\FieldTypeProcessor\MediaProcessor
     */
    protected function getProcessor()
    {
        return new MediaProcessor($this->getTempDir());
    }
}
