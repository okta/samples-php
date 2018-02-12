<?php

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__ . '/views');
$twig = new Twig_Environment($loader);


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($twig) {
    $r->get('/', function() use ($twig) {
        echo $twig->render('index.twig');
    });
});

$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
if($routeInfo[0] == FastRoute\Dispatcher::NOT_FOUND || $routeInfo[0] == FastRoute\Dispatcher::METHOD_NOT_ALLOWED) {
    die('Something Happened!');
}

return $routeInfo[1]($routeInfo[2]);