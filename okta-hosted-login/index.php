<?php
/******************************************************************************
 * Copyright 2018 Okta, Inc.                                                  *
 *                                                                            *
 * Licensed under the Apache License, Version 2.0 (the "License");            *
 * you may not use this file except in compliance with the License.           *
 * You may obtain a copy of the License at                                    *
 *                                                                            *
 *      http://www.apache.org/licenses/LICENSE-2.0                            *
 *                                                                            *
 * Unless required by applicable law or agreed to in writing, software        *
 * distributed under the License is distributed on an "AS IS" BASIS,          *
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.   *
 * See the License for the specific language governing permissions and        *
 * limitations under the License.                                             *
 ******************************************************************************/

require_once __DIR__ . '/vendor/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__ . '/views');
$twig = new Twig_Environment($loader);

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->overload();

$state = 'applicationState';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($twig, $state) {
    $r->get('/', function() use ($twig) {
        echo $twig->render('index.twig', [
            'authenticated' => isAuthenticated(),
            'profile' => getProfile()
        ]);
    });

    $r->get('/login', function() use ($state) {
        $query = http_build_query([
            'client_id' => getenv('CLIENT_ID'),
            'response_type' => 'code',
            'response_mode' => 'query',
            'scope' => 'openid profile',
            'redirect_uri' => 'http://localhost:8080/authorization-code/callback',
            'state' => $state,
            'nonce' => random_bytes(32)
        ]);

        header('Location: ' . getenv("ISSUER").'/v1/authorize?'.$query);
    });

    $r->get('/authorization-code/callback', function() use ($state) {
        if(array_key_exists('state', $_REQUEST) && $_REQUEST['state'] !== $state) {
            throw new \Exception('State does not match.');
        }

        if(array_key_exists('code', $_REQUEST)) {
            $exchange = exchangeCode($_REQUEST['code']);
            if(!isset($exchange->access_token)) {
                die('Could not exchange code for an access token');
            }

            if(verifyJwt($exchange->access_token) == false) {
                die('Verification of JWT failed');
            }

            setcookie("access_token","$exchange->access_token",time()+$exchange->expires_in,"/",false);
            header('Location: / ');
        }

        die('An error during login has occurred');


    });

    $r->get('/profile', function() use ($twig) {
        if(!isAuthenticated()) {
            header('Location: /');
        }
        echo $twig->render('profile.twig', [
            'authenticated' => isAuthenticated(),
            'profile' => getProfile()
        ]);
    });

    $r->post('/logout', function() {
        setcookie("access_token",NULL,-1,"/",false);
        header('Location: /');
    });

});

function exchangeCode($code) {
    $authHeaderSecret = base64_encode( getenv('CLIENT_ID') . ':' . getenv('CLIENT_SECRET') );
    $query = http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => 'http://localhost:8080/authorization-code/callback'
    ]);
    $headers = [
        'Authorization: Basic ' . $authHeaderSecret,
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded',
        'Connection: close',
        'Content-Length: 0'
    ];
    $url = getenv("ISSUER").'/v1/token?' . $query;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    $output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if(curl_error($ch)) {
        $httpcode = 500;
    }
    curl_close($ch);
    return json_decode($output);
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
