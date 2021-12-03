<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\URLWildcard as URLWildcardValue;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * URLWildcard value object visitor.
 */
class URLWildcard extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\URLWildcard $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $visitor->setHeader('Content-Type', $generator->getMediaType('UrlWildcard'));
        $generator->startObjectElement('UrlWildcard');
        $this->visitURLWildcardAttributes($visitor, $generator, $data);
        $generator->endObjectElement('UrlWildcard');
    }

    protected function visitURLWildcardAttributes(Visitor $visitor, Generator $generator, URLWildcardValue $data)
    {
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadURLWildcard', ['urlWildcardId' => $data->id])
        );
        $generator->endAttribute('href');

        $generator->startAttribute('id', $data->id);
        $generator->endAttribute('id');

        $generator->startValueElement('sourceUrl', $data->sourceUrl);
        $generator->endValueElement('sourceUrl');

        $generator->startValueElement('destinationUrl', $data->destinationUrl);
        $generator->endValueElement('destinationUrl');

        $generator->startValueElement(
            'forward',
            $this->serializeBool($generator, $data->forward)
        );
        $generator->endValueElement('forward');
    }
}

class_alias(URLWildcard::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\URLWildcard');
