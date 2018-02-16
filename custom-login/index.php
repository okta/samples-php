<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->get('/', function() {
        print 'hello';
    });
});

$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
if($routeInfo[0] == FastRoute\Dispatcher::NOT_FOUND || $routeInfo[0] == FastRoute\Dispatcher::METHOD_NOT_ALLOWED) {
    die('Something Happened!');
}

return $routeInfo[1]($routeInfo[2]);