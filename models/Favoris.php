<?php

/**
 * @file
 * Modèle de la classe Favoris.
 *
 * Cette classe fournit des méthodes pour interagir avec la table 'favoris' de la base de données, incluant la récupération des favoris d'un user, l'ajout d'un favoris, la suppression d'un favoris.
 */

class Favoris
{
    private $connexion;
    public $id;
    public $id_lieu;
    public $id_user;

    /**
     * Constructeur de la classe Favoris.
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
     * Ajoute un lieu en favoris
     */
    public function create()
    {
        $sql = "INSERT INTO favoris SET id_lieu=:id_lieu, id_user=:id_user";

        $query = $this->connexion->prepare($sql);

        // Nettoyage et sécurisation des données
        $this->id_lieu = htmlspecialchars(strip_tags($this->id_lieu));
        $this->id_user = htmlspecialchars(strip_tags($this->id_user));
        // Pas besoin de nettoyer date_ajout car on utilise NOW()

        // Liaison des valeurs
        $query->bindParam(":id_lieu", $this->id_lieu);
        $query->bindParam(":id_user", $this->id_user);

        // Exécution de la requête
        if ($query->execute()) {
            return true;
        }

        return false;
    }

    public function read($id) {}

    /**
     * Supprimer un favoris
     */
    public function delete() {
        $sql = "DELETE FROM favoris WHERE id_lieu=:id_lieu";

        $query = $this->connexion->prepare($sql);

        $this->id_lieu = htmlspecialchars(strip_tags($this->id_lieu));

        $query->bindParam(":id_lieu", $this->id_lieu);

        if ($query->execute()) {
            // Vérifie si une ligne a été affectée
            if ($query->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérification si un lieux est déjà dans les favoris de l'user
     */
    public function alreadyExists()
    {
        $sql = "SELECT COUNT(*) FROM favoris WHERE id_lieu = :id_lieu AND id_user = :id_user";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(":id_lieu", $this->id_lieu);
        $query->bindParam(":id_user", $this->id_user);
        $query->execute();
        return $query->fetchColumn() > 0;
    }

    /**
     * Vérification si un lieu existe
     */
    public function exists()
    {
        $sql = "SELECT COUNT(*) FROM lieux WHERE id = :id";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(":id", $this->id_lieu);
        $query->execute();
        return $query->fetchColumn() > 0;
    }
}
