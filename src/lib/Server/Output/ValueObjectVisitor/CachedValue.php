<?php

/**
 * File containing the ContentList ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\MVC\Symfony\RequestStackAware;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;

/**
 * CachedValue value object visitor.
 */
class CachedValue extends ValueObjectVisitor
{
    use RequestStackAware;

    /** @var ConfigResolverInterface */
    protected $configResolver;

    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * @param Visitor   $visitor
     * @param Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\CachedValue $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $visitor->visitValueObject($data->value);

        if ($this->getParameter('content.view_cache') !== true) {
            return;
        }

        $response = $visitor->getResponse();
        $response->setPublic();
        $response->setVary('Accept');

        if ($this->getParameter('content.ttl_cache') === true) {
            $response->setSharedMaxAge($this->getParameter('content.default_ttl'));
            $request = $this->getCurrentRequest();
            if (isset($request) && $request->headers->has('X-User-Hash')) {
                $response->setVary('X-User-Hash', false);
            }
        }

        if (isset($data->cacheTags['locationId'])) {
            $visitor->getResponse()->headers->set('X-Location-Id', $data->cacheTags['locationId']);
        }
    }

    public function getParameter($parameterName, $defaultValue = null)
    {
        if ($this->configResolver->hasParameter($parameterName)) {
            return $this->configResolver->getParameter($parameterName);
        }

        return $defaultValue;
    }
}
