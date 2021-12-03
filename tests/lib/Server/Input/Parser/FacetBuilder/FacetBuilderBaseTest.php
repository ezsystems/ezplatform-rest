<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser\FacetBuilder;

use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Server\Input\Parser\ContentQuery as QueryParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\ContentTypeParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\CriterionParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\DateRangeParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\FieldParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\FieldRangeParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\LocationParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\SectionParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\TermParser;
use Ibexa\Rest\Server\Input\Parser\FacetBuilder\UserParser;
use Ibexa\Tests\Rest\Server\Input\Parser\BaseTest;

abstract class FacetBuilderBaseTest extends BaseTest
{
    /**
     * @return \Ibexa\Contracts\Rest\Input\ParsingDispatcher
     */
    protected function getParsingDispatcher()
    {
        $parsingDispatcher = new ParsingDispatcher();

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.ContentType',
            new ContentTypeParser()
        );

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.Criterion',
            new CriterionParser()
        );

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.DateRange',
            new DateRangeParser()
        );

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.Field',
            new FieldParser()
        );

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.FieldRange',
            new FieldRangeParser()
        );

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.Location',
            new LocationParser()
        );

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.Section',
            new SectionParser()
        );

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.Term',
            new TermParser()
        );

        $parsingDispatcher->addParser(
            'application/vnd.ez.api.internal.facetbuilder.User',
            new UserParser()
        );

        return $parsingDispatcher;
    }

    /**
     * Returns the query parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\ContentQuery
     */
    protected function internalGetParser()
    {
        return new QueryParser();
    }
}

class_alias(FacetBuilderBaseTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\FacetBuilder\FacetBuilderBaseTest');
