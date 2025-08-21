<?php

class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($user_id, $venue_id, $title, $description, $event_date) {
        $sql = "INSERT INTO events (user_id, venue_id, title, description, event_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $venue_id, $title, $description, $event_date]);
    }

    public function findByUserId($user_id) {
        $sql = "SELECT events.*, venues.name as venue_name FROM events LEFT JOIN venues ON events.venue_id = venues.id WHERE events.user_id = ? ORDER BY event_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function findAll() {
        $sql = "SELECT events.*, venues.name as venue_name, users.username as created_by
                FROM events
                LEFT JOIN venues ON events.venue_id = venues.id
                JOIN users ON events.user_id = users.id
                ORDER BY event_date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM events WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $venue_id, $title, $description, $event_date) {
        $sql = "UPDATE events SET venue_id = ?, title = ?, description = ?, event_date = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$venue_id, $title, $description, $event_date, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
