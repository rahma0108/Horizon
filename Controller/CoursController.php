<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Cours.php';

class CoursController
{
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    // READ - Liste tous les cours
    public function listCours()
    {
        $sql = "SELECT * FROM cours";
        try {
            $liste = $this->db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // READ - Obtenir un cours par son ID
    public function getCoursById($id)
    {
        $sql = "SELECT * FROM cours WHERE id_cours = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $cours = $stmt->fetch(PDO::FETCH_ASSOC);
            return $cours ? $cours : false;
        } catch (PDOException $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    // CREATE - Ajouter un nouveau cours
    public function addCours($cours)
    {
        $sql = "INSERT INTO cours (type_cours, date_cours, adresse, prix)
                VALUES (:type_cours, :date_cours, :adresse, :prix)";

        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'type_cours' => $cours->getType(),
                'date_cours' => $cours->getDate(),
                'adresse' => $cours->getAdresse(),
                'prix' => $cours->getPrix()
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    // UPDATE - Modifier un cours existant
    public function updateCours($id, $data)
    {
        $sql = "UPDATE cours
                SET type_cours = :type_cours,
                    date_cours = :date_cours,
                    adresse = :adresse,
                    prix = :prix
                WHERE id_cours = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'type_cours' => $data['type_cours'],
                'date_cours' => $data['date_cours'],
                'adresse' => $data['adresse'],
                'prix' => $data['prix'],
                'id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    // DELETE - Supprimer un cours
    public function deleteCours($id)
    {
        $sql = "DELETE FROM cours WHERE id_cours = :id";
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

    // Statistics Methods
    public function getCourseStatistics()
    {
        $sql = "SELECT
                COUNT(*) as total_courses,
                AVG(prix) as average_price,
                MIN(prix) as min_price,
                MAX(prix) as max_price,
                COUNT(DISTINCT type_cours) as unique_types
                FROM cours";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    public function getCourseTypeDistribution()
    {
        $sql = "SELECT type_cours, COUNT(*) as count
                FROM cours
                GROUP BY type_cours";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    // Rechercher des cours par type
    public function searchCoursByType($searchTerm, $sortOrder = null)
    {
        $searchTerm = '%' . $searchTerm . '%';
        $sql = "SELECT * FROM cours WHERE type_cours LIKE :searchTerm";

        // Ajouter le tri par prix si spécifié
        if ($sortOrder === 'asc') {
            $sql .= " ORDER BY prix ASC";
        } elseif ($sortOrder === 'desc') {
            $sql .= " ORDER BY prix DESC";
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }

    // Lister tous les cours avec tri par prix
    public function listCoursWithSort($sortOrder = null)
    {
        $sql = "SELECT * FROM cours";

        // Ajouter le tri par prix si spécifié
        if ($sortOrder === 'asc') {
            $sql .= " ORDER BY prix ASC";
        } elseif ($sortOrder === 'desc') {
            $sql .= " ORDER BY prix DESC";
        }

        try {
            $liste = $this->db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>
