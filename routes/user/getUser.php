<?php

require_once 'db.php'; // Inclure le fichier db.php où tu as créé l'instance de PDO

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

$app->get('/profil', function (Request $request, Response $response) {
    // Retrieve the user ID from request attributes
    $userId = $request->getAttribute('id');

    require 'db.php';

    $query = 'SELECT `id`, `email`, `nom` FROM `user` WHERE `id` = ?';
    $queryexec = $database->prepare($query);
    $queryexec->bindValue(1, $userId, PDO::PARAM_INT);
    $queryexec->execute();
    $userProfile = $queryexec->fetch(PDO::FETCH_ASSOC);

    if ($userProfile) {
        $response->getBody()->write(json_encode($userProfile));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Profil non trouvé']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
})->add($testAuth);

$app->get('/user/decode', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');

    $userInfo = [
        'userId' => $token['id'],
        'userRole' => $token['role'],
    ];


    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $userInfo]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($testAuth);