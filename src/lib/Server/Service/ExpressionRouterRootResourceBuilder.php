<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Service;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\EzPlatformRest\Values;
use EzSystems\EzPlatformRest\Values\Root;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ExpressionRouterRootResourceBuilder.
 *
 * This class builds a Root from an array building the href's using ExpressionLanguage
 * to build href's from the router or templateRouter.
 *
 * Example $resourceConfig structure:
 *
 * array(
 *      'content' => array(
 *          'mediaType' => '',
 *          'href' => 'router.generate("ezpublish_rest_listContentTypes")',
 *      ),
 *      'usersByRoleId' => array(
 *          'mediaType' => 'UserRefList',
 *          'href' => 'templateRouter.generate("ezpublish_rest_loadUsers", {roleId: "{roleId}"})',
 *      ),
 * )
 */
class ExpressionRouterRootResourceBuilder implements RootResourceBuilderInterface
{
    /** @var \Symfony\Component\Routing\RouterInterface */
    protected $router;

    /** @var \Symfony\Component\Routing\RouterInterface */
    protected $templateRouter;

    /** @var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    protected $configResolver;

    public function __construct(RouterInterface $router, RouterInterface $templateRouter, ConfigResolverInterface $configResolver)
    {
        $this->router = $router;
        $this->templateRouter = $templateRouter;
        $this->configResolver = $configResolver;
    }

    /**
     * Build root resource.
     *
     * @return array|\EzSystems\EzPlatformRest\Values\Root
     */
    public function buildRootResource(): Root
    {
        $language = new ExpressionLanguage();

        $resources = [];
        foreach ($this->configResolver->getParameter('rest_root_resources') as $name => $resource) {
            $resources[] = new Values\Resource(
                $name,
                $resource['mediaType'],
                $language->evaluate($resource['href'], [
                    'router' => $this->router,
                    'templateRouter' => $this->templateRouter,
                ])
            );
        }

        return new Root($resources);
    }
}
