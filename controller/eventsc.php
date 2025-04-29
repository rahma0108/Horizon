    <?php
    include_once '../config.php';

    class eventsC {
        public function getEventStats() {
            $sql = "SELECT e.title, COUNT(r.id) AS reservation_count
                    FROM events e
                    LEFT JOIN reservations r ON e.id = r.event_id
                    GROUP BY e.id
                    HAVING reservation_count > 0
                    ORDER BY reservation_count DESC";
            $db = config::getConnexion();
            try {
                $query = $db->prepare($sql);
                $query->execute();
                return $query->fetchAll();
            } catch (Exception $e) {
                die('Error: ' . $e->getMessage());
            }
        }
        public function listEvents() {
            $db = config::getConnexion();
            try {
                $query = $db->query('SELECT * FROM events');
                return $query->fetchAll();
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }

        public function deleteEvent($id) {
            $db = config::getConnexion();
             try {
        // 1. Update reservations: set status = cancelled if pending or confirmed
        $updateReservations = $db->prepare('UPDATE reservations SET status = "cancelled" WHERE event_id = :id AND (status = "pending" OR status = "confirmed")');
        $updateReservations->execute(['id' => $id]);

        // 2. Now delete the event
        $req = $db->prepare('DELETE FROM events WHERE id = :id');
        $req->execute(['id' => $id]);
    } catch (Exception $e) {
        die('ERROR: ' . $e->getMessage());
    }
}

        public function addEvent(Event $event) {
            $db = config::getConnexion();
            try {
                $sql = "INSERT INTO events (title, description, sport_type, location, event_date, max_participants, createdby, created_at, image)
                        VALUES (:title, :description, :sport_type, :location, :event_date, :max_participants, :created_by, :created_at, :image)";
                $req = $db->prepare($sql);
                $req->execute([
                    'title' => $event->getTitle(),
                    'description' => $event->getDescription(),
                    'sport_type' => $event->getSportType(),
                    'location' => $event->getLocation(),
                    'event_date' => $event->getEventDate(),
                    'max_participants' => $event->getMaxParticipants(),
                    'created_by' => $event->getCreatedBy(),
                    'created_at' => $event->getCreatedAt(),
                    'image'=>$event->getImage()
                ]);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }

    

        public function updateEvent(Event $event, $id) {
            $db = config::getConnexion();
            try {
                $sql = "UPDATE events 
                        SET title = :title,
                            description = :description,
                            sport_type = :sport_type,
                            location = :location,
                            event_date = :event_date,
                            max_participants = :max_participants,
                            createdby = :created_by,
                            image= :image
                        WHERE id = :id";
                $req = $db->prepare($sql);
                $req->execute([
                    'title' => $event->getTitle(),
                    'description' => $event->getDescription(),
                    'sport_type' => $event->getSportType(),
                    'location' => $event->getLocation(),
                    'event_date' => $event->getEventDate(),
                    'max_participants' => $event->getMaxParticipants(),
                    'created_by' => $event->getCreatedBy(),
                    'image'=> $event->getImage(),
                    'id' => $id
                ]);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }
        public function getEventById($id) {
            $db = config::getConnexion();
            try {
                
                $query = $db->prepare('SELECT * FROM events WHERE id = :id');
                $query->execute(['id' => $id]);

                return $query->fetch();
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }
    }
    ?>
