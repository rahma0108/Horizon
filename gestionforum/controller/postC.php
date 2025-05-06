<?php
require_once '../config.php';
require_once '../model/Post.php';
require_once '../model/ContentFilter.php';


class PostC
{
    public function addPost(Post $post)
{
    // Vérification du contenu vide
  /*  if (empty(trim($post->getContenu()))) {
        return "Le contenu ne peut pas être vide";
    }*/

    // Vérification des mots interdits
    if (ContentFilter::containsBadWords($post->getContenu())) {
        return "Désolé, votre post contient un terme inapproprié. Veuillez modifier votre texte.";
    }

    $db = config::getConnexion();
    try {
        $query = $db->prepare('INSERT INTO post (contenu, date, id_user) VALUES (:contenu, :date, :id_user)');
        $query->execute([
            'contenu' => $post->getContenu(),
            'date' => date('Y-m-d H:i:s'),
            'id_user' => $post->getIdUser()
        ]);
        return true;
    } catch (Exception $e) {
        return "Une erreur technique est survenue. Veuillez réessayer plus tard.";
    }
}

    public function listePosts()
    {
        $db = config::getConnexion();
        try {
            $query = $db->query('
                SELECT p.*, u.nom AS auteur 
                FROM post p 
                JOIN utilisateurs u ON p.id_user = u.id 
                ORDER BY p.date DESC
            ');
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function supprimerPost($id)
    {
        $db = config::getConnexion();
        try {
            $query = $db->prepare('DELETE FROM post WHERE id = :id');
            $query->execute(['id' => $id]);
            $queryPost = $db->prepare('DELETE FROM post WHERE id = :id');
            $queryPost->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function deletePost($id) {
        $db = config::getConnexion();
        try {
            $db->beginTransaction();
    
            // 1. Supprimer les signalements des commentaires de ce post
            $db->exec("DELETE sc FROM signaler_commentaire sc 
                      JOIN commentaire c ON sc.id_commentaire = c.id 
                      WHERE c.id_post = $id");
    
            // 2. Supprimer les réactions des commentaires de ce post
            $db->exec("DELETE cr FROM commentaire_reactions cr 
                      JOIN commentaire c ON cr.id_commentaire = c.id 
                      WHERE c.id_post = $id");
    
            // 3. Supprimer les commentaires du post
            $db->exec("DELETE FROM commentaire WHERE id_post = $id");
    
            // 4. Supprimer les signalements du post
            $db->exec("DELETE FROM signaler_post WHERE id_post = $id");
    
            // 5. Supprimer les réactions du post
            $db->exec("DELETE FROM post_reactions WHERE id_post = $id");
    
            // 6. Enfin supprimer le post
            $query = $db->prepare("DELETE FROM post WHERE id = :id");
            $query->execute(['id' => $id]);
    
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    public function updatePost($id, $contenu)
    {
        $sql = "UPDATE post SET contenu = ? WHERE id = ?";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$contenu, $id]);
    }
    public function reactToPost($id_user, $id_post, $reaction) {
        $db = config::getConnexion();
        $sql = "INSERT INTO post_reactions (id_user, id_post, reaction)
                VALUES (:id_user, :id_post, :reaction)
                ON DUPLICATE KEY UPDATE reaction = :reaction";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id_user' => $id_user,
            'id_post' => $id_post,
            'reaction' => $reaction
        ]);
    }


    public function signalerPost($id_post, $id_user, $raison, $details = null) {
        $db = config::getConnexion();
        try {
            $query = $db->prepare('INSERT INTO signaler_post (id_post, id_user, raison, details) 
                                 VALUES (:id_post, :id_user, :raison, :details)');
            $query->execute([
                'id_post' => $id_post,
                'id_user' => $id_user,
                'raison' => $raison,
                'details' => $details
            ]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors du signalement: " . $e->getMessage());
        }
    }
    
    public function getSignalisationsPost($id_post) {
        $db = config::getConnexion();
        $query = $db->prepare('SELECT COUNT(*) as nb FROM signaler_post WHERE id_post = :id_post');
        $query->execute(['id_post' => $id_post]);
        return $query->fetch()['nb'];
    }
    
}
?>
