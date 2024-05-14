<?php

require_once 'db.php'; // Inclure le fichier db.php où tu as créé l'instance de PDO

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$app->post('/login', function (Request $request, Response $response) use ($database, $key) {
    $data = $request->getParsedBody();

    $query = 'SELECT * FROM `user` WHERE `email` = ?';
    $queryexec = $database->prepare($query);
    $queryexec->bindValue(1, $data['email'], PDO::PARAM_STR);
    $queryexec->execute();
    $res = $queryexec->fetchAll();

    // Vérifie si un utilisateur avec cet email a été trouvé
    if (!empty($res)) {
        if (password_verify($data['password'], $res[0]['password'])) {
            $payload = [
                'iat' => time(),
                'exp' => time() + 1800,
                'id' => $res[0]['id'],
                'role' => $res[0]['role']
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');
            $response->getBody()->write(json_encode(['valid' => 'Vous êtes connecté', 'token' => $jwt]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => 'Mauvais mot de passe ou mauvais mail']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Utilisateur non trouvé']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
});
