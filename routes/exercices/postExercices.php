<?php

$app->post('/addExercices', function (Request $request, Response $response) {
    $err = array();
    require 'db.php';
    
    $uploadedFiles = $request->getUploadedFiles();
    $data = $request->getParsedBody();

    // Vérifiez si le fichier a été correctement envoyé
    if (empty($uploadedFiles['image']) || $uploadedFiles['image']->getError() !== UPLOAD_ERR_OK) {
        $err['image'] = 'Erreur lors de l\'envoi de l\'image';
    }

    if (empty($data['description'])) {
        $err['description'] = 'Description vide';
    }

    if (empty($err)) {
        $image = $uploadedFiles['image'];
        $uploadPath = __DIR__ . '/uploads'; // Chemin de téléchargement des images
        $filename = uniqid() . '-' . $image->getClientFilename();
        
        // Déplacez le fichier téléchargé vers le dossier d'uploads
        $image->moveTo($uploadPath . DIRECTORY_SEPARATOR . $filename);

        // Insérer le chemin du fichier dans la base de données
        $query = 'INSERT INTO `exercices` (`image`,`description`,`time`) VALUES(?,?,?)';
        $queryexec = $database->prepare($query);
        $queryexec->bindValue(1, '/uploads/' . $filename, PDO::PARAM_STR);
        $queryexec->bindValue(2, $data['description'], PDO::PARAM_STR);
        $queryexec->bindValue(3, $data['time'], PDO::PARAM_STR);
        $queryexec->execute();

        $response->getBody()->write(json_encode(['valid' => 'Image et description ajoutées']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => $err]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});