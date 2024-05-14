<?php

require_once 'db.php'; // Inclure le fichier db.php où tu as créé l'instance de PDO

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

$app->post('/addInspiration', function (Request $request, Response $response)  use ($database, $key){
    $err = array();
    require_once 'db.php';
    
    $uploadedFiles = $request->getUploadedFiles();
    $data = $request->getParsedBody();

    // Vérifiez si le fichier a été correctement envoyé
    if (empty($uploadedFiles['image']) || $uploadedFiles['image']->getError() !== UPLOAD_ERR_OK) {
        $err['image'] = 'Erreur lors de l\'envoi de l\'image';
    }

    if (empty($data['etiquette'])) {
        $err['etiquette'] = 'etiquette vide';
    }

    if (empty($err)) {
        $image = $uploadedFiles['image'];
        $uploadPath = __DIR__ . '/../../uploads'; // Chemin de téléchargement des images
        $filename = uniqid() . '-' . $image->getClientFilename();
    
        // Déplacez le fichier téléchargé vers le dossier d'uploads
        $image->moveTo($uploadPath . DIRECTORY_SEPARATOR . $filename);
        // Insérer le chemin du fichier dans la base de données
        $query = 'INSERT INTO `inspirations` (`image`,`etiquette`) VALUES(?,?)';
        $queryexec = $database->prepare($query);
        $queryexec->bindValue(1, '/uploads/' . $filename, PDO::PARAM_STR);
        $queryexec->bindValue(2, $data['etiquette'], PDO::PARAM_STR);
        $queryexec->execute();

        $response->getBody()->write(json_encode(['valid' => 'Image et etiquette ajoutées']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => $err]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});