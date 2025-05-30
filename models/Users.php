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

    public $opt_in_email;

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
            pseudo = :pseudo, 
            mail = :mail, 
            telephone = :telephone, 
            mot_de_passe = :mot_de_passe, 
            grade = :grade, 
            date_creation = NOW()";

        $query = $this->connexion->prepare($sql);

        $this->pseudo = htmlspecialchars(strip_tags($this->pseudo));
        $this->mail = htmlspecialchars(strip_tags($this->mail));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->mot_de_passe = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
        $this->grade = $this->grade ?: 1;

        $query->bindParam(":pseudo", $this->pseudo);
        $query->bindParam(":mail", $this->mail);
        $query->bindParam(":telephone", $this->telephone);
        $query->bindParam(":mot_de_passe", $this->mot_de_passe);
        $query->bindParam(":grade", $this->grade);

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
        // Construction dynamique de la requête SQL
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
        if (isset($this->tentatives_connexion)) { // Modifié de !empty à isset
            $params[] = "tentatives_connexion = :tentatives_connexion";
        }
        if (isset($this->derniere_connexion)) { // Ajouté pour derniere_connexion
            $params[] = "derniere_connexion = :derniere_connexion";
        }
        if (isset($this->compte_verrouille)) { // Modifié de !empty à isset
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
        if(isset($this->opt_in_email)){
            $params[] = "opt_in_email = :opt_in_email";
        }

        // Vérifier si des champs ont été spécifiés pour mise à jour
        if (empty($params)) {
            return false; // Aucun champ à mettre à jour, ne pas exécuter la requête
        }

        // Ajout des champs à mettre à jour
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
            $this->mot_de_passe = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
            $query->bindParam(":mot_de_passe", $this->mot_de_passe);
        }
        if (!empty($this->grade)) {
            $this->grade = htmlspecialchars(strip_tags($this->grade));
            $query->bindParam(":grade", $this->grade);
        }
        if (isset($this->tentatives_connexion)) {
            $query->bindParam(":tentatives_connexion", $this->tentatives_connexion);
        }
        if (isset($this->derniere_connexion)) {
            $query->bindParam(":derniere_connexion", $this->derniere_connexion);
        }
        if (isset($this->compte_verrouille)) {
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
        if(isset($this->opt_in_email)){
            $this->opt_in_email = htmlspecialchars(strip_tags($this->opt_in_email));
            $query->bindParam(":opt_in_email", $this->opt_in_email);
        }

        // Exécution de la requête
        if ($query->execute()) {
            return true;
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

    /**
     * Obtenir un utilisateur par son identifiant unique.
     *
     * Effectue une requête SQL pour récupérer les informations d'un utilisateur spécifique en utilisant son ID.
     *
     * @param int $id L'identifiant unique de l'utilisateur à récupérer.
     * @return PDOStatement|false Un objet PDOStatement contenant le résultat de la requête si elle réussit,
     * ou `false` en cas d'erreur d'exécution.
     */
    public function obtenirUserId($email)
    {
        $sql = "SELECT 
                u.id
                FROM
                users u
                WHERE
                u.mail = :mail";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':mail', $email);

        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Rechercher un utilisateur par son adresse email.
     * 
     * @param string $email L'adresse email à rechercher.
     * @return PDOStatement Le résultat de la requête.
     */
    public function rechercherParEmail($email)
    {
        $sql = "SELECT id, pseudo, mail, mot_de_passe, grade, compte_verrouille, tentatives_connexion 
            FROM users 
            WHERE mail = :mail";

        $query = $this->connexion->prepare($sql);
        $email = htmlspecialchars(strip_tags($email));
        $query->bindParam(':mail', $email);
        $query->execute();

        return $query;
    }

    /**
     * Vérifie si un pseudo existe déjà dans la base de données
     * 
     * @param string $pseudo Le pseudo à vérifier
     * @return bool Retourne true si le pseudo existe, false sinon
     */
    public function pseudoExists($pseudo = null)
    {
        try {
            // Utilise soit le pseudo passé en paramètre, soit celui de l'objet
            $pseudoToCheck = $pseudo ?: $this->pseudo;

            $query = "SELECT COUNT(*) FROM users WHERE pseudo = :pseudo";
            $stmt = $this->connexion->prepare($query);
            $stmt->bindParam(':pseudo', $pseudoToCheck, PDO::PARAM_STR);
            $stmt->execute();

            // Si le compte est supérieur à 0, le pseudo existe déjà
            return ($stmt->fetchColumn() > 0);
        } catch (PDOException $e) {
            // Log l'erreur et retourne false en cas d'échec
            //error_log("Erreur lors de la vérification du pseudo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si un email existe déjà dans la base de données
     * 
     * @param string $email L'email à vérifier
     * @return bool Retourne true si l'email existe, false sinon
     */
    public function emailExists($email = null)
    {
        try {
            // Utilise soit l'email passé en paramètre, soit celui de l'objet
            $emailToCheck = $email ?: $this->mail;

            $query = "SELECT COUNT(*) FROM users WHERE mail = :email";
            $stmt = $this->connexion->prepare($query);
            $stmt->bindParam(':email', $emailToCheck, PDO::PARAM_STR);
            $stmt->execute();

            // Si le compte est supérieur à 0, l'email existe déjà
            return ($stmt->fetchColumn() > 0);
        } catch (PDOException $e) {
            // Log l'erreur et retourne false en cas d'échec
            //error_log("Erreur lors de la vérification de l'email: " . $e->getMessage());
            return false;
        }
    }

    // Génère un token de réinitialisation
    public function genererTokenReinitialisation($id_user)
    {
        try {
            // Génère un token lisible de 6 caractères (chiffres + lettres)
            $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $token = '';
            for ($i = 0; $i < 6; $i++) {
                $token .= $caracteres[random_int(0, strlen($caracteres) - 1)];
            }

            // Expire dans 20 minutes
            $expiration = date('Y-m-d H:i:s', strtotime('+20 minutes'));

            // Sauvegarde dans la base
            return $this->sauvegarderTokenReinitialisation($id_user, $token, $expiration)
                ? ['token' => $token, 'expiration' => $expiration]
                : false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sauvegarderTokenReinitialisation($id_user, $token, $expiration)
    {
        try {
            $query = "UPDATE users 
              SET token_reinitialisation = :token, date_expiration_token = :expiration, compte_verrouille = 1
              WHERE id = :id_user";
            $stmt = $this->connexion->prepare($query);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':expiration', $expiration, PDO::PARAM_STR);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            // error_log("Erreur lors de l'enregistrement du token : " . $e->getMessage());
            return false;
        }
    }

    public function changePassword()
    {

        $query = "UPDATE users
            SET compte_verrouille = 0, mot_de_passe = :mot_de_passe, token_reinitialisation = null
            WHERE mail = :mail";

        $stmt = $this->connexion->prepare($query);

        $this->mot_de_passe = password_hash($this->mot_de_passe, PASSWORD_DEFAULT);
        $this->mail = htmlspecialchars(strip_tags($this->mail));

        $stmt->bindParam(":mot_de_passe", $this->mot_de_passe);
        $stmt->bindParam(":mail", $this->mail);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function verifyResetToken()
    {
        $query = "SELECT id FROM users
              WHERE mail = :mail
              AND token_reinitialisation = :token
              AND date_expiration_token > NOW()";

        $stmt = $this->connexion->prepare($query);

        // Sécurisation des données
        $this->mail = htmlspecialchars(strip_tags($this->mail));
        $this->token_reinitialisation = htmlspecialchars(strip_tags($this->token_reinitialisation));

        // Bind des paramètres
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':token', $this->token_reinitialisation);

        $stmt->execute();

        // Vérifie s’il y a un résultat
        return $stmt->rowCount() > 0;
    }

    /**
     * Récupère tous les utilisateurs qui ont opté pour recevoir des emails
     * 
     * @return PDOStatement L'objet PDOStatement contenant les résultats de la requête
     */
    public function getUsersWithEmailOptIn()
    {
        $query = "SELECT id, pseudo, mail FROM users WHERE opt_in_email = 1";

        $stmt = $this->connexion->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Compte le nombre d'utilisateurs qui ont opté pour recevoir des emails
     * 
     * @return int Le nombre d'utilisateurs avec opt_in_email = 1
     */
    public function countUsersWithEmailOptIn()
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE opt_in_email = 1";

        $stmt = $this->connexion->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$row['count'];
    }
}
