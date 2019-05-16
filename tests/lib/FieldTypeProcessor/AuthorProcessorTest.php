<?php

/**
 * File containing the DateProcessorTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\FieldTypeProcessor;

use EzSystems\EzPlatformRest\FieldTypeProcessor\AuthorProcessor;
use PHPUnit\Framework\TestCase;

class AuthorProcessorTest extends TestCase
{
    protected $constants = [
        'DEFAULT_VALUE_EMPTY',
        'DEFAULT_CURRENT_USER',
    ];

    public function fieldSettingsHashes()
    {
        return array_map(
            function ($constantName) {
                return [
                    ['defaultAuthor' => $constantName],
                    ['defaultAuthor' => constant("eZ\\Publish\\Core\\FieldType\\Author\\Type::{$constantName}")],
                ];
            },
            $this->constants
        );
    }

    /**
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\AuthorProcessor::preProcessFieldSettingsHash
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
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\AuthorProcessor::postProcessFieldSettingsHash
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
     * @return \EzSystems\EzPlatformRest\FieldTypeProcessor\AuthorProcessor
     */
    protected function getProcessor()
    {
        return new AuthorProcessor();
    }
}
