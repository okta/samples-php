<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->get('/', function() {
        print "Hello!  There's not much to see here :) Please grab one of our front-end samples for use with this sample resource server";
    });

    $r->get('/secure', function() {
        $verifiedJwt = authenticate();

        print $verifiedJwt->getJwt();
    });

    $r->addRoute('OPTIONS', '/api/messages', function() {
        print '';
    });
    $r->get('/api/messages', function() {
        authenticate(); // will die here if not authenticated.

        print json_encode(
            [
                "messages" => [
                    [
                        "date" => new DateTime(),
                        "text" => "I am a robot."
                    ],
                    [
                        "date" => new DateTime(time() - 3600),
                        "text" => "Hello, World!"
                    ]
                ]
            ]
        );
    });
});

function authenticate() {

    try {
        switch(true) {
            case array_key_exists('HTTP_AUTHORIZATION', $_SERVER) :
                $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
                break;
            case array_key_exists('Authorization', $_SERVER) :
                $authHeader = $_SERVER['Authorization'];
                break;
            default :
                $authHeader = null;
                break;
        }
        preg_match('/Bearer\s(\S+)/', $authHeader, $matches);

        if(!isset($matches[1])) {
            throw new \Exception('No Bearer Token');
        }

        $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
            ->setIssuer(getenv('ISSUER'))
            ->setAudience('api://default')
            ->setClientId(getenv('CLIENT_ID'))
            ->build();

        return $jwtVerifier->verify($matches[1]);
    } catch (\Exception $e) {
        http_response_code('401');
        die('Unauthorized');
    }
}

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        die('Not Found');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        die('Not Allowed');
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        print $handler($vars);
        break;
}