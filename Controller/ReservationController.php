<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Reservation.php';

class ReservationController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    public function listReservations() {
        $sql = "SELECT r.*, c.type_cours, c.prix
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

    public function updateReservationStatus($id, $status) {
        $sql = "UPDATE reservation
                SET statut = :statut
                WHERE id_reservation = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'statut' => $status,
                'id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }
    public function updateReservationPayment($reservationId, $paymentIntentId) {
        try {
            $sql = "UPDATE reservation SET
                    statut = 'Payé',
                    payment_intent_id = :payment_intent_id
                    WHERE id_reservation = :reservation_id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':payment_intent_id', $paymentIntentId);
            $stmt->bindParam(':reservation_id', $reservationId);
            $stmt->execute();
        } catch (PDOException $e) {
            // Log the error
            error_log('Database Update Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function searchReservationsByDate($date, $status = null) {
        try {
            // Formatage de la date pour la requête SQL
            $formattedDate = date('Y-m-d', strtotime($date));
            error_log("Date formatée pour la recherche: " . $formattedDate);

            $sql = "SELECT r.*, c.type_cours, c.prix
                    FROM reservation r
                    JOIN cours c ON r.id_cours = c.id_cours
                    WHERE DATE(r.date_reservation) = :date";

            // Ajouter le filtre par statut si spécifié
            if ($status) {
                $sql .= " AND r.statut = :status";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':date', $formattedDate);

            if ($status) {
                $stmt->bindValue(':status', $status);
            }

            $stmt->execute();

            // Pour le débogage
            error_log("Recherche de réservations pour la date: " . $formattedDate . ($status ? " et statut: " . $status : ""));
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats trouvés: " . count($results));

            return $results;
        } catch (Exception $e) {
            error_log('Erreur dans searchReservationsByDate: ' . $e->getMessage());
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function filterReservationsByStatus($status) {
        try {
            $sql = "SELECT r.*, c.type_cours, c.prix
                    FROM reservation r
                    JOIN cours c ON r.id_cours = c.id_cours
                    WHERE r.statut = :status";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':status', $status);
            $stmt->execute();

            // Pour le débogage
            error_log("Filtrage des réservations par statut: " . $status);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de résultats trouvés: " . count($results));

            return $results;
        } catch (Exception $e) {
            error_log('Erreur dans filterReservationsByStatus: ' . $e->getMessage());
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getAvailableStatuses() {
        try {
            $sql = "SELECT DISTINCT statut FROM reservation ORDER BY statut";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log('Erreur dans getAvailableStatuses: ' . $e->getMessage());
            return ['En attente', 'Confirmé', 'Payé', 'Annulé'];  // Valeurs par défaut
        }
    }
}
?>
