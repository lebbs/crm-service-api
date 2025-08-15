<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/UserRepository.php';

return function (App $app): void {
    $pdo = make_pdo();
    $userRepository = new UserRepository($pdo);

    function jsonResponse(Response $response, $data, int $status = 200): Response {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }

    function validateUserData(array $data): ?array {
        if (!isset($data['name'], $data['email'], $data['address'])) {
            return ['error' => 'name, email and address required'];
        }
        return null;
    }

    $app->get('/users', function (Request $request, Response $response) use ($userRepository) {
        $users = $userRepository->getAllUsers();
        if (empty($users)) {
            return jsonResponse($response, ['message' => 'No users found'], 404);
        }
        return jsonResponse($response, $users);
    });

    $app->get('/users/{id}', function (Request $request, Response $response, array $args) use ($userRepository) {
        $user = $userRepository->getUserById((int)$args['id']);
        if (empty($user)) {
            return jsonResponse($response, ['message' => 'User not found'], 404);
        }
        return jsonResponse($response, $user);
    });

    $app->post('/users', function (Request $request, Response $response) use ($userRepository) {
        $data = (array)$request->getParsedBody();
        if ($error = validateUserData($data)) {
            return jsonResponse($response, $error, 400);
        }
        $id = $userRepository->createUser($data);
        return jsonResponse($response, ['message' => 'User created successfully', 'id' => $id]);
    });

    $app->put('/users/{id}', function (Request $request, Response $response, array $args) use ($userRepository) {
        $data = (array)$request->getParsedBody();
        if ($error = validateUserData($data)) {
            return jsonResponse($response, $error, 400);
        }
        $updated = $userRepository->updateUser((int)$args['id'], $data);
        if ($updated === 0) {
            return jsonResponse($response, ['message' => 'User not found or no changes made'], 404);
        }
        return jsonResponse($response, ['message' => 'User updated successfully']);
    });

    $app->delete('/users/{id}', function (Request $request, Response $response, array $args) use ($userRepository) {
        $deleted = $userRepository->deleteUser((int)$args['id']);
        if ($deleted === 0) {
            return jsonResponse($response, ['message' => 'User not found'], 404);
        }
        return jsonResponse($response, ['message' => 'User deleted successfully']);
    });
};