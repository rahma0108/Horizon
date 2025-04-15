<?php
class Cours {
    private $id_cours;
    private $type_cours;
    private $date_cours;
    private $adresse;
    private $prix;
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=projetweb', 'root', '');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }

    // Getters
    public function getId() {
        return $this->id_cours;
    }

    public function getType() {
        return $this->type_cours;
    }

    public function getDate() {
        return $this->date_cours;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getPrix() {
        return $this->prix;
    }

    // Setters
    public function setType($type) {
        $this->type_cours = $type;
    }

    public function setDate($date) {
        $this->date_cours = $date;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    // CRUD Operations
    public function create($type_cours, $date_cours, $adresse, $prix) {
        try {
            $sql = "INSERT INTO cours (type_cours, date_cours, adresse, prix) 
                    VALUES (:type_cours, :date_cours, :adresse, :prix)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':type_cours' => $type_cours,
                ':date_cours' => $date_cours,
                ':adresse' => $adresse,
                ':prix' => $prix
            ]);
        } catch(PDOException $e) {
            echo "Erreur de création : " . $e->getMessage();
            return false;
        }
    }

    public function read($id) {
        try {
            $sql = "SELECT * FROM cours WHERE id_cours = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur de lecture : " . $e->getMessage();
            return false;
        }
    }

    public function update($id, $type_cours, $date_cours, $adresse, $prix) {
        try {
            $sql = "UPDATE cours 
                    SET type_cours = :type_cours,
                        date_cours = :date_cours,
                        adresse = :adresse,
                        prix = :prix
                    WHERE id_cours = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':type_cours' => $type_cours,
                ':date_cours' => $date_cours,
                ':adresse' => $adresse,
                ':prix' => $prix
            ]);
        } catch(PDOException $e) {
            echo "Erreur de mise à jour : " . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM cours WHERE id_cours = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch(PDOException $e) {
            echo "Erreur de suppression : " . $e->getMessage();
            return false;
        }
    }

    public function getAllCours() {
        try {
            $sql = "SELECT * FROM cours";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur de récupération : " . $e->getMessage();
            return false;
        }
    }
}
?>
