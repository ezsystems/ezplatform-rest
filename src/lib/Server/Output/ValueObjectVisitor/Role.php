<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\User\RoleDraft;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * Role value object visitor.
 */
class Role extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param Role|\Ibexa\Contracts\Core\Repository\Values\User\RoleDraft $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('Role');
        $visitor->setHeader('Content-Type', $generator->getMediaType($data instanceof RoleDraft ? 'RoleDraft' : 'Role'));
        $visitor->setHeader('Accept-Patch', $generator->getMediaType('RoleInput'));
        $this->visitRoleAttributes($visitor, $generator, $data);
        $generator->endObjectElement('Role');
    }

    protected function visitRoleAttributes(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadRole', ['roleId' => $data->id])
        );
        $generator->endAttribute('href');

        $generator->startValueElement('identifier', $data->identifier);
        $generator->endValueElement('identifier');

        $generator->startObjectElement('Policies', 'PolicyList');
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadPolicies', ['roleId' => $data->id])
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Policies');
    }
}

class_alias(Role::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Role');
