<?php

require_once 'db.php'; // Inclure le fichier db.php où tu as créé l'instance de PDO

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$app->post('/namegroupe', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $userId = $request->getAttribute('id');

    require 'db.php';

    $query = 'SELECT `id`, `nom` FROM `groupe_discussion` WHERE `id` = ?';
    $queryexec = $database->prepare($query);
    $queryexec->bindValue(1, $data['idgroupe'], PDO::PARAM_INT);
    $queryexec->execute();
    $groupe_discussion = $queryexec->fetch(PDO::FETCH_ASSOC);

    if ($groupe_discussion) {
        $response->getBody()->write(json_encode($groupe_discussion));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'groupe_discussion non trouvé']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
})->add($testAuth);
$app->post('/addMessage', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
   $err = array();
   require 'db.php';

  
   if(empty($data['idemetteur'])){
       $err['nom'] = "l'emetteur est vide";
   }
   if(empty($data['type'])){
       $err['email'] = 'type de message vide vide';
   }
   if(empty($data['contenu'])){
       $err['password'] = 'le contenu vide vide';
   }

   if(empty($data['idgroupe'])){
    $err['idgroupe'] = "le groupe n'a pas ete retrouver vide";
}

   if(empty($err)){

       $passwordhash = password_hash($data['password'],PASSWORD_DEFAULT);

       $query = 'INSERT INTO `message_discussion` (`idemetteur`,`type`,`contenu`,`idgroupe`) VALUES(?,?,?,?)';
       $queryexec = $database->prepare($query);
       $queryexec->bindValue(1, $data['idemetteur'] ,PDO::PARAM_STR);
       $queryexec->bindValue(2, $data['type'] ,PDO::PARAM_STR);
       $queryexec->bindValue(3, $data['contenu'] ,PDO::PARAM_STR);
       $queryexec->bindValue(4, $data['idgroupe'] ,PDO::PARAM_STR);
       $queryexec->execute();

       $response->getBody()->write(json_encode(['valid' => 'message inserer ']));
       return $response->withHeader('Content-Type', 'application/json')->withStatus(201);



   }else{
   $response->getBody()->write(json_encode(['erreur' => $err]));
   return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
   }

})->add($testAuth);
$app->post('/messagegroupe', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $userId = $request->getAttribute('id');

    require 'db.php';

    $query = 'SELECT * FROM `message_discussion` WHERE `idgroupe` = ?';
    $queryexec = $database->prepare($query);
    $queryexec->bindValue(1, $data['idgroupe'], PDO::PARAM_INT);
    $queryexec->execute();
    $groupe_discussion = $queryexec->fetch(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($groupe_discussion));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
   
})->add($testAuth);
