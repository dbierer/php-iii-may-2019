<?php
/**
 * Propulsion Systems Resource Factory
 * This is the class responsible for initializing the resource by injecting the mapper instance.
 */
namespace FlyingElephantService\V1\Rest\PropulsionSystems;
use Interop\Container\ContainerInterface;

class PropulsionSystemsResourceFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return PropulsionSystemsResource
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mapper = $container->get('Propulsion\Mapper');
        return new PropulsionSystemsResource($container->get('Propulsion\Mapper'));
    }
}