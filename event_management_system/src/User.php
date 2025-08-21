<?php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($username, $password, $email) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$username, $hashed_password, $email]);
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }

    public function findAll() {
        $sql = "SELECT id, username, email, role, created_at FROM users ORDER BY username ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function updateRole($user_id, $role) {
        // Basic role validation
        if (!in_array($role, ['admin', 'staff'])) {
            return false;
        }
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$role, $user_id]);
    }
}
