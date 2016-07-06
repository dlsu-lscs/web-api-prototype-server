<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'dbname' => 'web_api_prototype_db',
        'user' => 'root',
        // 'password' => 'password',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
    ),
));


$app->get('/', function() use($app) {
    return $app->json('ok');
});

// list
$app->get('/notes', function() use($app) {
    $conn = $app['db'];
    $query = "SELECT id, note FROM note ORDER BY timestamp desc";

    $notes = $conn->fetchAll($query);
    foreach ($notes as &$note) {
        $text = $note['note'];
        if (strlen($text) > 40) {
            $note['note'] = substr($text, 0, 40-3).'...';
        }
    }

    return $app->json($notes);
});

// create
$app->post('/note', function(Request $req) use($app) {
    $conn = $app['db'];

    $note = array(
        'note' => $req->get('note'),
        'timestamp' => date('Y-m-d H:i:s'),
    );

    $conn->insert('note', $note);
    $id = $conn->lastInsertId();

    return $app->json($id);
});

// retrieve
$app->get('/note/{id}', function($id) use($app) {
    $conn = $app['db'];
    $query = "SELECT * FROM note WHERE id = ?";

    $note = $conn->fetchAssoc($query, array($id));
    if (!$note) {
        $error = array('message' => 'note not found');
        return $app->json($error, 404);
    }

    return $app->json($note);
});

// update
$app->put('/note/{id}', function($id, Request $req) use($app) {
    $conn = $app['db'];

    $note = array(
        'note' => $req->get('note'),
        'timestamp' => date('Y-m-d H:i:s'),
    );

    $ok = $conn->update('note', $note, array('id' => $id));
    if (!$ok) {
        $error = array('message' => 'note not found');
        return $app->json($error, 404);
    }

    return $app->json('ok');
});

// delete
$app->delete('/note/{id}', function($id) use($app) {
    $conn = $app['db'];

    $ok = $conn->delete('note', array('id' => $id));
    if (!$ok) {
        $error = array('message' => 'note not found');
        return $app->json($error, 404);
    }

    return $app->json('ok');
});


$app['debug'] = true;
$app->run();
