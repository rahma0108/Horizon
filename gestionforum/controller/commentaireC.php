<?php
include_once '../config.php';
include_once '../model/Commentaire.php';

class CommentaireC {
  
    public function listeCommentaires() {
        $db = config::getConnexion();
        try {
            $req = $db->query("SELECT * FROM commentaire ORDER BY date ASC");
            return $req->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function addCommentaire($c) {
       

        $db = config::getConnexion();
        /*if (ContentFilter::containsBadWords($post->getContenu())) {
            die('Contenu inapproprié détecté.');
        }*/
        


        try {
            $req = $db->prepare("INSERT INTO commentaire (contenu, date, id_post, id_user) VALUES (:contenu, NOW(), :id_post, :id_user)");
            $req->execute([
                'contenu' => $c->getContenu(),
                'id_post' => $c->getIdPost(),
                'id_user' => $c->getIdUser()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
        
    }
    public function getCommentairesByPostId($postId) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare("SELECT * FROM commentaire WHERE id_post = :id_post ORDER BY date ASC");
            $req->execute(['id_post' => $postId]);
            return $req->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
}

/*public function deleteCommentaire($id) {
    $db = config::getConnexion();
    try {
        $db->beginTransaction();
        
        // 1. Supprimer les réactions du commentaire
        $db->exec("DELETE FROM commentaire_reactions WHERE id_commentaire = $id");
        
        // 2. Supprimer le commentaire
        $query = $db->prepare("DELETE FROM commentaire WHERE id = :id");
        $query->execute(['id' => $id]);
        
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        throw new Exception("Erreur lors de la suppression : " . $e->getMessage());
    }
}

/*public function modifierCommentaire($commentaire, $id) {
    $sql = "UPDATE commentaire SET contenu = :contenu, date = :date WHERE id = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'contenu' => $commentaire->getContenu(),
            'date' => $commentaire->getDate(),
            'id' => $id
        ]);
    } catch (PDOException $e) {
        die('Erreur: ' . $e->getMessage());
    }
}

public function recupererCommentaire($id) {
    $sql = "SELECT * FROM commentaire WHERE id = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        die('Erreur: ' . $e->getMessage());
    }
}*/
public function deleteCommentaire($id) {
    $db = config::getConnexion();
    try {
        $db->beginTransaction();

        // 1. Supprimer les signalements du commentaire
        $db->exec("DELETE FROM signaler_commentaire WHERE id_commentaire = $id");

        // 2. Supprimer les réactions du commentaire
        $db->exec("DELETE FROM commentaire_reactions WHERE id_commentaire = $id");

        // 3. Supprimer le commentaire
        $query = $db->prepare("DELETE FROM commentaire WHERE id = :id");
        $query->execute(['id' => $id]);

        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        throw new Exception("Erreur lors de la suppression : " . $e->getMessage());
    }
}

    public function updateCommentaire($id, $contenu)
    {
        $sql = "UPDATE commentaire SET contenu = ? WHERE id = ?";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$contenu, $id]);
    }
    public function reactToCommentaire($id_user, $id_commentaire, $reaction) {
        $db = config::getConnexion();
        $sql = "INSERT INTO commentaire_reactions (id_user, id_commentaire, reaction)
                VALUES (:id_user, :id_commentaire, :reaction)
                ON DUPLICATE KEY UPDATE reaction = :reaction";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id_user' => $id_user,
            'id_commentaire' => $id_commentaire,
            'reaction' => $reaction
        ]);
    }
    
    public function signalerCommentaire($id_commentaire, $id_user, $raison, $details = null) {
        $db = config::getConnexion();
        try {
            // Vérifier si le commentaire existe
            $query = $db->prepare('SELECT id FROM commentaire WHERE id = :id_commentaire');
            $query->execute(['id_commentaire' => $id_commentaire]);
            if (!$query->fetch()) {
                throw new Exception("Le commentaire avec l'ID $id_commentaire n'existe pas");
            }
    
            // Vérifier si l'utilisateur existe
            $query = $db->prepare('SELECT id FROM utilisateurs WHERE id = :id_user');
            $query->execute(['id_user' => $id_user]);
            if (!$query->fetch()) {
                throw new Exception("L'utilisateur avec l'ID $id_user n'existe pas");
            }
    
            // Insérer le signalement
            $query = $db->prepare('INSERT INTO signaler_commentaire (id_commentaire, id_user, raison, details) 
                                 VALUES (:id_commentaire, :id_user, :raison, :details)');
            $result = $query->execute([
                'id_commentaire' => $id_commentaire,
                'id_user' => $id_user,
                'raison' => $raison,
                'details' => $details
            ]);
    
            if (!$result) {
                throw new Exception("Échec de l'insertion dans signaler_commentaire");
            }
            return true;
        } catch (Exception $e) {
            error_log("Erreur signalerCommentaire: " . $e->getMessage());
            throw new Exception("Erreur lors du signalement: " . $e->getMessage());
        }
    }
    
    public function getSignalisationsCommentaire($id_commentaire) {
        $db = config::getConnexion();
        $query = $db->prepare('SELECT COUNT(*) as nb FROM signaler_commentaire WHERE id_commentaire = :id_commentaire');
        $query->execute(['id_commentaire' => $id_commentaire]);
        return $query->fetch()['nb'];
    }
    public function userAlreadyReportedComment($id_user, $id_commentaire) {
        $db = config::getConnexion();
        $query = $db->prepare('SELECT COUNT(*) as nb FROM signaler_commentaire 
                              WHERE id_commentaire = :id_commentaire AND id_user = :id_user');
        $query->execute(['id_commentaire' => $id_commentaire, 'id_user' => $id_user]);
        return $query->fetch()['nb'] > 0;
    } 
    
}