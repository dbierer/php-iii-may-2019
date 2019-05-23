<?php

declare(strict_types=1);

namespace FlyingElephantService\Handler;

use Psr\Container\ContainerInterface;
use FlyingElephantService\V1\Rest\PropulsionSystems\PropulsionSystemsResource;

class FlyingElephantHandlerFactory
{
    public function __invoke(ContainerInterface $container) : FlyingElephantHandler
    {
        return new FlyingElephantHandler($container->get(PropulsionSystemsResource::class));
    }
}
