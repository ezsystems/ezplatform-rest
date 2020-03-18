<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Output;

use EzSystems\EzPlatformRest;
use EzSystems\EzPlatformRest\Output\Exceptions\InvalidTypeException;
use EzSystems\EzPlatformRest\Output\Exceptions\NoVisitorFoundException;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use stdClass;
use PHPUnit\Framework\TestCase;

/**
 * Visitor test.
 */
class ValueObjectVisitorDispatcherTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|Common\Output\Visitor
     */
    private $outputVisitorMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|Common\Output\Generator
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

    /**
     * @return Common\Output\ValueObjectVisitorDispatcher
     */
    private function getValueObjectDispatcher()
    {
        $dispatcher = new EzPlatformRest\Output\ValueObjectVisitorDispatcher();
        $dispatcher->setOutputGenerator($this->getOutputGeneratorMock());
        $dispatcher->setOutputVisitor($this->getOutputVisitorMock());

        return $dispatcher;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\EzSystems\EzPlatformRest\Output\ValueObjectVisitor
     */
    private function getValueObjectVisitorMock()
    {
        return $this->getMockForAbstractClass(ValueObjectVisitor::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|Common\Output\Visitor
     */
    private function getOutputVisitorMock()
    {
        if (!isset($this->outputVisitorMock)) {
            $this->outputVisitorMock = $this->createMock(Visitor::class);
        }

        return $this->outputVisitorMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|Common\Output\Generator
     */
    private function getOutputGeneratorMock()
    {
        if (!isset($this->outputGeneratorMock)) {
            $this->outputGeneratorMock = $this->createMock(Generator::class);
        }

        return $this->outputGeneratorMock;
    }
}
