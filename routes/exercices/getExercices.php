<?php

$app->get('/getExercices', function (Request $request, Response $response) {
    // Retrieve the user ID from request attributes
    $userId = $request->getAttribute('id');

    require 'db.php';

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