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

public function supprimerCommentaire($id) {
    $sql = "DELETE FROM commentaire WHERE id = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);
        $query->execute();
    } catch (PDOException $e) {
        die('Erreur: ' . $e->getMessage());
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
public function deleteCommentaire($id)
    {
        $sql = "DELETE FROM commentaire WHERE id = ?";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
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
    
    public function signalerCommentaire($id_user, $id_commentaire, $raison)
    {
        $db = config::getConnexion();
        $sql = "INSERT INTO signaler_commentaire (id_user, id_commentaire, raison) VALUES (:id_user, :id_commentaire, :raison)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id_user' => $id_user,
            'id_commentaire' => $id_commentaire,
            'raison' => $raison
        ]);
    }
     
    
}