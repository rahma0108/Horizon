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
    
    

   
}
