<?php

declare(strict_types=1);

use Slim\Psr7\Request;
use Slim\Psr7\Response;

/** @var \Slim\App $app */

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Hello world!');
    return $response;
});
