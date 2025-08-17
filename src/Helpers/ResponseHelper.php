<?php
namespace App\Helpers;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseHelper {
    /**
     * Return a JSON response.
     */
    public static function jsonResponse(Response $response, $data, int $status = 200): Response {
        $json = json_encode($data);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode data to JSON: ' . json_last_error_msg());
        }
        $response->getBody()->write($json);
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }

    /**
     * Validate user data.
     */
    public static function validateUserData(array $data): ?array {
        $requiredFields = ['name', 'email', 'address'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return ['error' => "$field is required and cannot be empty"];
            }
        }
        return filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? null : ['error' => 'Invalid email format'];
    }
}
