<?php

class MenuItem {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($menu_id, $name, $description, $price) {
        $sql = "INSERT INTO menu_items (menu_id, name, description, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$menu_id, $name, $description, $price]);
    }

    public function findByMenuId($menu_id) {
        $sql = "SELECT * FROM menu_items WHERE menu_id = ? ORDER BY name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$menu_id]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM menu_items WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $name, $description, $price) {
        $sql = "UPDATE menu_items SET name = ?, description = ?, price = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $description, $price, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM menu_items WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
