<?php

require_once __DIR__.'/../../db.php'; // Inclure le fichier db.php où tu as créé l'instance de PDO

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

$app->get('/getExercices', function (Request $request, Response $response)  use ($database, $key){
    // Retrieve the user ID from request attributes
    $userId = $request->getAttribute('user');

    $query = 'SELECT `id`, `image`, `description`, `time` FROM `exercices`';
    $queryexec = $database->prepare($query);
    $queryexec->execute();
    $userProfile = $queryexec->fetchAll(PDO::FETCH_ASSOC);

    if ($userProfile) {
        $response->getBody()->write(json_encode($userProfile));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Inspiration non trouvé']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
})->add($testAuth);