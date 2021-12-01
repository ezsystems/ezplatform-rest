<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\FieldTypeProcessor;

use Ibexa\Rest\FieldTypeProcessor\ImageProcessor;
use Symfony\Component\Routing\RouterInterface;

class ImageProcessorTest extends BinaryInputProcessorTest
{
    /** @var \Ibexa\Rest\RequestParser */
    protected $requestParser;

    /**
     * @covers \EzSystems\EzPlatformRest\FieldTypeProcessor\ImageProcessor::postProcessValueHash
     */
    public function testPostProcessValueHash()
    {
        $processor = $this->getProcessor();

        $inputHash = [
            'path' => 'var/some_site/223-1-eng-US/Cool-File.jpg',
            'imageId' => '223-12345',
        ];

        $routerMock = $this->getRouterMock();
        foreach ($this->getVariations() as $iteration => $variationIdentifier) {
            $expectedVariations[$variationIdentifier]['href'] = "/content/binary/images/{$inputHash['imageId']}/variations/{$variationIdentifier}";
            $routerMock
                ->expects($this->at($iteration))
                ->method('generate')
                ->with(
                    'ezpublish_rest_binaryContent_getImageVariation',
                    ['imageId' => $inputHash['imageId'], 'variationIdentifier' => $variationIdentifier]
                )
                ->willReturn(
                    $expectedVariations[$variationIdentifier]['href']
                );
        }

        $outputHash = $processor->postProcessValueHash($inputHash);

        $this->assertEquals(
            [
                'path' => '/var/some_site/223-1-eng-US/Cool-File.jpg',
                'imageId' => '223-12345',
                'variations' => $expectedVariations,
            ],
            $outputHash
        );
    }

    /**
     * Returns the processor under test.
     *
     * @return \Ibexa\Rest\FieldTypeProcessor\ImageProcessor
     */
    protected function getProcessor()
    {
        return new ImageProcessor(
            $this->getTempDir(),
            $this->getRouterMock(),
            $this->getVariations()
        );
    }

    /**
     * @returns \Symfony\Component\Routing\RouterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRouterMock()
    {
        if (!isset($this->requestParser)) {
            $this->requestParser = $this->createMock(RouterInterface::class);
        }

        return $this->requestParser;
    }

    protected function getVariations()
    {
        return ['small', 'medium', 'large'];
    }
}

class_alias(ImageProcessorTest::class, 'EzSystems\EzPlatformRest\Tests\FieldTypeProcessor\ImageProcessorTest');
