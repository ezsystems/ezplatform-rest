<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\User\Role;
use Ibexa\Core\Repository\Values\User;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\RoleList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class RoleListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the RoleList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $roleList = new RoleList([], '/user/roles');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $roleList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains RoleList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsRoleListElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'RoleList',
            ],
            $result,
            'Invalid <RoleList> element.',
            false
        );
    }

    /**
     * Test if result contains RoleList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsRoleListAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'RoleList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.RoleList+xml',
                    'href' => '/user/roles',
                ],
            ],
            $result,
            'Invalid <RoleList> attributes.',
            false
        );
    }

    /**
     * Test if RoleList visitor visits the children.
     */
    public function testRoleListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $roleList = new RoleList(
            [
                new User\Role(),
                new User\Role(),
            ],
            '/user/roles'
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(Role::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $roleList
        );
    }

    /**
     * Get the RoleList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\RoleList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\RoleList();
    }
}

class_alias(RoleListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\RoleListTest');
