<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Core\Base\Exceptions\ContentFieldValidationException as CoreContentFieldValidationException;
use Ibexa\Core\FieldType\ValidationError;
use Ibexa\Rest\Server\Exceptions;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;
use Symfony\Component\Translation\Translator;

class ContentFieldValidationExceptionTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the ContentFieldValidationException visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $exception = $this->getException();

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $exception
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains ErrorMessage element and description.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsErrorDescription($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'errorDescription',
                'content' => $this->getExpectedDescription(),
            ],
            $result,
            'Missing <errorDescription> element.'
        );
    }

    /**
     * Test if result contains ErrorMessage element and details.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsErrorDetails($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'errorDetails',
            ],
            $result,
            'Missing <errorDetails> element.'
        );

        $this->assertXMLTag(
            [
                'tag' => 'field',
            ],
            $result,
            'Missing <field> element.'
        );
    }

    /**
     * Get expected status code.
     *
     * @return int
     */
    protected function getExpectedStatusCode()
    {
        return 400;
    }

    /**
     * Get expected message.
     *
     * @return string
     */
    protected function getExpectedMessage()
    {
        return 'Bad Request';
    }

    /**
     * Get expected description.
     *
     * @return string
     */
    protected function getExpectedDescription()
    {
        return 'Content Fields did not validate';
    }

    /**
     * Gets the exception.
     *
     * @return \Exception
     */
    protected function getException()
    {
        return new Exceptions\ContentFieldValidationException(
            new CoreContentFieldValidationException([
                1 => [
                    'eng-GB' => new ValidationError(
                        "Value for required field definition '%identifier%' with language '%languageCode%' is empty",
                        null,
                        ['%identifier%' => 'name', '%languageCode%' => 'eng-GB'],
                        'empty'
                    ),
                ],
                2 => [
                    'eng-GB' => new ValidationError(
                        'The value must be a valid email address.',
                        null,
                        [],
                        'email'
                    ),
                ],
            ])
        );
    }

    /**
     * Gets the exception visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\ContentFieldValidationException
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\ContentFieldValidationException(false, new Translator('eng-GB'));
    }
}

class_alias(ContentFieldValidationExceptionTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\ContentFieldValidationExceptionTest');
