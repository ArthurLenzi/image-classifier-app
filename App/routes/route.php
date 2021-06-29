<?php

use Controller\{ImageController, AuthController, LogController, UserController};

$app->group('', function ($app) {
    $app->get('/image/{role}', ImageController::class . ':getImage');
    $app->get('/image/process/all', ImageController::class . ':processImages');
    $app->get('/image/show/{hash}', ImageController::class . ':showImage');
    $app->post('/createUser', UserController::class . ':createUser');
})->add(AuthController::class . ':authWithJWT');

$app->post('/login', AuthController::class . ':login');

#TODO Middleware de Log deve ser adicionada antes da Middleware de Auth
