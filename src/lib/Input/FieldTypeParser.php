<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Input;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\FieldTypeService;
use Ibexa\Rest\FieldTypeProcessorRegistry;

class FieldTypeParser
{
    /**
     * @var \Ibexa\Contracts\Core\Repository\ContentService
     */
    protected $contentService;

    /**
     * @var \Ibexa\Contracts\Core\Repository\ContentTypeService
     */
    protected $contentTypeService;

    /**
     * @var \Ibexa\Contracts\Core\Repository\FieldTypeService
     */
    protected $fieldTypeService;

    /**
     * @var \Ibexa\Rest\FieldTypeProcessorRegistry
     */
    protected $fieldTypeProcessorRegistry;

    /**
     * @param \Ibexa\Contracts\Core\Repository\ContentService $contentService
     * @param \Ibexa\Contracts\Core\Repository\ContentTypeService $contentTypeService
     * @param \Ibexa\Contracts\Core\Repository\FieldTypeService $fieldTypeService
     * @param \Ibexa\Rest\FieldTypeProcessorRegistry $fieldTypeProcessorRegistry
     */
    public function __construct(
        ContentService $contentService,
        ContentTypeService $contentTypeService,
        FieldTypeService $fieldTypeService,
        FieldTypeProcessorRegistry $fieldTypeProcessorRegistry
    ) {
        $this->contentService = $contentService;
        $this->contentTypeService = $contentTypeService;
        $this->fieldTypeService = $fieldTypeService;
        $this->fieldTypeProcessorRegistry = $fieldTypeProcessorRegistry;
    }

    /**
     * Parses the given $value for the field $fieldDefIdentifier in the content
     * identified by $contentInfoId.
     *
     * @param string $contentInfoId
     * @param string $fieldDefIdentifier
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseFieldValue($contentInfoId, $fieldDefIdentifier, $value)
    {
        $contentInfo = $this->contentService->loadContentInfo($contentInfoId);
        $contentType = $this->contentTypeService->loadContentType($contentInfo->contentTypeId);

        $fieldDefinition = $contentType->getFieldDefinition($fieldDefIdentifier);

        return $this->parseValue($fieldDefinition->fieldTypeIdentifier, $value);
    }

    /**
     * Parses the given $value using the FieldType identified by
     * $fieldTypeIdentifier.
     *
     * @param mixed $fieldTypeIdentifier
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseValue($fieldTypeIdentifier, $value)
    {
        if ($this->fieldTypeProcessorRegistry->hasProcessor($fieldTypeIdentifier)) {
            $fieldTypeProcessor = $this->fieldTypeProcessorRegistry->getProcessor($fieldTypeIdentifier);
            $value = $fieldTypeProcessor->preProcessValueHash($value);
        }

        $fieldType = $this->fieldTypeService->getFieldType($fieldTypeIdentifier);

        return $fieldType->fromHash($value);
    }

    /**
     * Parses the given $settingsHash using the FieldType identified by
     * $fieldTypeIdentifier.
     *
     * @param string $fieldTypeIdentifier
     * @param mixed $settingsHash
     *
     * @return mixed
     */
    public function parseFieldSettings($fieldTypeIdentifier, $settingsHash)
    {
        if ($this->fieldTypeProcessorRegistry->hasProcessor($fieldTypeIdentifier)) {
            $fieldTypeProcessor = $this->fieldTypeProcessorRegistry->getProcessor($fieldTypeIdentifier);
            $settingsHash = $fieldTypeProcessor->preProcessFieldSettingsHash($settingsHash);
        }

        $fieldType = $this->fieldTypeService->getFieldType($fieldTypeIdentifier);

        return $fieldType->fieldSettingsFromHash($settingsHash);
    }

    /**
     * Parses the given $configurationHash using the FieldType identified by
     * $fieldTypeIdentifier.
     *
     * @param string $fieldTypeIdentifier
     * @param mixed $configurationHash
     *
     * @return mixed
     */
    public function parseValidatorConfiguration($fieldTypeIdentifier, $configurationHash)
    {
        if ($this->fieldTypeProcessorRegistry->hasProcessor($fieldTypeIdentifier)) {
            $fieldTypeProcessor = $this->fieldTypeProcessorRegistry->getProcessor($fieldTypeIdentifier);
            $configurationHash = $fieldTypeProcessor->preProcessValidatorConfigurationHash($configurationHash);
        }

        $fieldType = $this->fieldTypeService->getFieldType($fieldTypeIdentifier);

        return $fieldType->validatorConfigurationFromHash($configurationHash);
    }
}

class_alias(FieldTypeParser::class, 'EzSystems\EzPlatformRest\Input\FieldTypeParser');
