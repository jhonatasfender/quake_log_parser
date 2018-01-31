<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    $b = new \App\Bootstrap();

    $this->logger->info("Slim-Skeleton '/' route");
    
    return $this->renderer->render($response, 'index.phtml', ['b' => $b->get()]);
});


$app->post('/search', function (Request $request, Response $response, array $args) {
    $b = new \App\Bootstrap();

    if($request->getParsedBodyParam('search') == null)
    	return $response->withRedirect("/");

    $this->logger->info("Slim-Skeleton '/search' route");
    
    return $this->renderer->render($response, 'index.phtml', ['b' => $b->get($request->getParsedBodyParam('search'))]);
});
