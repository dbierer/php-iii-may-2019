<?php
return [
    'service_manager' => [
        'factories' => [
            \Test\V1\Rest\TestRest\TestRestResource::class => \Test\V1\Rest\TestRest\TestRestResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'test.rest.test-rest' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/test-rest[/:test_rest_id]',
                    'defaults' => [
                        'controller' => 'Test\\V1\\Rest\\TestRest\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'test.rest.test-rest',
        ],
    ],
    'zf-rest' => [
        'Test\\V1\\Rest\\TestRest\\Controller' => [
            'listener' => \Test\V1\Rest\TestRest\TestRestResource::class,
            'route_name' => 'test.rest.test-rest',
            'route_identifier_name' => 'test_rest_id',
            'collection_name' => 'test_rest',
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
            'entity_class' => \Test\V1\Rest\TestRest\TestRestEntity::class,
            'collection_class' => \Test\V1\Rest\TestRest\TestRestCollection::class,
            'service_name' => 'TestRest',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Test\\V1\\Rest\\TestRest\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'Test\\V1\\Rest\\TestRest\\Controller' => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Test\\V1\\Rest\\TestRest\\Controller' => [
                0 => 'application/vnd.test.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Test\V1\Rest\TestRest\TestRestEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'test.rest.test-rest',
                'route_identifier_name' => 'test_rest_id',
                'hydrator' => \Zend\Hydrator\ClassMethodsHydrator::class,
            ],
            \Test\V1\Rest\TestRest\TestRestCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'test.rest.test-rest',
                'route_identifier_name' => 'test_rest_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-content-validation' => [
        'Test\\V1\\Rest\\TestRest\\Controller' => [
            'input_filter' => 'Test\\V1\\Rest\\TestRest\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'Test\\V1\\Rest\\TestRest\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'username',
                'description' => 'name used to identify this user',
                'field_type' => 'string',
            ],
        ],
    ],
];
