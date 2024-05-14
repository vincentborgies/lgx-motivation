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


require_once __DIR__ . '/routes/messages/postMessages.php';


$app->run();
