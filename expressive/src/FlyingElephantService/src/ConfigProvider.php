<?php
declare(strict_types=1);
namespace FlyingElephantService;

/**
 * The configuration provider for the FlyingElephantService module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
use FlyingElephantService\Handler;
use FlyingElephantService\Middleware;
use FlyingElephantService\V1\Model as FSM;
use FlyingElephantService\V1\Rest\PropulsionSystems as PPS;

class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
			'aliases' => [
				//'Mapper' => FSM\TableGateway::class,
			],
			'factories'  => [
				FSM\ArrayMapper::class => FSM\ArrayMapperFactory::class,
				FSM\TableGateway::class => FSM\TableGatewayFactory::class,
				FSM\TableGatewayMapper::class => FSM\TableGatewayMapperFactory::class,
				PPS\PropulsionSystemsResource::class => PPS\PropulsionSystemsResourceFactory::class,
				Handler\FlyingElephantHandler::class => Handler\FlyingElephantHandlerFactory::class,
				Middleware\UuidCheckMiddleware::class => Middleware\UuidCheckMiddlewareFactory::class,
				Middleware\AuthCheckMiddleware::class => Middleware\AuthCheckMiddlewareFactory::class,
			]
        ];
    }

}
