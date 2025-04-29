<?php
class Reservation {
    private $id_reservation;
    private $date_reservation;
    private $nombre_personnes;
    private $statut;
    private $id_cours;
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    // Getters
    public function getId() {
        return $this->id_reservation;
    }

    public function getDateReservation() {
        return $this->date_reservation;
    }

    public function getNombrePersonnes() {
        return $this->nombre_personnes;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getIdCours() {
        return $this->id_cours;
    }

    // Setters
    public function setDateReservation($date_reservation) {
        $this->date_reservation = $date_reservation;
    }

    public function setNombrePersonnes($nombre_personnes) {
        $this->nombre_personnes = $nombre_personnes;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }

    public function setIdCours($id_cours) {
        $this->id_cours = $id_cours;
    }
}
?>
