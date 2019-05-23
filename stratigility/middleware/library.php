<?php
// stratigility middleware "library"

// class needed
use Zend\Diactoros\Response;

// init constants
define('LOG_FILE', __DIR__ . '/../logs/access.log');
define('MENU', '<a href="/">Home Page</a><br><a href="/page/1">Page 1</a><br><a href="/page/2">Page 2</a><br><a href="/json">JSON</a><br><a href="/view">View Log</a>');

// order in which middleware pages should be attached to the pipe
$order = ['log','accept','page','json','view','home'];
$response = new Response();

$middleware = [
    // middleware: writes to a log file; does not return a response
    'log' => [
        'path' => FALSE,
        'func' => function ($req, $handler) use ($response) {
            $text = sprintf('%20s : %10s : %16s : %s' . PHP_EOL,
                            date('Y-m-d H:i:s'),
                            $req->getUri()->getPath(),
                            ($req->getHeaders()['accept'][0] ?? 'N/A'),
                            ($req->getServerParams()['REMOTE_ADDR']) ?? 'Command Line');
            file_put_contents(LOG_FILE, $text, FILE_APPEND);
            return $handler->handle($req);
        }
    ],
    // middleware: sets "Content-Type" to JSON if "Accept" header is "application/json" 
    'accept' => [
        'path' => FALSE,
        'func' => function ($req, $handler) use ($response) {
			$accept = $req->getHeaders()['accept'][0];
			if (strpos($accept, 'application/json') !== FALSE) {
				header('Content-Type: application/json');
			}	
            return $handler->handle($req);
        }
    ],
    // middleware: outputs JSON; returns a response
    'json' => [
        'path' => '/json',
        'func' => function ($req, $handler) use ($response) {
			$data = ['A' => 'This is line 1', 'B' => 'This is line 2', 'C' => 'This is line 3'];
            $response->getBody()->write(json_encode($data));
            return $response;
        }
    ],
    // middleware: page 1 and 2; returns a response
    'page' => [
        'path' => '/page',
        'func' => function ($req, $handler) use ($response) {
            $path = $req->getUri()->getPath();
            $page = preg_replace('/[^0-9]/', '', $path);
            $response->getBody()->write('<h1>Page ' . $page . '</h1>' . MENU);
            return $response;
        }
    ],
    // middleware: view log page; returns a response
    'view' => [
        'path' => '/view',
        'func' => function ($req, $handler) use ($response) {
            $contents = file_get_contents(LOG_FILE);
            $response->getBody()->write('<h1>View Access Log</h1><pre>' . $contents . '</pre>' . MENU);
            return $response;
        }
    ],
    // middleware: home page; returns a response
    'home' => [
        'path' => '/',
        'func' => function ($req, $handler) use ($response) {
            if (! in_array($req->getUri()->getPath(), ['/', ''], true)) {
                return $handler->handle($req);
            }
            $response->getBody()->write('<h1>Home Page</h1>' . MENU);
            return $response;
        }
    ]
];

