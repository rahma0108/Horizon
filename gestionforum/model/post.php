<?php
class Post {
    private $id;
    private $contenu;
    private $date;
    private $id_user;

    public function __construct($contenu, $id_user, $date = null) {
        $this->contenu = $contenu;
        $this->id_user = $id_user;
        $this->date = $date ?? date('Y-m-d H:i:s'); // Date actuelle si non fournie
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getIdUser() {
        return $this->id_user;
    }

    public function getDate() {
        return $this->date;
    }
    public static function getAllPosts() {
        $db = config::getConnexion();
        $sql = "SELECT post.*, utilisateurs.nom AS auteur FROM post 
                JOIN utilisateurs ON post.id_user = utilisateurs.id 
                ORDER BY date DESC";
        $req = $db->query($sql);
        return $req->fetchAll();
    }
    
    public static function getReactions($postId) {
        $db = config::getConnexion();
        $sql = "SELECT reaction, COUNT(*) as count FROM post_reactions WHERE id_post = :id GROUP BY reaction";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $postId]);
        return $stmt->fetchAll();
    }
    /*public static function getReportCount($postId) {
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM signaler_post WHERE id_post = :post_id");
            $stmt->execute(['post_id' => $postId]);
            return $stmt->fetch()['count'];
        } catch (PDOException $e) {
            error_log("Erreur dans getReportCount: " . $e->getMessage());
            return 0;
        }
    }
    public static function getPostWithReports($postId) {
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare("
                SELECT p.*, u.nom AS auteur, 
                (SELECT COUNT(*) FROM signaler_post sp WHERE sp.id_post = p.id) AS nb_reports
                FROM post p
                JOIN utilisateurs u ON p.id_user = u.id
                WHERE p.id = :post_id
            ");
            $stmt->execute(['post_id' => $postId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur dans getPostWithReports: " . $e->getMessage());
            return null;
        }
    }
    public function getSignalisationsPost($id_post) {
        $db = config::getConnexion();
        try {
            $query = $db->prepare('SELECT COUNT(*) as nb FROM signaler_post WHERE id_post = :id_post');
            $query->execute(['id_post' => $id_post]);
            return $query->fetch()['nb'];
        } catch (PDOException $e) {
            error_log("Erreur dans getSignalisationsPost: " . $e->getMessage());
            return 0;
        }
    }*/
}
