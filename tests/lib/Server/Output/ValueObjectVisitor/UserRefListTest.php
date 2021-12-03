<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Core\Repository\Values\Content\Location;
use Ibexa\Core\Repository\Values\User\User;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\RestUser;
use Ibexa\Rest\Server\Values\UserRefList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class UserRefListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the UserRefList visitor.
     *
     * @return \DOMDocument
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $UserRefList = new UserRefList(
            [
                new RestUser(
                    new User(),
                    $this->getMockForAbstractClass(ContentType::class),
                    new ContentInfo(
                        [
                            'id' => 14,
                        ]
                    ),
                    new Location(),
                    []
                ),
            ],
            '/some/path'
        );

        $this->addRouteExpectation(
            'ezpublish_rest_loadUser',
            ['userId' => $UserRefList->users[0]->contentInfo->id],
            "/user/users/{$UserRefList->users[0]->contentInfo->id}"
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $UserRefList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        $dom = new \DOMDocument();
        $dom->loadXml($result);

        return $dom;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testUserRefListHrefCorrect(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/UserRefList[@href="/some/path"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testUserRefListMediaTypeCorrect(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/UserRefList[@media-type="application/vnd.ez.api.UserRefList+xml"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testUserHrefCorrect(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/UserRefList/User[@href="/user/users/14"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testUserMediaTypeCorrect(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/UserRefList/User[@media-type="application/vnd.ez.api.User+xml"]');
    }

    /**
     * Get the UserRefList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\UserRefList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\UserRefList();
    }
}

class_alias(UserRefListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\UserRefListTest');
