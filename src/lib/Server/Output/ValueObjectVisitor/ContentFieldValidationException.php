<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Translation;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * ContentFieldValidationException value object visitor.
 */
class ContentFieldValidationException extends BadRequestException
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Exceptions\ContentFieldValidationException $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ErrorMessage');

        $statusCode = $this->getStatus();
        $visitor->setStatus($statusCode);
        $visitor->setHeader('Content-Type', $generator->getMediaType('ErrorMessage'));

        $generator->valueElement('errorCode', $statusCode);

        $generator->valueElement(
            'errorMessage',
            static::$httpStatusCodes[$statusCode] ?? static::$httpStatusCodes[500]
        );

        $generator->valueElement('errorDescription', $data->getMessage());

        $generator->startHashElement('errorDetails');
        $generator->startList('fields');
        foreach ($data->getFieldErrors() as $fieldTypeId => $translations) {
            foreach ($translations as $languageCode => $validationErrors) {
                if (!is_array($validationErrors)) {
                    $validationErrors = [$validationErrors];
                }

                foreach ($validationErrors as $validationError) {
                    $generator->startHashElement('field');
                    $generator->attribute('fieldTypeId', $fieldTypeId);

                    $generator->startList('errors');
                    $generator->startHashElement('error');

                    $generator->valueElement('type', $validationError->getTarget());

                    $translation = $validationError->getTranslatableMessage();
                    $generator->valueElement(
                        'message',
                        $this->translator->trans(
                            $this->translationToString($translation),
                            $translation->values,
                            'repository_exceptions'
                        )
                    );

                    $generator->endHashElement('error');
                    $generator->endList('errors');
                    $generator->endHashElement('field');
                }
            }
        }
        $generator->endList('fields');
        $generator->endHashElement('errorDetails');

        if ($this->debug) {
            $generator->valueElement('trace', $data->getTraceAsString());
            $generator->valueElement('file', $data->getFile());
            $generator->valueElement('line', $data->getLine());
        }

        $generator->endObjectElement('ErrorMessage');
    }

    /**
     * Convert a Translation object to a string, detecting singular/plural as needed.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Translation $translation The Translation object
     *
     * @return string
     */
    private function translationToString(Translation $translation)
    {
        $values = $translation->values;
        if ($translation instanceof Translation\Plural) {
            if (current($values) === 1) {
                return $translation->singular;
            } else {
                return $translation->plural;
            }
        } else {
            return $translation->message;
        }
    }
}

class_alias(ContentFieldValidationException::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ContentFieldValidationException');
