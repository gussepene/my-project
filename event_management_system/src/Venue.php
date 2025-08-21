<?php

class Venue {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($name, $address, $capacity) {
        $sql = "INSERT INTO venues (name, address, capacity) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $address, $capacity]);
    }

    public function findAll() {
        $sql = "SELECT * FROM venues ORDER BY name ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM venues WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $name, $address, $capacity) {
        $sql = "UPDATE venues SET name = ?, address = ?, capacity = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $address, $capacity, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM venues WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
