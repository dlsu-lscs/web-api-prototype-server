<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function() use($app) {
    $status = array('status' => 'ok');
    return $app->json($status);
});

$app->get('/notes', function() use($app) {
    return 'list of notes';
});

$app->post('/note', function() use($app) {
    return 'create note';
});

$app->get('/note/{id}', function($id) use($app) {
    return 'retrieve note';
});

$app->put('/note/{id}', function($id) use($app) {
    return 'update note';
});

$app->delete('/note/{id}', function($id) use($app) {
    return 'delete note';
});


$app['debug'] = true;
$app->run();
