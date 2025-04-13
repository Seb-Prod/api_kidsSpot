<?php

/**
 * @file
 * Modèle de la classe Commentaires.
 *
 * Cette classe fournit des méthodes pour interagir avec la table 'commentaires' de la base de données, incluant la récupération des commentaires et de la moyenne des notes pour un lieu (par son id), l'ajout d'un commentaire et de sa note, la suppression d'un commentaire et la modification.
 */

class Commentaires
{
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
    public function create()
    {
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

        // Exécution de la requête
        if ($query->execute()) {
            return true;
        }

        return false;
    }


    public function read() {}

    /**
     * Modifier un commentaire de la base de données en fonction de son ID.
     *
     * Prépare et exécute une requête SQL pour modifier un commentaire spécifique en utilisant l'ID stocké dans la propriété `$this->id`.
     * L'ID est nettoyé et sécurisé avant l'exécution de la requête.
     *
     * @return bool Retourne `true` si la suppression a réussi (au moins une ligne a été affectée),
     * `false` en cas d'échec ou si aucun commentaire avec cet ID n'a été trouvé.
     */
    public function update()
    {
        $sql = "UPDATE commentaires 
        SET commentaire = :commentaire, note = :note, date_modification = NOW()
        WHERE id = :id AND id_user = :id_user";

        $query = $this->connexion->prepare($sql);

        // Nettoyage et sécurisation des données
        $this->commentaire = htmlspecialchars(strip_tags($this->commentaire));
        $this->note = htmlspecialchars(strip_tags($this->note));

        // Liaison des valeurs
        $query->bindParam(":commentaire", $this->commentaire);
        $query->bindParam(":note", $this->note);
        $query->bindParam(":id_user", $this->id_user);
        $query->bindParam(":id", $this->id);

        // Exécution de la requête
        if ($query->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Supprimer un commentaire de la base de données en fonction de son ID.
     *
     * Prépare et exécute une requête SQL de suppression pour retirer un commentaire spécifique en utilisant l'ID stocké dans la propriété `$this->id`.
     * L'ID est nettoyé et sécurisé avant l'exécution de la requête.
     *
     * @return bool Retourne `true` si la suppression a réussi (au moins une ligne a été affectée),
     * `false` en cas d'échec ou si aucun commentaire avec cet ID n'a été trouvé.
     */
    public function delete()
    {
        $sql = "DELETE FROM commentaires WHERE id=:id";

        $query = $this->connexion->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $query->bindParam(":id", $this->id);

        if ($query->execute()) {
            // Vérifie si une ligne a été affectée
            if ($query->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérification si l'user à déjà mis un commentaire
     *
     * @return void
     */
    public function alreadyExists()
    {
        $sql = "SELECT COUNT(*) FROM commentaires WHERE id_lieu = :id_lieu AND id_user = :id_user";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(":id_lieu", $this->id_lieu);
        $query->bindParam(":id_user", $this->id_user);
        $query->execute();
        return $query->fetchColumn() > 0;
    }
    /**
     * Vérification si un commentaire existe
     *
     * @return void
     */
    public function exists()
    {
        $sql = "SELECT COUNT(*) FROM commentaires WHERE id = :id";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(":id", $this->id);
        $query->execute();
        return $query->fetchColumn() > 0;
    }

    /**
     * Récupération de l'id de l'user qui à émis le commentaire
     *
     * @param integer $id_commentaire
     * @return integer | null
     */
    public function getUserIdByCommentId($id_commentaire)
    {
        $sql = "SELECT id_user FROM commentaires WHERE id = :id_commentaire";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(":id_commentaire", $id_commentaire);
        $query->execute();

        if ($query->rowCount() > 0) {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row['id_user'];
        }

        return null; // Aucun commentaire trouvé
    }

    /**
     * Undocumented function
     *
     * @param integer $id_user_connecte
     * @param integer $grade_user_connecte
     * @return boolean
     */
    public function peutSupprimer($id_user_connecte, $grade_user_connecte)
    {
        // Récupérer l'auteur du commentaire
        $userIdAuteur = $this->getUserIdByCommentId($this->id);

        // Autoriser si : auteur ou admin
        if ($userIdAuteur === $id_user_connecte || $grade_user_connecte == 4) {
            return true;
        }
        return false;
    }
}
