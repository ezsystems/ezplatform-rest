<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use eZ\Publish\API\Repository\ContentService;
use EzSystems\EzPlatformRest\Output\DelegateValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Server\Values\RestUser;

final class User extends ValueObjectVisitor implements DelegateValueObjectVisitor
{
    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\User\User $data
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
