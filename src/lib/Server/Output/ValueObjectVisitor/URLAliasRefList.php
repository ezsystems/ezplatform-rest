<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * URLAliasRefList value object visitor.
 */
class URLAliasRefList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\URLAliasRefList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('UrlAliasRefList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('UrlAliasRefList'));

        $generator->startAttribute('href', $data->path);
        $generator->endAttribute('href');

        $generator->startList('UrlAlias');
        foreach ($data->urlAliases as $urlAlias) {
            $generator->startObjectElement('UrlAlias');

            $generator->startAttribute(
                'href',
                $this->router->generate('ezpublish_rest_loadURLAlias', ['urlAliasId' => $urlAlias->id])
            );
            $generator->endAttribute('href');

            $generator->endObjectElement('UrlAlias');
        }
        $generator->endList('UrlAlias');

        $generator->endObjectElement('UrlAliasRefList');
    }
}

class_alias(URLAliasRefList::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\URLAliasRefList');
