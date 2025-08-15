<?php

declare(strict_types=1);

class UserRepository {
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers(): array {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function getUserById(int $id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: [];
    }

    public function createUser(array $data): int {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, address) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['email'], $data['address']]);
        return (int)$this->pdo->lastInsertId();
    }

    public function updateUser(int $id, array $data): int {
        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, address = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['email'], $data['address'], $id]);
        return $stmt->rowCount();
    }

    public function deleteUser(int $id): int {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}