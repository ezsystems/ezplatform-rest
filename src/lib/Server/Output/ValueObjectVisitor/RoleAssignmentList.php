<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\Rest\Server\Values;

/**
 * RoleAssignmentList value object visitor.
 */
class RoleAssignmentList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\RoleAssignmentList $data
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

class_alias(RoleAssignmentList::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\RoleAssignmentList');
