<?php
class ActualiteC {
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=greenmove", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connexion échouée : " . $e->getMessage());
        }
    }

    // 🔹 Ajouter une actualité
    public function ajouterActualite($titre, $contenu, $image_url, $date_publication, $id_categorie) {
        $sql = "INSERT INTO actualites (titre, contenu, image_url, date_publication, id_categorie)
                VALUES (:titre, :contenu, :image_url, :date_publication, :id_categorie)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':titre' => $titre,
            ':contenu' => $contenu,
            ':image_url' => $image_url,
            ':date_publication' => $date_publication,
            ':id_categorie' => $id_categorie
        ]);
    }

    // 🔹 Modifier une actualité
    public function modifierActualite($id, $titre, $contenu, $image_url, $date_publication, $id_categorie) {
        $sql = "UPDATE actualites
                SET titre = :titre, contenu = :contenu, image_url = :image_url, date_publication = :date_publication, id_categorie = :id_categorie 
                WHERE id_actualite = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':titre' => $titre,
            ':contenu' => $contenu,
            ':image_url' => $image_url,
            ':date_publication' => $date_publication,
            ':id_categorie' => $id_categorie,
            ':id' => $id
        ]);
    }

    // 🔹 Supprimer une actualité
    public function supprimerActualite($id) {
        // Récupérer l'image pour la supprimer
        $stmt = $this->conn->prepare("SELECT image_url FROM actualites WHERE id_actualite = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && file_exists($row['image_url'])) {
            unlink($row['image_url']);
        }

        // Supprimer l'entrée
        $stmt = $this->conn->prepare("DELETE FROM actualites WHERE id_actualite = ?");
        $stmt->execute([$id]);
    }

    // 🔹 Récupérer toutes les actualités
    public function afficherActualites() {
        $stmt = $this->conn->query("SELECT * FROM actualites ORDER BY date_publication DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Récupérer une actualité par ID
    public function getActualiteById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM actualites WHERE id_actualite = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
