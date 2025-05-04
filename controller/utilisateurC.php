<?php
namespace App\Controller;
use App\Model\Utilisateur;
use App\Model\Databaseconfig;

class UtilisateurC {
    public function getUtilisateurs($id) {
        try {
            $db = Databaseconfig::getConnexion();
            $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$result) {
                return false;
            }
            return $result;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }

    public function addUtilisateurs(Utilisateur $utilisateur, $id) {
        try {
            $db = Databaseconfig::getConnexion();
            $sql = "INSERT INTO utilisateurs (id, nom, prenom, email, adresse, password, date, role) 
                    VALUES (:id, :nom, :prenom, :email, :adresse, :password, :date, :role)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'nom' => $utilisateur->getNom(),
                'prenom' => $utilisateur->getPrenom(),
                'email' => $utilisateur->getEmail(),
                'adresse' => $utilisateur->getAdresse(),
                'password' => $utilisateur->getPassword(),
                'date' => $utilisateur->getDate(),
                'role' => $utilisateur->getRole()
            ]);
            if ($stmt->rowCount() === 0) {
                throw new \Exception("Aucune insertion effectuée, vérifiez les données.");
            }
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage());
        }
    }

    public function updateUtilisateurs($oldId, Utilisateur $updatedUser, $newId) {
        try {
            $db = Databaseconfig::getConnexion();
            $sql = "UPDATE utilisateurs SET id = :newId, nom = :nom, prenom = :prenom, email = :email, adresse = :adresse, password = :password, date = :date, role = :role WHERE id = :oldId";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'newId' => $newId,
                'nom' => $updatedUser->getNom(),
                'prenom' => $updatedUser->getPrenom(),
                'email' => $updatedUser->getEmail(),
                'adresse' => $updatedUser->getAdresse(),
                'password' => $updatedUser->getPassword(),
                'date' => $updatedUser->getDate(),
                'role' => $updatedUser->getRole(),
                'oldId' => $oldId
            ]);
            if ($stmt->rowCount() === 0) {
                throw new \Exception("Aucune mise à jour effectuée, vérifiez l'ID.");
            }
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
        }
    }

    public function listeUtilisateurs() {
        try {
            $db = Databaseconfig::getConnexion();
            $stmt = $db->prepare("SELECT * FROM utilisateurs");
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $results;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération de la liste des utilisateurs : " . $e->getMessage());
        }
    }

    public function deleteUtilisateurs($id) {
        try {
            $db = Databaseconfig::getConnexion();
            $stmt = $db->prepare("DELETE FROM utilisateurs WHERE id = :id");
            $stmt->execute(['id' => $id]);
            if ($stmt->rowCount() === 0) {
                throw new \Exception("Aucun utilisateur supprimé, vérifiez l'ID.");
            }
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        }
    }
}