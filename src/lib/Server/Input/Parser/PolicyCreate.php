<?php

/**
 * File containing the PolicyCreate parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Input\ParserTools;
use EzSystems\EzPlatformRest\Exceptions;
use eZ\Publish\API\Repository\RoleService;

/**
 * Parser for PolicyCreate.
 */
class PolicyCreate extends BaseParser
{
    /**
     * Role service.
     *
     * @var \eZ\Publish\API\Repository\RoleService
     */
    protected $roleService;

    /**
     * Parser tools.
     *
     * @var \EzSystems\EzPlatformRest\Input\ParserTools
     */
    protected $parserTools;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\RoleService $roleService
     * @param \EzSystems\EzPlatformRest\Input\ParserTools $parserTools
     */
    public function __construct(RoleService $roleService, ParserTools $parserTools)
    {
        $this->roleService = $roleService;
        $this->parserTools = $parserTools;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \eZ\Publish\API\Repository\Values\User\PolicyCreateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('module', $data)) {
            throw new Exceptions\Parser("Missing 'module' attribute for PolicyCreate.");
        }

        if (!array_key_exists('function', $data)) {
            throw new Exceptions\Parser("Missing 'function' attribute for PolicyCreate.");
        }

        $policyCreate = $this->roleService->newPolicyCreateStruct($data['module'], $data['function']);

        // @todo XSD says that limitations is mandatory,
        // but polices can be created without limitations
        if (array_key_exists('limitations', $data)) {
            if (!is_array($data['limitations'])) {
                throw new Exceptions\Parser("Invalid format for 'limitations' in PolicyCreate.");
            }

            if (!isset($data['limitations']['limitation']) || !is_array($data['limitations']['limitation'])) {
                throw new Exceptions\Parser("Invalid format for 'limitations' in PolicyCreate.");
            }

            foreach ($data['limitations']['limitation'] as $limitationData) {
                $policyCreate->addLimitation(
                    $this->parserTools->parseLimitation($limitationData)
                );
            }
        }

        return $policyCreate;
    }
}
