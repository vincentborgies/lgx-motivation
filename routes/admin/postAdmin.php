<?php

require_once 'db.php'; // Inclure le fichier db.php où tu as créé l'instance de PDO

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$app->post('/addUser', function (Request $request, Response $response) use ($database, $key) {
     $data = $request->getParsedBody();
    $err = array();

   
    if(empty($data['nom'])){
        $err['nom'] = 'nom vide';
    }
    if(empty($data['email'])){
        $err['email'] = 'email vide';
    }
    if(empty($data['password'])){
        $err['password'] = 'password vide';
    }

    if(empty($err)){

        $passwordhash = password_hash($data['password'],PASSWORD_DEFAULT);

        $query = 'INSERT INTO `user` (`nom`,`email`,`password`) VALUES(?,?,?)';
        $queryexec = $database->prepare($query);
        $queryexec->bindValue(1, $data['nom'] ,PDO::PARAM_STR);
        $queryexec->bindValue(2, $data['email'] ,PDO::PARAM_STR);
        $queryexec->bindValue(3, $passwordhash ,PDO::PARAM_STR);
        $queryexec->execute();

        $response->getBody()->write(json_encode(['valid' => 'Super le compte est créé']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);



    }else{
    $response->getBody()->write(json_encode(['erreur' => $err]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

})->add($testAuth)->add($checkAdmin);