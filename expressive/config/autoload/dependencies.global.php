<?php

declare(strict_types=1);

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,
        ],
        'services' => [
			'zf-mvc-auth' => [
				'authorization' => [
					'FlyingElephantService\\V1\\Rest\\PropulsionSystems\\PropulsionSystemsResource' => [
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
        ],
    ],
	'propulsion' => [
		'db' => 'flying-elephant-db',
		'table' => 'propellant',
		'array_mapper_path' => 'data/propulsion.php'
	],
];
