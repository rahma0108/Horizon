<?php
class Reservation {
    private int $id;
    private int $event_id;
    private int $user_id;
    private string $reservation_date;
    private string $status;

    // Constructor
    public function __construct(
        int $event_id,
        int $user_id,
        string $status = 'pending'
    ) {
        $this->event_id = $event_id;
        $this->user_id = $user_id;
        $this->reservation_date = date("Y-m-d H:i:s"); // Current timestamp
        $this->status = $status;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getEventId(): int {
        return $this->event_id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function getReservationDate(): string {
        return $this->reservation_date;
    }

    public function getStatus(): string {
        return $this->status;
    }

    // Setters
    public function setEventId(int $event_id): void {
        $this->event_id = $event_id;
    }

    public function setUserId(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function setReservationDate(string $reservation_date): void {
        $this->reservation_date = $reservation_date;
    }

    public function setStatus(string $status): void {
        $allowed = ['pending', 'confirmed', 'cancelled'];
        if (in_array($status, $allowed)) {
            $this->status = $status;
        }
    }
}
?>
