<?php

/**
 * @file
 * Modèle de la classe Users.
 * 
 * Cette classe fournit des méthodes pour interagir avec la table 'users' de la base de données.
 */

class Users
{
    /**
     * @var PDO $connexion Instance de connexion à la base de données.
     */
    private $connexion;

    /**
     * @var int $id Identifiant unique de l'user
     */
    public $id;

    /**
     * @var string $pseudo Pseudo de l'user
     */
    public $pseudo;

    /**
     * @var string $mail Email de l'user
     */
    public $mail;

    /**
     * @var string $telephone Numéro de téléphone de l'user
     */
    public $telephone;

    /**
     * @var string $mot_de_passe Mot de passe de l'user
     */
    public $mot_de_passe;

    /**
     * @var int $grade Grade de l'user (1 : user, 2 : superUser, 3 : spare, 4 : admin)
     */
    public $grade;

    /**
     * @var string $date_creation Date de création du compte
     */
    public $date_creation;

    /**
     * @var string $derniere_connexion Date de la derniere connexion
     */
    public $derniere_connexion;

    /**
     * @var int $tentatives_connexion Nombre de tentatives de connexion
     */
    public $tentatives_connexion;

    /**
     * @var bool $compte_verrouille Compte bloqué ou pas
     */
    public $compte_verrouille;

    /**
     * @var string $date_verrouillage Date de verrouillage du compte
     */
    public $date_verrouillage;

    /**
     * @var string $token_reinitialisation Token pour debloquer le compte
     */
    public $token_reinitialisation;

    /**
     * @var string $date_expiration_token Date d'expiration du token
     */
    public $date_expiration_token;

