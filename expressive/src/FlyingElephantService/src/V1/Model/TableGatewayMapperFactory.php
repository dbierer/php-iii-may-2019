<?php
/**
 * Table Gateway Mapper Factory Class
 */

namespace FlyingElephantService\V1\Model;

use DomainException;
use Interop\Container\ContainerInterface;

/**
 * Service factory for returning a Model\TableGatewayMapper instance.
 *
 * Requires the Model\TableGateway service be present in the service locator.
 */
class TableGatewayMapperFactory
{
    const PATH = 'Propulsion';
    /**
     * @param $services
     * @return TableGatewayMapper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!$container->has(static::PATH . '\TableGateway')) {
            throw new DomainException('Cannot create ' . TableGatewayMapper::class . '; missing ' . TableGateway::class . ' dependency');
        }

        return new TableGatewayMapper($container->get(static::PATH . '\TableGateway'));
    }
}
