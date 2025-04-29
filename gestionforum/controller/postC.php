<?php
require_once '../config.php';
require_once '../model/Post.php';
require_once '../model/ContentFilter.php';


class PostC
{
    public function addPost(Post $post)
{

    $badWord = ContentFilter::containsBadWords($post->getContenu());
    
    if ($badWord !== false) {
        // Retourne le mot interdit pour affichage dans la vue
        return "Le mot '$badWord' n'est pas autorisé dans les posts.";
    }



        $db = config::getConnexion();

        try {
            $query = $db->prepare('INSERT INTO post (contenu, date, id_user) VALUES (:contenu, :date, :id_user)');
            $query->execute([
                'contenu' => $post->getContenu(),
                'date' => date('Y-m-d H:i:s'),
                'id_user' => $post->getIdUser()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
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
    public function deletePost($id)
    {
        $sql = "DELETE FROM post WHERE id = ?";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
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

    public function signalerPost($id_user, $id_post, $raison)
{
    $db = config::getConnexion();
    $sql = "INSERT INTO signaler_post (id_user, id_post, raison) VALUES (:id_user, :id_post, :raison)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'id_user' => $id_user,
        'id_post' => $id_post,
        'raison' => $raison
    ]);
}

    
   
}
?>
