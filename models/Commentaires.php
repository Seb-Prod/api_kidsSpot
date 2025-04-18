<?php
class Commentaires
{
    /**
     * @var PDO Instance de la connexion à la base de données.
     */
    private $connexion;

    /**
     * @var int Identifiant unique du commentaire.
     */
    public $id;

    /**
     * @var int Identifiant du lieu auquel le commentaire est associé.
     */
    public $id_lieu;

    /**
     * @var string Contenu du commentaire.
     */
    public $commentaire;

    /**
     * @var int Note attribuée dans le commentaire.
     */
    public $note;

    /**
     * @var int Identifiant de l'utilisateur ayant créé le commentaire.
     */
    public $id_user;

    /**
     * @var string Date et heure de l'ajout du commentaire (format MySQL DATETIME).
     */
    public $date_ajout;

    /**
     * Constructeur de la classe Commentaires.
     *
     * @param PDO $db Instance de la connexion à la base de données.
     */
    public function __construct($db)
    {
        $this->connexion = $db;
    }

    /**
     * Crée un nouveau commentaire dans la base de données.
     *
     * @return bool True en cas de succès de la création, false en cas d'erreur.
     */
    public function create()
    {
        try {
            $sql = "INSERT INTO commentaires SET id_lieu=:id_lieu, commentaire=:commentaire, 
                note=:note, id_user=:id_user, date_ajout=NOW(), date_modification = NOW()";

            $query = $this->connexion->prepare($sql);

            $this->id_lieu = htmlspecialchars(strip_tags($this->id_lieu));
            $this->commentaire = htmlspecialchars(strip_tags($this->commentaire));
            $this->note = htmlspecialchars(strip_tags($this->note));
            $this->id_user = htmlspecialchars(strip_tags($this->id_user));

            $query->bindParam(":id_lieu", $this->id_lieu);
            $query->bindParam(":commentaire", $this->commentaire);
            $query->bindParam(":note", $this->note);
            $query->bindParam(":id_user", $this->id_user);


            return $query->execute();
        } catch (PDOException $e) {
            error_log("Erreur PDO dans create() : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lit les informations d'un commentaire spécifique à partir de son ID.
     * Inclut également le pseudo de l'utilisateur et le nom du lieu.
     *
     * @param int $id L'identifiant du commentaire à récupérer.
     * @return PDOStatement|false Le résultat de la requête PDO en cas de succès, false en cas d'erreur.
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
     * Lit tous les commentaires associés à un lieu spécifique, ordonnés par date d'ajout descendante.
     * Inclut également le pseudo de l'utilisateur et le nom du lieu.
     *
     * @param int $id L'identifiant du lieu pour lequel récupérer les commentaires.
     * @return PDOStatement|false Le résultat de la requête PDO en cas de succès, false en cas d'erreur.
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
     * Met à jour un commentaire existant dans la base de données.
     *
     * @return bool True en cas de succès de la mise à jour, false en cas d'erreur.
     */
    public function update()
    {
        try {
            $sql = "UPDATE commentaires 
            SET commentaire = :commentaire, note = :note, date_modification = NOW()
            WHERE id = :id";

            $query = $this->connexion->prepare($sql);

            $this->commentaire = htmlspecialchars(strip_tags($this->commentaire));
            $this->note = htmlspecialchars(strip_tags($this->note));

            $query->bindParam(":commentaire", $this->commentaire);
            $query->bindParam(":note", $this->note);
            $query->bindParam(":id", $this->id);

            // Exécution de la requête
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Erreur PDO dans update() : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un commentaire de la base de données en fonction de son ID.
     *
     * @return bool True si au moins une ligne a été affectée (suppression réussie), false en cas d'erreur.
     */
    public function delete()
    {
        try {
            $sql = "DELETE FROM commentaires WHERE id=:id";

            $query = $this->connexion->prepare($sql);

            $this->id = htmlspecialchars(strip_tags($this->id));
            $query->bindParam(":id", $this->id);

            $query->execute();

            return $query->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur PDO dans delete() : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si un commentaire existe déjà pour un lieu donné par un utilisateur spécifique.
     *
     * @return bool True si un commentaire existe déjà, false sinon.
     */
    public function alreadyExists()
    {
        try {
            $sql = "SELECT COUNT(*) FROM commentaires WHERE id_lieu = :id_lieu AND id_user = :id_user";
            $query = $this->connexion->prepare($sql);
            $query->bindParam(":id_lieu", $this->id_lieu);
            $query->bindParam(":id_user", $this->id_user);
            $query->execute();
            return $query->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erreur PDO dans alreadyExists() : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si un commentaire existe dans la base de données en fonction de son ID.
     *
     * @return bool True si le commentaire existe, false sinon.
     */
    public function exists()
    {
        try {
            $sql = "SELECT COUNT(*) FROM commentaires WHERE id = :id";
            $query = $this->connexion->prepare($sql);
            $query->bindParam(":id", $this->id);
            $query->execute();
            return $query->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erreur PDO dans exists() : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère l'ID de l'utilisateur qui a créé un commentaire spécifique.
     *
     * @param int $id_commentaire L'identifiant du commentaire.
     * @return int|null L'ID de l'utilisateur si trouvé, null sinon.
     */
    public function getUserIdByCommentId($id_commentaire)
    {
        try {
            $sql = "SELECT id_user FROM commentaires WHERE id = :id_commentaire";
            $query = $this->connexion->prepare($sql);
            $query->bindParam(":id_commentaire", $id_commentaire);
            $query->execute();

            if ($query->rowCount() > 0) {
                $row = $query->fetch(PDO::FETCH_ASSOC);
                return $row['id_user'];
            }

            return null;
        } catch (PDOException $e) {
            error_log("Erreur PDO dans getUserIdByCommentId() : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère la moyenne des notes pour un lieu donné.
     *
     * @param int $id_lieu L'identifiant du lieu.
     * @return PDOStatement|false Le résultat de la requête PDO contenant la moyenne des notes, false en cas d'erreur.
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

    /**
     * Vérifie si le commentaire actuel appartient à un utilisateur donné.
     *
     * @param int $userId L'identifiant de l'utilisateur à vérifier.
     * @return bool True si le commentaire appartient à l'utilisateur, false sinon.
     */
    public function isOwnedBy($userId): bool
    {
        $auteurId = $this->getUserIdByCommentId($this->id);
        return $auteurId === $userId;
    }

    /**
     * Détermine si un utilisateur a le droit de modifier ou supprimer le commentaire actuel.
     * Un utilisateur peut toujours modifier son propre commentaire.
     * Un utilisateur avec un grade supérieur ou égal à 4 peut supprimer n'importe quel commentaire.
     *
     * @param int $user_id L'identifiant de l'utilisateur effectuant l'action.
     * @param int $user_grade Le grade de l'utilisateur effectuant l'action.
     * @param string $action L'action à vérifier ('modify', 'delete', ou 'both'). Par défaut 'both'.
     * @return bool True si l'utilisateur a la permission, false sinon.
     */
    public function peutModifierOuSupprimer($user_id, $user_grade, $action = 'both')
    {
        $auteur_id = $this->getUserIdByCommentId($this->id);

        if ($user_id == $auteur_id) {
            return true;
        }

        if ($action == 'delete' && $user_grade >= 4) {
            return true;
        }

        return false;
    }
}
