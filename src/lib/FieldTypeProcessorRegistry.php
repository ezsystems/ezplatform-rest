<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest;

use Ibexa\Contracts\Rest\FieldTypeProcessor;

/**
 * FieldTypeProcessorRegistry.
 */
class FieldTypeProcessorRegistry
{
    /**
     * Registered processors.
     *
     * @var \Ibexa\Contracts\Rest\FieldTypeProcessor[]
     */
    private $processors = [];

    /**
     * @param \Ibexa\Contracts\Rest\FieldTypeProcessor[] $processors
     */
    public function __construct(array $processors = [])
    {
        foreach ($processors as $fieldTypeIdentifier => $processor) {
            $this->registerProcessor($fieldTypeIdentifier, $processor);
        }
    }

    /**
     * Registers $processor for $fieldTypeIdentifier.
     *
     * @param string $fieldTypeIdentifier
     * @param \Ibexa\Contracts\Rest\FieldTypeProcessor $processor
     */
    public function registerProcessor($fieldTypeIdentifier, FieldTypeProcessor $processor)
    {
        $this->processors[$fieldTypeIdentifier] = $processor;
    }

    /**
     * Returns if a processor is registered for $fieldTypeIdentifier.
     *
     * @param string $fieldTypeIdentifier
     *
     * @return bool
     */
    public function hasProcessor($fieldTypeIdentifier)
    {
        return isset($this->processors[$fieldTypeIdentifier]);
    }

    /**
     * Returns the processor for $fieldTypeIdentifier.
     *
     * @param string $fieldTypeIdentifier
     *
     * @throws \RuntimeException if not processor is registered for $fieldTypeIdentifier
     *
     * @return \Ibexa\Contracts\Rest\FieldTypeProcessor
     */
    public function getProcessor($fieldTypeIdentifier)
    {
        if (!$this->hasProcessor($fieldTypeIdentifier)) {
            throw new \RuntimeException(
                "Field Type processor for '{$fieldTypeIdentifier}' not found."
            );
        }

        return $this->processors[$fieldTypeIdentifier];
    }
}

class_alias(FieldTypeProcessorRegistry::class, 'EzSystems\EzPlatformRest\FieldTypeProcessorRegistry');
