<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\User\Policy;
use Ibexa\Core\Repository\Values\User;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\PolicyList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class PolicyListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the PolicyList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $policyList = new PolicyList([], '/user/roles/42/policies');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $policyList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains PolicyList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsPolicyListElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'PolicyList',
            ],
            $result,
            'Invalid <PolicyList> element.',
            false
        );
    }

    /**
     * Test if result contains PolicyList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsPolicyListAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'PolicyList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.PolicyList+xml',
                    'href' => '/user/roles/42/policies',
                ],
            ],
            $result,
            'Invalid <PolicyList> attributes.',
            false
        );
    }

    /**
     * Test if PolicyList visitor visits the children.
     */
    public function testPolicyListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $policyList = new PolicyList(
            [
                new User\Policy(),
                new User\Policy(),
            ],
            42
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(Policy::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $policyList
        );
    }

    /**
     * Get the PolicyList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\PolicyList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\PolicyList();
    }
}

class_alias(PolicyListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\PolicyListTest');
