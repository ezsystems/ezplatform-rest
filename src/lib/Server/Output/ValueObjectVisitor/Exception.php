<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\Core\Base\Translatable;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Exception value object visitor.
 */
class Exception extends ValueObjectVisitor
{
    /**
     * Is debug mode enabled?
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Mapping of HTTP status codes to their respective error messages.
     *
     * @var array
     */
    protected static $httpStatusCodes = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => "I'm a teapot",
        421 => 'There are too many connections from your internet address',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
    ];

    /** @var \Symfony\Contracts\Translation\TranslatorInterface */
    protected $translator;

    /**
     * Construct from debug flag.
     *
     * @param bool $debug
     * @param \Symfony\Contracts\Translation\TranslatorInterface|null $translator
     */
    public function __construct($debug = false, ?TranslatorInterface $translator = null)
    {
        $this->debug = (bool)$debug;
        $this->translator = $translator;
    }

    /**
     * Returns HTTP status code.
     *
     * @return int
     */
    protected function getStatus()
    {
        return 500;
    }

    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Exception $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ErrorMessage');

        $visitor->setHeader('Content-Type', $generator->getMediaType('ErrorMessage'));

        $statusCode = $this->generateErrorCode($generator, $visitor, $data);

        $generator->valueElement(
            'errorMessage',
            static::$httpStatusCodes[$statusCode] ?? static::$httpStatusCodes[500]
        );

        if ($this->debug || $statusCode < 500) {
            $errorDescription = $data instanceof Translatable && $this->translator
                ? /** @Ignore */ $this->translator->trans($data->getMessageTemplate(), $data->getParameters(), 'repository_exceptions')
                : $data->getMessage();
        } else {
            // Do not leak any file paths and sensitive data on production environments
            $errorDescription = $this->translator
                ? /** @Desc("An error has occurred. Please try again later or contact your Administrator.") */ $this->translator->trans('non_verbose_error', [], 'repository_exceptions')
                : 'An error has occurred. Please try again later or contact your Administrator.';
        }

        $generator->valueElement('errorDescription', $errorDescription);

        if ($this->debug) {
            $generator->valueElement('trace', $data->getTraceAsString());
            $generator->valueElement('file', $data->getFile());
            $generator->valueElement('line', $data->getLine());
        }

        if ($previous = $data->getPrevious()) {
            $generator->startObjectElement('Previous', 'ErrorMessage');
            $visitor->visitValueObject($previous);
            $generator->endObjectElement('Previous');
        }

        $generator->endObjectElement('ErrorMessage');
    }

    protected function generateErrorCode(Generator $generator, Visitor $visitor, \Exception $e): int
    {
        $statusCode = $this->getStatus();
        $visitor->setStatus($statusCode);

        $generator->valueElement('errorCode', $statusCode);

        return $statusCode;
    }
}

class_alias(Exception::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Exception');