    /**
     * Constructeur de la classe Users.
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
     * Créer un nouvel utilisateur dans la base de données.
     * 
     * Prépare et exécute une requête SQL d'insertion pour ajouter un nouvel utilisateur
     * avec les informations fournies dans les propriétés de l'objet.
     * Les données sont nettoyées et sécurisées avant l'insertion.
     * 
     * @return bool Retourne `true` si l'insertion a réussi, `false` en cas d'échec.
     */
    public function creer()
    {
        $sql = "INSERT INTO users SET 
                pseudo=:pseudo, 
                mail=:mail, 
                telephone=:telephone, 
                mot_de_passe=:mot_de_passe, 
                grade=:grade, 
                date_creation=NOW()";

        $query = $this->connexion->prepare($sql);

        // Nettoyage et sécurisation des données
        $this->pseudo = htmlspecialchars(strip_tags($this->pseudo));
        $this->mail = htmlspecialchars(strip_tags($this->mail));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        // Le mot de passe sera haché avant d'être inséré
        $this->mot_de_passe = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
        $this->grade = $this->grade ?: 1; // Si grade non défini, utilisateur standard par défaut

        // Liaison des valeurs
        $query->bindParam(":pseudo", $this->pseudo);
        $query->bindParam(":mail", $this->mail);
        $query->bindParam(":telephone", $this->telephone);
        $query->bindParam(":mot_de_passe", $this->mot_de_passe);
        $query->bindParam(":grade", $this->grade);

        // Exécution de la requête
        if ($query->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Modifier un utilisateur existant dans la base de données.
     * 
     * Prépare et exécute une requête SQL de mise à jour pour modifier les informations
     * d'un utilisateur existant en utilisant l'ID stocké dans la propriété `$this->id`.
     * Les données sont nettoyées et sécurisées avant la mise à jour.
     * 
     * @return bool Retourne `true` si la mise à jour a réussi, `false` en cas d'échec
     * ou si aucun utilisateur avec cet ID n'a été trouvé.
     */
    public function modifier()
    {
        // Construction dynamique de la requête SQL pour ne mettre à jour que les champs fournis
        $sql = "UPDATE users SET ";
        $params = [];

        // Mise à jour conditionnelle des champs
        if (!empty($this->pseudo)) {
            $params[] = "pseudo = :pseudo";
        }
        if (!empty($this->mail)) {
            $params[] = "mail = :mail";
        }
        if (!empty($this->telephone)) {
            $params[] = "telephone = :telephone";
        }
        if (!empty($this->mot_de_passe)) {
            $params[] = "mot_de_passe = :mot_de_passe";
        }
        if (!empty($this->grade)) {
            $params[] = "grade = :grade";
        }
        if (!empty($this->tentatives_connexion)) {
            $params[] = "tentatives_connexion = :tentatives_connexion";
        }
        if (!empty($this->compte_verrouille)) {
            $params[] = "compte_verrouille = :compte_verrouille";
        }
        if (isset($this->date_verrouillage)) {
            $params[] = "date_verrouillage = :date_verrouillage";
        }
        if (!empty($this->token_reinitialisation)) {
            $params[] = "token_reinitialisation = :token_reinitialisation";
        }
        if (isset($this->date_expiration_token)) {
            $params[] = "date_expiration_token = :date_expiration_token";
        }

        // Ajout automatique de la date de modification
        $sql .= implode(", ", $params) . " WHERE id = :id";

        $query = $this->connexion->prepare($sql);

        // Nettoyage et sécurisation des données
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Liaison conditionnelle des paramètres
        $query->bindParam(":id", $this->id);

        if (!empty($this->pseudo)) {
            $this->pseudo = htmlspecialchars(strip_tags($this->pseudo));
            $query->bindParam(":pseudo", $this->pseudo);
        }
        if (!empty($this->mail)) {
            $this->mail = htmlspecialchars(strip_tags($this->mail));
            $query->bindParam(":mail", $this->mail);
        }
        if (!empty($this->telephone)) {
            $this->telephone = htmlspecialchars(strip_tags($this->telephone));
            $query->bindParam(":telephone", $this->telephone);
        }
        if (!empty($this->mot_de_passe)) {
            // Hachage du mot de passe avant mise à jour
            $this->mot_de_passe = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
            $query->bindParam(":mot_de_passe", $this->mot_de_passe);
        }
        if (!empty($this->grade)) {
            $this->grade = htmlspecialchars(strip_tags($this->grade));
            $query->bindParam(":grade", $this->grade);
        }
        if (!empty($this->tentatives_connexion)) {
            $this->tentatives_connexion = htmlspecialchars(strip_tags($this->tentatives_connexion));
            $query->bindParam(":tentatives_connexion", $this->tentatives_connexion);
        }
        if (!empty($this->compte_verrouille)) {
            $this->compte_verrouille = htmlspecialchars(strip_tags($this->compte_verrouille));
            $query->bindParam(":compte_verrouille", $this->compte_verrouille);
        }
        if (isset($this->date_verrouillage)) {
            $query->bindParam(":date_verrouillage", $this->date_verrouillage);
        }
        if (!empty($this->token_reinitialisation)) {
            $this->token_reinitialisation = htmlspecialchars(strip_tags($this->token_reinitialisation));
            $query->bindParam(":token_reinitialisation", $this->token_reinitialisation);
        }
        if (isset($this->date_expiration_token)) {
            $query->bindParam(":date_expiration_token", $this->date_expiration_token);
        }

        // Exécution de la requête
        if ($query->execute()) {
            // Vérifie si une ligne a été affectée
            if ($query->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtenir un utilisateur par son identifiant unique.
     *
     * Effectue une requête SQL pour récupérer les informations d'un utilisateur spécifique en utilisant son ID.
     *
     * @param int $id L'identifiant unique de l'utilisateur à récupérer.
     * @return PDOStatement|false Un objet PDOStatement contenant le résultat de la requête si elle réussit,
     * ou `false` en cas d'erreur d'exécution.
     */
    public function obtenirUser($id)
    {
        $sql = "SELECT 
                u.id,
                u.pseudo,
                u.mail,
                u.telephone,
                u.grade,
                u.date_creation,
                u.derniere_connexion,
                u.tentatives_connexion,
                u.compte_verrouille,
                t.nom AS type_user
                FROM
                users u
                JOIN
                types_users t ON u.grade = t.id
                WHERE
                u.id = :id";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $id);

        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }
}