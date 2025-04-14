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
     * Constructeur de la classe Commentaires.
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
     */
    public function create()
    {
        $sql = "INSERT INTO commentaires SET id_lieu=:id_lieu, commentaire=:commentaire, 
            note=:note, id_user=:id_user, date_ajout=NOW(), date_modification = NOW()";

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


    /**
     * Lire un commentaire en fonction de son ID.
     */
    public function read($id)
    {
        $sql = "SELECT
                c.id AS id_commentaire,
                c.commentaire,
                c.note,
                c.date_ajout,
                c.date_modification,
                c.id_user,
                u.pseudo AS pseudo_user,
                c.id_lieu,
                l.nom AS nom_lieu
            FROM 
                commentaires c
            JOIN 
                users u ON c.id_user = u.id
            JOIN
            lieux l ON c.id_lieu = l.id
            WHERE 
                c.id = :id";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $id);

        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Lire tous les commentaires sur un lieu par son ID
     */
    public function readAll($id)
    {
        $sql = "SELECT 
                c.id AS id_commentaire,
                c.commentaire,
                c.note,
                c.date_ajout,
                c.date_modification,
                c.id_user,
                u.pseudo AS pseudo_user,
                c.id_lieu,
                l.nom AS nom_lieu
            FROM 
                commentaires c
            JOIN 
                users u ON c.id_user = u.id
            JOIN
                lieux l ON c.id_lieu = l.id
            WHERE 
                c.id_lieu = :id_lieu
            ORDER BY
                c.date_ajout DESC";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id_lieu', $id);

        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifier un commentaire de la base de données en fonction de son ID.
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
     * Vérifie si s'est l'auteur du commentaire ou un administrateur
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

    /**
     * Récupère la moyenne des notes pour un lieu spécifique.
     */
    public function getMoyenneNotes($id_lieu)
    {
        $sql = "SELECT 
            AVG(note) AS moyenne_notes
        FROM 
            commentaires
        WHERE 
            id_lieu = :id_lieu";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id_lieu', $id_lieu);

        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }
}
