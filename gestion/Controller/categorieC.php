<?php
include 'config.php';

class CategorieC
{
    // Afficher toutes les catégories
    public function afficherCategories()
    {
        $sql = "SELECT * FROM categorie_actualite";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll();
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }   
    }

    public function ajouterCategorie($nom) {
        $sql = "INSERT INTO categorie_actualite (nom_categorie) VALUES (:nom)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['nom' => $nom]);
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    
    
    // Supprimer une catégorie par ID
    public function supprimerCategorie($id_categorie)
    {
        $sql = "DELETE FROM categorie_actualite WHERE id_categorie = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id_categorie);
            $query->execute();
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Modifier une catégorie
    public function modifierCategorie($id_categorie, $nom_categorie)
    {
        $sql = "UPDATE categorie_actualite SET nom_categorie = :nom WHERE id_categorie = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $nom_categorie,
                'id' => $id_categorie
            ]);
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Récupérer une catégorie par ID (pour modification)
    public function recupererCategorie($id_categorie)
    {
        $sql = "SELECT * FROM categorie_actualite WHERE id_categorie = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id_categorie);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>
