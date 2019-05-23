<?php
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Validator\NotEmpty;
use Zend\Filter\StripTags;
use Zend\Hydrator\ObjectProperty;
use FlyingElephantService\V1\Rest\PropulsionSystems\{
    PropulsionSystemsResource,
    PropulsionSystemsResourceFactory,
    PropulsionSystemsEntity,
    PropulsionSystemsCollection,
};
use FlyingElephantService\V1\Model\{
    ArrayMapperFactory,
    TableGatewayFactory,
    TableGatewayMapperFactory,
};
use FlyingElephantService\V1\Adapter\Factory\{AdapterFactory};
return [
    'propulsion' => [
        'db' => 'flying-elephant-db',
        'table' => 'propellant',
        'array_mapper_path' => 'data/propulsion.php'
    ],
    'service_manager' => [
        'services' => [
            'flying_elephant_adapter_config' => [
                'driver' => \PDO::class,
                'dsn' => 'mysql:host=localhost;dbname=flying_elephant',
                'username' => 'vagrant',
                'password' => 'vagrant',
                'options' => [
                    3 => 2,
                ],
            ],
        ],
        'aliases' => [
            'Propulsion\\Mapper' => 'Propulsion\\TableGatewayMapper',
//            'Propulsion\\Mapper' => 'Propulsion\\ArrayMapper',
            'flying-elephant-db' => AdapterFactory::class,
        ],
        'factories' => [
            PropulsionSystemsResource::class => PropulsionSystemsResourceFactory::class,
            'Propulsion\\ArrayMapper' => ArrayMapperFactory::class,
            'Propulsion\\TableGatewayMapper' => TableGatewayMapperFactory::class,
            'Propulsion\\TableGateway' => TableGatewayFactory::class,
            PropulsionSystemsEntity::class => InvokableFactory::class,
            PropulsionSystemsCollection::class => InvokableFactory::class,
            AdapterFactory::class => AdapterFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'fes-rest-propulsion-systems' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/propulsion-systems[/:propulsion_systems_id]',
                    'defaults' => [
                        'controller' => 'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'fes-rest-propulsion-systems',
        ],
    ],
    'zf-rest' => [
        'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Controller' => [
            'listener' => PropulsionSystemsResource::class,
            'route_name' => 'fes-rest-propulsion-systems',
            'route_identifier_name' => 'propulsion_systems_id',
            'collection_name' => 'propulsion_systems',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => PropulsionSystemsEntity::class,
            'collection_class' => PropulsionSystemsCollection::class,
            'service_name' => 'PropulsionSystems',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Controller' => [
                0 => 'application/vnd.flying-elephant-service.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Controller' => [
                0 => 'application/vnd.flying-elephant-service.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            PropulsionSystemsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'fes-rest-propulsion-systems',
                'route_identifier_name' => 'propulsion_systems_id',
                'hydrator' => ObjectProperty::class,
            ],
            PropulsionSystemsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'fes-rest-propulsion-systems',
                'route_identifier_name' => 'propulsion_systems_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-content-validation' => [
        'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Controller' => [
            'input_filter' => 'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => NotEmpty::class,
                        'options' => [],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'type',
                'description' => 'Volatile chemical',
                'field_type' => 'string',
                'error_message' => 'A type is required',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => NotEmpty::class,
                        'options' => [],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'propellant',
                'description' => 'The thrust component',
                'field_type' => 'string',
                'error_message' => 'A propellant is required',
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
            ],
        ],
    ],
];
