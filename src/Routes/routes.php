<?php

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Repositories/UserRepository.php';
require_once __DIR__ . '/../Controllers/UserController.php';

use Slim\App;
use App\Repositories\UserRepository;
use App\Controllers\UserController;

return function (App $app): void {
    $pdo = make_pdo();
    $userRepository = new UserRepository($pdo);
    $userController = new UserController($userRepository);

    $app->get('/users', [$userController, 'getAllUsers']);
    $app->get('/users/{id}', [$userController, 'getUserById']);
    $app->post('/users', [$userController, 'createUser']);
    $app->put('/users/{id}', [$userController, 'updateUser']);
    $app->delete('/users/{id}', [$userController, 'deleteUser']);
};