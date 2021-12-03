<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\Rest\Output\DelegateValueObjectVisitor;
use Ibexa\Rest\Server\Values\RestUser;

final class User extends ValueObjectVisitor implements DelegateValueObjectVisitor
{
    /** @var \Ibexa\Contracts\Core\Repository\ContentService */
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\User\User $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $visitor->visitValueObject(
            new RestUser(
                $data,
                $data->getContentType(),
                $data->contentInfo,
                $data->contentInfo->getMainLocation(),
                $this->contentService->loadRelations(
                    $data->getVersionInfo()
                )
            )
        );
    }
}

class_alias(User::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\User');
