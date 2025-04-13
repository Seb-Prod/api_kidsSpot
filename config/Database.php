<?php

/**
 * Classe pour établir une connexion à la base de données MySQL en utilisant PDO.
 */
class Database
{
    /**
     * @var string L'adresse de l'hôte de la base de données.
     */
    private $host;

    /**
     * @var string Le nom de la base de données.
     */
    private $db_name;

    /**
     * @var string Le nom d'utilisateur pour la connexion à la base de données.
     */
    private $username;

    /**
     * @var string Le mot de passe pour la connexion à la base de données.
     */
    private $password;

    /**
     * @var PDO|null L'objet de connexion PDO. Initialisé à null.
     */
    public $connexion;

    /**
     * Constructeur de la classe Database.
     * Charge la configuration de la base de données depuis le fichier 'config.php'.
     */
    public function __construct()
    {
        // Charger la configuration
        $config = require_once(__DIR__ . '/config.php');

        // Initialiser les propriétés à partir de la configuration
        $this->host = $config['host'];
        $this->db_name = $config['db_name'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    /**
     * Établit et retourne une connexion PDO à la base de données.
     *
     * @return PDO|null L'objet de connexion PDO en cas de succès, null en cas d'erreur.
     */
    public function getConnexion()
    {
        $this->connexion = null;

        try {
            $this->connexion = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;charset=utf8",
                $this->username,
                $this->password,
            );
            // Configuration des erreurs PDO en mode exception pour une meilleure gestion
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur de connexion : {$e->getMessage()}";
        }
        return $this->connexion;
    }
}
