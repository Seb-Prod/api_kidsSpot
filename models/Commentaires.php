<?php 

/**
 * @file
 * Modèle de la classe Commentaires.
 *
 * Cette classe fournit des méthodes pour interagir avec la table 'commentaires' de la base de données, incluant la récupération des commentaires et de la moyenne des notes pour un lieu (par son id), l'ajout d'un commentaire et de sa note, la suppression d'un commentaire et la modification.
 */

 class Commentaires{
    private $connexion;
    public $id;
    public $id_lieu;
    public $commentaire;
    public $note;
    public $id_user;
    public $date_ajout;

    /**
     * Constructeur de la classe Lieux.
     *
     * Initialise l'instance de connexion à la base de données.
     *
     * @param PDO $db Instance de connexion PDO.
     */
    public function __construct($db)
    {
        $this->connexion = $db;
    }

    /**
     * Créer un nouveau commentaire et note pour un lieu.
     *
     * Prépare et exécute une requête SQL d'insertion pour ajouter un commentaire sur un lieu et de le noter avec les informations fournies dans les propriétés de l'objet.
     * Les données sont nettoyées et sécurisées avant l'insertion.
     *
     * @return bool Retourne `true` si l'insertion a réussi, `false` en cas d'échec.
     */
    public function create(){
        // Correction du double égal (==) par un seul égal (=)
        $sql = "INSERT INTO commentaires SET id_lieu=:id_lieu, commentaire=:commentaire, 
            note=:note, id_user=:id_user, date_ajout=NOW()";
    
        $query = $this->connexion->prepare($sql);
    
        // Nettoyage et sécurisation des données
        $this->id_lieu = htmlspecialchars(strip_tags($this->id_lieu));
        $this->commentaire = htmlspecialchars(strip_tags($this->commentaire));
        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->id_user = htmlspecialchars(strip_tags($this->id_user));
        // Pas besoin de nettoyer date_ajout car on utilise NOW()
    
        // Liaison des valeurs
        $query->bindParam(":id_lieu", $this->id_lieu);
        $query->bindParam(":commentaire", $this->commentaire);
        $query->bindParam(":note", $this->note);
        $query->bindParam(":id_user", $this->id_user);
        // Suppression de la liaison avec date_ajout car NOW() est utilisé dans la requête
        
        // Exécution de la requête
        if ($query->execute()) {
            return true;
        }
    
        return false;
    }


    public function read(){

    }

    public function update(){

    }

    public function delete(){

    }

    /**
     * Vérification si un commentaire existe déjà
     *
     * @return void
     */
    public function alreadyExists() {
        $sql = "SELECT COUNT(*) FROM commentaires WHERE id_lieu = :id_lieu AND id_user = :id_user";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(":id_lieu", $this->id_lieu);
        $query->bindParam(":id_user", $this->id_user);
        $query->execute();
        return $query->fetchColumn() > 0;
    }
 }