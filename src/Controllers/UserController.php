<?php

namespace App\Controllers;

require_once __DIR__ . '/../Repositories/UserRepository.php';
require_once __DIR__ . '/../Helpers/ResponseHelper.php';

use App\Repositories\UserRepository;
use App\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieves all users from the repository and returns them in the response.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     * 
     * @return Response The HTTP response containing the list of users or an error message.
     */
    public function getAllUsers(Request $request, Response $response): Response
    {
        $users = $this->userRepository->getAllUsers();
        if (empty($users)) {
            return ResponseHelper::jsonResponse($response, ['message' => 'No users found'], 404);
        }
        return ResponseHelper::jsonResponse($response, $users);
    }

    /**
     * Retrieves a user by their ID.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     * @param array $args An array of route parameters, including the user ID.
     *
     * @return Response The HTTP response containing the user data or an error message.
     *
     * @throws InvalidArgumentException If the user ID is not a valid integer.
     */
    public function getUserById(Request $request, Response $response, array $args): Response
    {
        $user = $this->userRepository->getUserById((int)$args['id']);
        if (!$user) {
            return ResponseHelper::jsonResponse($response, ['message' => 'User not found'], 404);
        }
        return ResponseHelper::jsonResponse($response, $user);
    }

    /**
     * Handles the creation of a new user.
     *
     * @param Request $request  The HTTP request object containing user data.
     * @param Response $response The HTTP response object to send the response.
     *
     * @return Response Returns a JSON response indicating success or failure.
     *
     * The method performs the following steps:
     * 1. Retrieves and parses the user data from the request body.
     * 2. Validates the user data using the `validateUserData` function.
     *    - If validation fails, returns a 400 response with the error details.
     * 3. Creates a new user in the repository using the provided data.
     * 4. Returns a success response with the newly created user's ID.
     */
    public function createUser(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        if ($error = ResponseHelper::validateUserData($data)) {
            return ResponseHelper::jsonResponse($response, $error, 400);
        }
        $id = $this->userRepository->createUser($data);
        return ResponseHelper::jsonResponse($response, ['message' => 'User created successfully', 'id' => $id]);
    }

    /**
     * Updates a user's information based on the provided data.
     *
     * @param Request $request  The HTTP request object containing the user data to update.
     * @param Response $response The HTTP response object used to return the response.
     * @param array $args       An associative array containing route parameters, including the user ID.
     *
     * @return Response Returns a JSON response indicating the result of the update operation:
     *                  - 400 if the provided data is invalid.
     *                  - 404 if the user is not found or no changes were made.
     *                  - 200 if the user was successfully updated.
     */
    public function updateUser(Request $request, Response $response, array $args): Response
    {
        $data = (array)$request->getParsedBody();
        if ($error = ResponseHelper::validateUserData($data)) {
            return ResponseHelper::jsonResponse($response, $error, 400);
        }
        $updated = $this->userRepository->updateUser((int)$args['id'], $data);
        if ($updated === 0) {
            return ResponseHelper::jsonResponse($response, ['message' => 'User not found or no changes made'], 404);
        }
        return ResponseHelper::jsonResponse($response, ['message' => 'User updated successfully']);
    }

    /**
     * Deletes a user by their ID.
     *
     * This method handles the deletion of a user based on the ID provided
     * in the route arguments. If the user is not found, it returns a 404
     * response with an appropriate message. Otherwise, it confirms the
     * successful deletion of the user.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     * @param array $args The route arguments, including the user ID.
     *
     * @return Response A JSON response indicating the result of the operation.
     */
    public function deleteUser(Request $request, Response $response, array $args): Response
    {
        $deleted = $this->userRepository->deleteUser((int)$args['id']);
        if ($deleted === 0) {
            return ResponseHelper::jsonResponse($response, ['message' => 'User not found'], 404);
        }
        return ResponseHelper::jsonResponse($response, ['message' => 'User deleted successfully']);
    }
}