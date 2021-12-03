<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Output;

/**
 * Marker interface for ValueObjectVisitor implementations which doesn't generate output by them self
 * but rather wrap data and delegate output generation back to dispatcher.
 */
interface DelegateValueObjectVisitor
{
}

class_alias(DelegateValueObjectVisitor::class, 'EzSystems\EzPlatformRest\Output\DelegateValueObjectVisitor');
