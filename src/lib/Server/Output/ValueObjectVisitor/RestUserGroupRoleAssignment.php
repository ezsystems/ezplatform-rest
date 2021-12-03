<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\User\Limitation\RoleLimitation;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * RestUserGroupRoleAssignment value object visitor.
 */
class RestUserGroupRoleAssignment extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\RestUserGroupRoleAssignment $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('RoleAssignment');
        $visitor->setHeader('Content-Type', $generator->getMediaType('RoleAssignment'));

        $roleAssignment = $data->roleAssignment;
        $role = $roleAssignment->getRole();

        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadRoleAssignmentForUserGroup',
                [
                    'groupPath' => trim($data->id, '/'),
                    'roleId' => $role->id,
                ]
            )
        );
        $generator->endAttribute('href');

        $roleLimitation = $roleAssignment->getRoleLimitation();
        if ($roleLimitation instanceof RoleLimitation) {
            $this->visitLimitation($generator, $roleLimitation);
        }

        $generator->startObjectElement('Role');
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadRole', ['roleId' => $role->id])
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Role');

        $generator->endObjectElement('RoleAssignment');
    }
}

class_alias(RestUserGroupRoleAssignment::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\RestUserGroupRoleAssignment');
