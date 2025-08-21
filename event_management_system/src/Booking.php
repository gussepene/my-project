<?php

class Booking {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Creates a new booking for an event.
     */
    public function create($event_id, $customer_name, $customer_email, $notes) {
        $sql = "INSERT INTO bookings (event_id, customer_name, customer_email, notes) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$event_id, $customer_name, $customer_email, $notes]);
    }

    /**
     * Finds all bookings for a given event ID.
     */
    public function findByEventId($event_id) {
        $sql = "SELECT * FROM bookings WHERE event_id = ? ORDER BY booking_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$event_id]);
        return $stmt->fetchAll();
    }

    /**
     * Finds all bookings across all events. (For Admins)
     */
    public function findAll() {
        $sql = "SELECT b.*, e.title as event_title FROM bookings b JOIN events e ON b.event_id = e.id ORDER BY b.booking_date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
