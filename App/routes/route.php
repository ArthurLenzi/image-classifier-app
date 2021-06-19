<?php

use Controller\{ImageController, AuthController, LogController, ExamplesController};

$app->group('', function ($app) {
    $app->get('/', FormularioController::class . ':showFormulario');
    $app->get('/image', ImageController::class . ':getImage');
});

#TODO Middleware de Log deve ser adicionada antes da Middleware de Auth
