<?php
class Commentaire {
    private $id;
    private $contenu;
    private $date;
    private $id_post;
    private $id_user;

    public function __construct($contenu, $id_post, $id_user) {
        $this->contenu = $contenu;
        $this->id_post = $id_post;
        $this->id_user = $id_user;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getIdPost() {
        return $this->id_post;
    }

    public function getIdUser() {
        return $this->id_user;
    }
    public static function getAllCommentaires() {
        $db = config::getConnexion();
        $sql = "SELECT commentaire.*, utilisateurs.nom AS auteur FROM commentaire 
                JOIN utilisateurs ON commentaire.id_user = utilisateurs.id 
                ORDER BY date DESC";
        $req = $db->query($sql);
        return $req->fetchAll();
    }
    
    
}
