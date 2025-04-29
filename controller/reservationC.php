<?php
include_once '../config.php';
 // Assuming you have the Reservation class in this file

class reservationsC {
    public function setStatus(string $status): void {
        $allowed = ['pending', 'confirmed', 'cancelled'];
        if (in_array($status, $allowed)) {
            $this->status = $status;
        }
    }
    
    
    public function listReservations() {
        $db = config::getConnexion();
        try {
            $query = $db->query('SELECT * FROM reservations');
            return $query->fetchAll();
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function deleteReservation($id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('DELETE FROM reservations WHERE id = :id');
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function addReservation(Reservation $reservation) {
        $db = config::getConnexion();
        try {
            $sql = "INSERT INTO reservations (event_id, user_id, reservation_date, status)
                    VALUES (:event_id, :user_id, :reservation_date, :status)";
            $req = $db->prepare($sql);
            $req->execute([
                'event_id' => $reservation->getEventId(),
                'user_id' => $reservation->getUserId(),
                'reservation_date' => $reservation->getReservationDate(),
                'status' => $reservation->getStatus()
            ]);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function updateReservation(Reservation $reservation, $id) {
        $db = config::getConnexion();
        try {
            $sql = "UPDATE reservations 
                    SET event_id = :event_id,
                        user_id = :user_id,
                        reservation_date = :reservation_date,
                        status = :status
                    WHERE id = :id";
            $req = $db->prepare($sql);
            $req->execute([
                'event_id' => $reservation->getEventId(),
                'user_id' => $reservation->getUserId(),
                'reservation_date' => $reservation->getReservationDate(),
                'status' => $reservation->getStatus(),
                'id' => $id
            ]);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function getReservationById($id) {
        $db = config::getConnexion();
        try {
            $query = $db->prepare('SELECT * FROM reservations WHERE id = :id');
            $query->execute(['id' => $id]);

            return $query->fetch();
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function getReservationsByEvent($event_id) {
        $db = config::getConnexion();
        try {
            $query = $db->prepare('SELECT * FROM reservations WHERE event_id = :event_id');
            $query->execute(['event_id' => $event_id]);

            return $query->fetchAll();
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }
}
?>
