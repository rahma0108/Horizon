<?php
namespace App\Model;

class Utilisateur {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $adresse;
    private $password;
    private $date;
    private $role;

    public function __construct($id = null, $nom = "", $prenom = "", $email = "", $adresse = "", $password = "", $date = "", $role = "") {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->adresse = $adresse;
        $this->password = $password;
        $this->date = $date;
        $this->role = $role;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getDate() {
        return $this->date;
    }

    public function getRole() {
        return $this->role;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setRole($role) {
        $this->role = $role;
    }
}