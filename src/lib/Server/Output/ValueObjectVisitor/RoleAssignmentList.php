<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Server\Values;

/**
 * RoleAssignmentList value object visitor.
 */
class RoleAssignmentList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\RoleAssignmentList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('RoleAssignmentList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('RoleAssignmentList'));

        $generator->startAttribute(
            'href',
            $data->isGroupAssignment ?
                $this->router->generate('ezpublish_rest_loadRoleAssignmentsForUserGroup', ['groupPath' => $data->id]) :
                $this->router->generate('ezpublish_rest_loadRoleAssignmentsForUser', ['userId' => $data->id])
        );
        $generator->endAttribute('href');

        $generator->startList('RoleAssignment');
        foreach ($data->roleAssignments as $roleAssignment) {
            $visitor->visitValueObject(
                $data->isGroupAssignment ?
                    new Values\RestUserGroupRoleAssignment($roleAssignment, $data->id) :
                    new Values\RestUserRoleAssignment($roleAssignment, $data->id)
            );
        }
        $generator->endList('RoleAssignment');

        $generator->endObjectElement('RoleAssignmentList');
    }
}
