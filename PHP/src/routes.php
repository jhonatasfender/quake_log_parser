<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    $b = new \App\Bootstrap();

    $this->logger->info("Slim-Skeleton '/' route");
    
    return $this->renderer->render($response, 'index.phtml', ['b' => $b->get()]);
});


$app->get('/search', function (Request $request, Response $response, array $args) {
    $b = new \App\Bootstrap();

    d($request->getParsedBodyParam('search'));exit();

    $this->logger->info("Slim-Skeleton '/' route");
    
    return $this->renderer->render($response, 'index.phtml', ['b' => $b->get()]);
});
