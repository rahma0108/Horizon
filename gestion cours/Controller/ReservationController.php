<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Reservation.php';

class ReservationController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    public function listReservations() {
        $sql = "SELECT r.*, c.type_cours 
                FROM reservation r 
                JOIN cours c ON r.id_cours = c.id_cours";
        try {
            $liste = $this->db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function addReservation($reservation) {
        $sql = "INSERT INTO reservation (date_reservation, nombre_personnes, statut, id_cours)  
                VALUES (:date_reservation, :nombre_personnes, :statut, :id_cours)";

        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'date_reservation' => $reservation->getDateReservation(),
                'nombre_personnes' => $reservation->getNombrePersonnes(),
                'statut' => $reservation->getStatut(),
                'id_cours' => $reservation->getIdCours()
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    public function deleteReservation($id) {
        $sql = "DELETE FROM reservation WHERE id_reservation = :id";
        try {
            $req = $this->db->prepare($sql);
            $req->bindValue(':id', $id);
            $req->execute();
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    public function updateReservation($id, $data) {
        $sql = "UPDATE reservation 
                SET date_reservation = :date_reservation,
                    nombre_personnes = :nombre_personnes,
                    statut = :statut,
                    id_cours = :id_cours
                WHERE id_reservation = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'date_reservation' => $data['date_reservation'],
                'nombre_personnes' => $data['nombre_personnes'],
                'statut' => $data['statut'],
                'id_cours' => $data['id_cours'],
                'id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    public function getReservationById($id) {
        $sql = "SELECT r.*, c.type_cours 
                FROM reservation r 
                JOIN cours c ON r.id_cours = c.id_cours 
                WHERE r.id_reservation = :id";
    
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }
}
?>
