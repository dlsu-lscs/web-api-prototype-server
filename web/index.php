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

$app->get('/notes', 'Notes::index');
$app->post('/notes', 'Notes::create');
$app->get('/notes/{id}', 'Notes::retrieve');
$app->put('/notes/{id}', 'Notes::update');
$app->delete('/notes/{id}', 'Notes::delete');

$app['debug'] = true;
$app->run();


class Notes {
    public function index(Application $app, Request $req) {
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
    }

    public function create(Application $app, Request $req) {
        $conn = $app['db'];

        $note = array(
            'note' => $req->get('note'),
            'timestamp' => date('Y-m-d H:i:s'),
        );

        $conn->insert('note', $note);
        $id = $conn->lastInsertId();

        return $app->json($id);
    }

    public function retrieve(Application $app, Request $req, $id) {
        $conn = $app['db'];
        $query = "SELECT * FROM note WHERE id = ?";

        $note = $conn->fetchAssoc($query, array($id));
        if (!$note) {
            $error = array('message' => 'note not found');
            return $app->json($error, 404);
        }

        return $app->json($note);
    }

    public function update(Application $app, Request $req, $id) {
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
    }

    public function delete(Application $app, Request $req, $id) {
        $conn = $app['db'];

        $ok = $conn->delete('note', array('id' => $id));
        if (!$ok) {
            $error = array('message' => 'note not found');
            return $app->json($error, 404);
        }

        return $app->json('ok');
    }
}
