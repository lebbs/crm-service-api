<?php

namespace App\Repositories;
use PDO;

class UserRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Retrieves all users from the database.
     *
     * @return array An array of all users fetched from the "users" table.
     */
    public function getAllUsers(): array {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    /**
     * Retrieves a user record from the database by their ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return array An associative array containing the user's data, or an empty array if no user is found.
     */
    public function getUserById(int $id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: [];
    }

    /**
     * Creates a new user in the database.
     *
     * @param array $data An associative array containing the user's data:
     *                    - 'name' (string): The name of the user.
     *                    - 'email' (string): The email address of the user.
     *                    - 'address' (string): The address of the user.
     * @return int The ID of the newly created user.
     * @throws PDOException If the database operation fails.
     */
    public function createUser(array $data): int {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, address) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['email'], $data['address']]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Updates a user's information in the database.
     *
     * @param int $id The ID of the user to update.
     * @param array $data An associative array containing the user's updated data.
     *                    Expected keys: 'name', 'email', 'address'.
     * @return int The number of rows affected by the update operation.
     *
     * @throws PDOException If the database operation fails.
     */
    public function updateUser(int $id, array $data): int {
        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, address = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['email'], $data['address'], $id]);
        return $stmt->rowCount();
    }

    /**
     * Deletes a user from the database by their ID.
     *
     * @param int $id The ID of the user to be deleted.
     * @return int The number of rows affected by the delete operation.
     */
    public function deleteUser(int $id): int {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}