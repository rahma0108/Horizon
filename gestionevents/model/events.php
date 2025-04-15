<?php
class Event {
    private int $id;
    private string $title;
    private string $description;
    private string $sport_type;
    private string $location;
    private string $event_date;
    private int $max_participants;
    private string $created_by;
    private string $created_at;
    private string $image;

    // Constructor
    public function __construct(
        string $title,
        string $description,
        string $sport_type,
        string $location,
        string $event_date,
        int $max_participants,
        string $created_by,
        string $image = '',
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->sport_type = $sport_type;
        $this->location = $location;
        $this->event_date = $event_date;
        $this->max_participants = $max_participants;
        $this->created_by = $created_by;
        $this->created_at = date("Y-m-d H:i:s"); // Current timestamp
        $this->image = $image;

    }

    // Getters
    
    


    public function getImage(): string {
        return $this->image;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getSportType(): string {
        return $this->sport_type;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getEventDate(): string {
        return $this->event_date;
    }

    public function getMaxParticipants(): int {
        return $this->max_participants;
    }

    public function getCreatedBy(): string {
        return $this->created_by;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    // Setters
    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setSportType(string $sport_type): void {
        $this->sport_type = $sport_type;
    }

    public function setLocation(string $location): void {
        $this->location = $location;
    }

    public function setEventDate(string $event_date): void {
        $this->event_date = $event_date;
    }

    public function setMaxParticipants(int $max_participants): void {
        $this->max_participants = $max_participants;
    }

    public function setCreatedBy(string $created_by): void {
        $this->created_by = $created_by;
    }
    public function setImage(string $image): void {
        $this->image = $image;
    }
}
?>