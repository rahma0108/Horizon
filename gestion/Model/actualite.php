<?php
class Actualite {
    private int $id_actualite;
    private string $titre;
    private string $contenu;
    private string $image_url;
    private string $date_publication;
    private int $id_categorie;

    // Constructeur sans ID (géré automatiquement par la BDD)
    public function __construct(string $titre, string $contenu, string $image_url, string $date_publication, int $id_categorie) {
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->image_url = $image_url;
        $this->date_publication = $date_publication;
        $this->id_categorie = $id_categorie;
    }

    // Getters
    public function getId(): int {
        return $this->id_actualite;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getContenu(): string {
        return $this->contenu;
    }

    public function getImageUrl(): string {
        return $this->image_url;
    }

    public function getDatePublication(): string {
        return $this->date_publication;
    }

    public function getIdCategorie(): int {
        return $this->id_categorie;
    }

    // Setters
    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function setContenu(string $contenu): void {
        $this->contenu = $contenu;
    }

    public function setImageUrl(string $image_url): void {
        $this->image_url = $image_url;
    }

    public function setDatePublication(string $date_publication): void {
        $this->date_publication = $date_publication;
    }

    public function setIdCategorie(int $id_categorie): void {
        $this->id_categorie = $id_categorie;
    }

    // Optionnel : setter pour id si nécessaire
    public function setId(int $id): void {
        $this->id_actualite = $id;
    }
}
?>
