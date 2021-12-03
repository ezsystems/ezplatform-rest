<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\User\Policy as PolicyValue;
use Ibexa\Contracts\Core\Repository\Values\User\PolicyDraft;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * Policy value object visitor.
 */
class Policy extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param Policy|\Ibexa\Contracts\Core\Repository\Values\User\PolicyDraft $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('Policy');
        $visitor->setHeader('Content-Type', $generator->getMediaType($data instanceof PolicyDraft ? 'PolicyDraft' : 'Policy'));
        $visitor->setHeader('Accept-Patch', $generator->getMediaType('PolicyUpdate'));
        $this->visitPolicyAttributes($visitor, $generator, $data);
        $generator->endObjectElement('Policy');
    }

    protected function visitPolicyAttributes(Visitor $visitor, Generator $generator, PolicyValue $data)
    {
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadPolicy', ['roleId' => $data->roleId, 'policyId' => $data->id])
        );
        $generator->endAttribute('href');

        $generator->startValueElement('id', $data->id);
        $generator->endValueElement('id');

        if ($data instanceof PolicyDraft) {
            $generator->startValueElement('originalId', $data->originalId);
            $generator->endValueElement('originalId');
        }

        $generator->startValueElement('module', $data->module);
        $generator->endValueElement('module');

        $generator->startValueElement('function', $data->function);
        $generator->endValueElement('function');

        $limitations = $data->getLimitations();
        if (!empty($limitations)) {
            $generator->startHashElement('limitations');
            $generator->startList('limitation');

            foreach ($limitations as $limitation) {
                $this->visitLimitation($generator, $limitation);
            }

            $generator->endList('limitation');
            $generator->endHashElement('limitations');
        }
    }
}

class_alias(Policy::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Policy');
