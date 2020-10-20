<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Output;

/**
 * Marker interface for ValueObjectVisitor implementations which doesn't generate output by them self
 * but rather wrap data and delegate output generation back to dispatcher.
 */
interface DelegateValueObjectVisitor
{
}
