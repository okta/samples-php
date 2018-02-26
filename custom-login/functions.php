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
