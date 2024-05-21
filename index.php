<?php
//Récupérer les dépendances
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


//Récupération de l'autoload
require __DIR__ .'/vendor/autoload.php';
//instance d'app slim
$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$key = 'ïOÖbÈ3~_Äijb¥d-ýÇ£Hf¿@xyLcP÷@';

require_once 'db.php';

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("LGX MOTIVATION");
    return $response;
});

$testAuth = function($request,$handler)use ($key){
    $testAuth = $request->getHeader('Authorization');
    $token = $testAuth[0];
    $decoded = JWT::decode($token, new Key($key, 'HS256'));

    $userDatas = [
        'id' => $decoded->id,
        'role' => $decoded->role,
    ];

    $request = $request->withAttribute('user',$userDatas);

    return $handler->handle($request);
};

$checkAdmin = function($request,$handler)use ($key){
    $testAuth = $request->getHeader('Authorization');
    $token = $testAuth[0];
    try{
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '1'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

};



require_once __DIR__ . '/routes/admin/postAdmin.php';

require_once __DIR__ . '/routes/user/postUser.php';
require_once __DIR__ . '/routes/user/getUser.php';

require_once __DIR__ . '/routes/inspiration/getInspiration.php';
require_once __DIR__ . '/routes/inspiration/postInspiration.php';

require_once __DIR__ . '/routes/exercices/getExercices.php';
require_once __DIR__ . '/routes/exercices/postExercices.php';

require_once __DIR__ . '/routes/challenges/getChallenges.php';


$app->post('/namegroupe', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

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
       $err['idemetteur'] = "l'emetteur est vide";
   }
   if(empty($data['type'])){
       $err['type'] = 'type de message vide vide';
   }
   if(empty($data['contenu'])){
       $err['contenu'] = 'le contenu vide vide';
   }

   if(empty($data['idgroupe'])){
    $err['idgroupe'] = "le groupe n'a pas ete retrouver vide";
}

   if(empty($err)){

       $query = 'INSERT INTO `message_discussion` (`idemetteur`,`type`,`contenu`,`idgroupe`) VALUES(?,?,?,?)';
       $queryexec = $database->prepare($query);
       $queryexec->bindValue(1, $data['idemetteur'] ,PDO::PARAM_STR);
       $queryexec->bindValue(2, $data['type'] ,PDO::PARAM_STR);
       $queryexec->bindValue(3, $data['contenu'] ,PDO::PARAM_STR);
       $queryexec->bindValue(4, $data['idgroupe'] ,PDO::PARAM_STR);
       $queryexec->execute();

       $response->getBody()->write(json_encode('message inserer '));
       return $response->withHeader('Content-Type', 'application/json')->withStatus(201);



   }else{
   $response->getBody()->write(json_encode($err));
   return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
   }

})->add($testAuth);
$app->post('/messagegroupe', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
  

    require 'db.php';

    $query = 'SELECT * FROM `message_discussion` WHERE `idgroupe` = ?';
    $queryexec = $database->prepare($query);
    $queryexec->bindValue(1, $data['idgroupe'], PDO::PARAM_INT);
    $queryexec->execute();
    $groupe_discussion = $queryexec->fetch(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($groupe_discussion));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
   
})->add($testAuth);


$app->run();
