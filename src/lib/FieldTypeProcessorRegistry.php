<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest;

/**
 * FieldTypeProcessorRegistry.
 */
class FieldTypeProcessorRegistry
{
    /**
     * Registered processors.
     *
     * @var \EzSystems\EzPlatformRest\FieldTypeProcessorInterface[]
     */
    private $processors = [];

    /**
     * @param \EzSystems\EzPlatformRest\FieldTypeProcessorInterface[] $processors
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
     * @param \EzSystems\EzPlatformRest\FieldTypeProcessorInterface $processor
     */
    public function registerProcessor($fieldTypeIdentifier, /*FieldTypeProcessorInterface*/ $processor)
    {
        // The FieldTypeProcessorInterface was a late addition to the FieldTypeProcessor class.
        // The type validation is done this way to insure BC, ie. allowing subclasses of this class
        // to keep working even if they have the same method overridden with the original signature
        if (! $processor instanceof FieldTypeProcessorInterface) {
            throw new \TypeError('Argument 2 passed to ' . get_class($this) . '::registerProcessor() must be an' .
                ' instance of EzSystems\EzPlatformRest\FieldTypeProcessorInterface, instance of ' . get_class($processor) .
                ' given');
        }
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
     * @return \EzSystems\EzPlatformRest\FieldTypeProcessorInterface
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
