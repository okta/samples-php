<?php

require_once __DIR__ . '/../vendor/autoload.php';

function isAuthenticated()
{
    if(isset($_COOKIE['access_token'])) {
        return true;
    }

    return false;
}

function getProfile()
{
    if(!isAuthenticated()) {
        return [];
    }

    $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
        ->setIssuer(getenv('ISSUER'))
        ->setAudience('api://default')
        ->setClientId(getenv('CLIENT_ID'))
        ->build();

    $jwt = $jwtVerifier->verify($_COOKIE['access_token']);

    return $jwt->claims;

}

function verifyJwt($jwt)
{
    try {
        $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
            ->setIssuer(getenv('ISSUER'))
            ->setAudience('api://default')
            ->setClientId(getenv('CLIENT_ID'))
            ->build();

        return $jwtVerifier->verify($jwt);
    } catch (\Exception $e) {
        return false;
    }
}