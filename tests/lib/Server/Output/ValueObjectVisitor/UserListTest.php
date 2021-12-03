<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Core\Repository\Values\Content\Content;
use Ibexa\Core\Repository\Values\Content\Location;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\RestUser;
use Ibexa\Rest\Server\Values\UserList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class UserListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the UserList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $userList = new UserList([], '/some/path');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $userList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains UserList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsUserListElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'UserList',
            ],
            $result,
            'Invalid <UserList> element.',
            false
        );
    }

    /**
     * Test if result contains UserList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsUserListAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'UserList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.UserList+xml',
                    'href' => '/some/path',
                ],
            ],
            $result,
            'Invalid <UserList> attributes.',
            false
        );
    }

    /**
     * Test if UserList visitor visits the children.
     */
    public function testUserListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $userList = new UserList(
            [
                new RestUser(
                    new Content(
                        [
                            'internalFields' => [],
                        ]
                    ),
                    $this->getMockForAbstractClass(ContentType::class),
                    new ContentInfo(),
                    new Location(),
                    []
                ),
                new RestUser(
                    new Content(
                        [
                            'internalFields' => [],
                        ]
                    ),
                    $this->getMockForAbstractClass(ContentType::class),
                    new ContentInfo(),
                    new Location(),
                    []
                ),
            ],
            '/some/path'
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(RestUser::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $userList
        );
    }

    /**
     * Get the UserList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\UserList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\UserList();
    }
}

class_alias(UserListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\UserListTest');
