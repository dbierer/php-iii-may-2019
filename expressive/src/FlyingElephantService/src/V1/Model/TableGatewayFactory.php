<?php
/**
 * Table Gateway Factory
 */

namespace FlyingElephantService\V1\Model;

use DomainException;
use Interop\Container\ContainerInterface;

/**
 * Service factory for the Model TableGateway
 *
 * If the "model" key is present, and either the "db" or "table" subkeys
 * are present and valid, uses those; otherwise, uses defaults of "Db\Model"
 * and "status", respectively.
 *
 * If the DB service does not exist, raises an error.
 *
 * Otherwise, creates a TableGateway instance with the DB service and table.
 */
class TableGatewayFactory
{
    const CONFIG_KEY = 'propulsion';
    const DB_KEY = 'db';
    const TABLE_KEY = 'table';
    const EXCEPTION_MSG = 'Cannot create Propulsion\TableGateway ';

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TableGateway
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $db    = 'db\flying_elephant';
        $table = 'propellant';
        if ($container->has('config')) {
            $config = $container->get('config');
            switch (isset($config[static::CONFIG_KEY])) {
                case true:
                    $config = $config[static::CONFIG_KEY];

                    if (array_key_exists(static::DB_KEY, $config) && !empty($config[static::DB_KEY])) {
                        $db = $config[static::DB_KEY];
                    }

                    if (array_key_exists(static::TABLE_KEY, $config) && !empty($config[static::TABLE_KEY])) {
                        $table = $config[static::TABLE_KEY];
                    }
                    break;
                case false:
                default:
                    break;
            }
        }

        if (!$container->has($db)) {
            throw new DomainException(sprintf(
                static::EXCEPTION_MSG . ' due to missing "%s" service',
                $db
            ));
        }

        return new TableGateway($table, $container->get($db));
    }
}
