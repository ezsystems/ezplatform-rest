<?php

namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser\FacetBuilder;

use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Server\Input\Parser\ContentQuery as QueryParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\ContentTypeParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\CriterionParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\DateRangeParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\FieldParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\FieldRangeParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\LocationParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\SectionParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\TermParser;
use EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\UserParser;
use EzSystems\EzPlatformRest\Tests\Server\Input\Parser\BaseTest;

abstract class FacetBuilderBaseTest extends BaseTest
{
    /**
     * @return \EzSystems\EzPlatformRest\Input\ParsingDispatcher
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
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\ContentQuery
     */
    protected function internalGetParser()
    {
        return new QueryParser();
    }
}
