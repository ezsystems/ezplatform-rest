<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Rest\Functional;

use JsonSchema\Validator;

trait ResourceAssertionsTrait
{
    final protected static function assertResponseMatchesXmlSnapshot(string $content, ?string $file = null): void
    {
        self::assertStringMatchesSnapshot($content, 'xml', $file);
    }

    final protected static function assertResponseMatchesJsonSnapshot(string $content, ?string $file = null): void
    {
        self::assertStringMatchesSnapshot($content, 'json', $file);
    }

    /**
     * @phpstan-param "xml"|"json"|null $type
     */
    final protected static function assertStringMatchesSnapshot(
        string $content,
        ?string $type = null,
        ?string $file = null
    ): void {
        $file ??= self::getDefaultSnapshotFileLocation($type);

        self::checkSnapshotFileExistence($file, $content);

        if ($type === 'xml') {
            self::assertXmlStringEqualsXmlFile($file, $content);
        } elseif ($type === 'json') {
            self::assertJsonStringEqualsJsonFile($file, $content);
        } else {
            self::assertStringEqualsFile($file, $content);
        }
    }

    /**
     * @throws \JsonException
     */
    final protected static function assertJsonResponseIsValid(string $response, string $resourceType): void
    {
        self::assertJson($response);
        self::assertStringContainsString($resourceType, $response);
        self::validateAgainstJSONSchema($response, $resourceType);
    }

    /**
     * @throws \JsonException
     */
    final protected static function validateAgainstJSONSchema(string $data, string $resource): void
    {
        $validator = new Validator();
        $decodedData = json_decode($data, false, 512, JSON_THROW_ON_ERROR);
        $schemaReference = [
            '$ref' => 'file://' . self::getSchemaFileLocation($resource),
        ];

        $validator->validate($decodedData, $schemaReference);

        self::assertTrue($validator->isValid(), self::convertErrorsToString($validator, $data));
    }

    private static function convertErrorsToString(Validator $validator, string $data): string
    {
        $errorMessage = '';
        foreach ($validator->getErrors() as $error) {
            $errorMessage .= sprintf(
                "property: [%s], constraint: %s, error: %s\n",
                $error['property'],
                $error['constraint'],
                $error['message']
            );
        }

        $errorMessage .= "\n\nFor data:\n\n" . $data;

        return $errorMessage;
    }

    private static function getSchemaFileLocation(string $resource): string
    {
        return __DIR__ . '/JsonSchema/' . $resource . '.json';
    }

    private static function checkSnapshotFileExistence(string $file, string $content): void
    {
        if (file_exists($file)) {
            return;
        }

        if ($_ENV['IBEXA_REST_GENERATE_SNAPSHOTS'] ?? false) {
            file_put_contents($file, rtrim($content, "\n") . "\n");

            return;
        }

        self::fail(sprintf(
            'File %s does not exist. If it\'s a new REST route, add environment variable "%s" to phpunit.xml '
            . '(or environment) set to truthy value to enable snapshot generation.',
            $file,
            'IBEXA_REST_GENERATE_SNAPSHOTS',
        ));
    }

    private static function getDefaultSnapshotFileLocation(?string $type): string
    {
        $classInfo = new \ReflectionClass(static::class);
        $class = substr(static::class, strrpos(static::class, '\\') + 1);
        $classFilename = $classInfo->getFileName();
        self::assertNotFalse($classFilename);

        return dirname($classFilename) . '/_snapshot/' . $class . '.' . ($type ?? 'log');
    }
}
