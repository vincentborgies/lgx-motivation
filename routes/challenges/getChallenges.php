<?php

$app->get('/challenges/daily', function (Request $request, Response $response) {
    // Retrieve the user ID from request attributes
    $userId = $request->getAttribute('id');

    require 'db.php';

    $query = 'SELECT `id`, `image`, `description` 
            FROM `challenges`
            WHERE periode = 1';
            
    $queryexec = $database->prepare($query);
    $queryexec->execute();
    $dailyChallengeInfo = $queryexec->fetchAll(PDO::FETCH_ASSOC);

    if ($dailyChallengeInfo) {
        $response->getBody()->write(json_encode($userProfile));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Challenge quotidien non trouvé']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
})->add($testAuth);