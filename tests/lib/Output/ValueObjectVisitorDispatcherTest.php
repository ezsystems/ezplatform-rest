<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Output;

use Error;
use Ibexa\Contracts\Rest\Output\Exceptions\InvalidTypeException;
use Ibexa\Contracts\Rest\Output\Exceptions\NoVisitorFoundException;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitorDispatcher;
use Ibexa\Contracts\Rest\Output\Visitor;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Visitor test.
 */
class ValueObjectVisitorDispatcherTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Ibexa\Contracts\Rest\Output\Visitor
     */
    private $outputVisitorMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Ibexa\Contracts\Rest\Output\Generator
     */
    private $outputGeneratorMock;

    public function testVisitValueObject()
    {
        $data = new stdClass();

        $visitor = $this->getValueObjectVisitorMock();
        $visitor
            ->expects($this->at(0))
            ->method('visit')
            ->with($this->getOutputVisitorMock(), $this->getOutputGeneratorMock(), $data);

        $valueObjectDispatcher = $this->getValueObjectDispatcher();
        $valueObjectDispatcher->addVisitor('stdClass', $visitor);

        $valueObjectDispatcher->visit($data);
    }

    public function testVisitValueObjectInvalidType()
    {
        $this->expectException(InvalidTypeException::class);

        $this->getValueObjectDispatcher()->visit(42);
    }

    public function testVisitValueObjectNoMatch()
    {
        $this->expectException(NoVisitorFoundException::class);

        $dispatcher = $this->getValueObjectDispatcher();

        $dispatcher->visit(new stdClass());
    }

    public function testVisitValueObjectParentMatch()
    {
        $data = new ValueObject();

        $valueObjectVisitor = $this->getValueObjectVisitorMock();
        $valueObjectVisitor
            ->expects($this->at(0))
            ->method('visit')
            ->with($this->getOutputVisitorMock(), $this->getOutputGeneratorMock(), $data);

        $dispatcher = $this->getValueObjectDispatcher();
        $dispatcher->addVisitor('stdClass', $valueObjectVisitor);

        $dispatcher->visit($data);
    }

    public function testVisitValueObjectSecondRuleParentMatch()
    {
        $data = new ValueObject();

        $valueObjectVisitor1 = $this->getValueObjectVisitorMock();
        $valueObjectVisitor2 = $this->getValueObjectVisitorMock();

        $dispatcher = $this->getValueObjectDispatcher();
        $dispatcher->addVisitor('WontMatch', $valueObjectVisitor1);
        $dispatcher->addVisitor('stdClass', $valueObjectVisitor2);

        $valueObjectVisitor1
            ->expects($this->never())
            ->method('visit');

        $valueObjectVisitor2
            ->expects($this->at(0))
            ->method('visit')
            ->with($this->getOutputVisitorMock(), $this->getOutputGeneratorMock(), $data);

        $dispatcher->visit($data);
    }

    public function testVisitError(): void
    {
        $this->expectException(Error::class);

        $dispatcher = $this->getValueObjectDispatcher();
        $dispatcher->visit($this->createMock(Error::class));
    }

    /**
     * @return \Ibexa\Contracts\Rest\Output\ValueObjectVisitorDispatcher
     */
    private function getValueObjectDispatcher()
    {
        $dispatcher = new ValueObjectVisitorDispatcher();
        $dispatcher->setOutputGenerator($this->getOutputGeneratorMock());
        $dispatcher->setOutputVisitor($this->getOutputVisitorMock());

        return $dispatcher;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Ibexa\Contracts\Rest\Output\ValueObjectVisitor
     */
    private function getValueObjectVisitorMock()
    {
        return $this->getMockForAbstractClass(ValueObjectVisitor::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Ibexa\Contracts\Rest\Output\Visitor
     */
    private function getOutputVisitorMock()
    {
        if (!isset($this->outputVisitorMock)) {
            $this->outputVisitorMock = $this->createMock(Visitor::class);
        }

        return $this->outputVisitorMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Ibexa\Contracts\Rest\Output\Generator
     */
    private function getOutputGeneratorMock()
    {
        if (!isset($this->outputGeneratorMock)) {
            $this->outputGeneratorMock = $this->createMock(Generator::class);
        }

        return $this->outputGeneratorMock;
    }
}

class_alias(ValueObjectVisitorDispatcherTest::class, 'EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorDispatcherTest');
