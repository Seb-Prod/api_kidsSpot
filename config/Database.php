<?php

/**
 * Classe pour établir une connexion à la base de données MySQL en utilisant PDO.
 *
 * Cette classe gère la connexion à une base de données MySQL en utilisant l'extension PDO (PHP Data Objects).
 * Elle lit les informations de connexion à partir d'un fichier de configuration externe ('config.php')
 * pour une meilleure séparation de la configuration et du code.
 */
class Database
{
    /**
     * @var string L'adresse ou le nom d'hôte du serveur de base de données.
     */
    private $host;

    /**
     * @var string Le nom de la base de données à laquelle se connecter.
     */
    private $db_name;

    /**
     * @var string Le nom d'utilisateur pour l'authentification à la base de données.
     */
    private $username;

    /**
     * @var string Le mot de passe pour l'authentification à la base de données.
     */
    private $password;

    /**
     * @var PDO|null L'objet de connexion PDO une fois établi, ou null si la connexion échoue.
     */
    public $connexion;

    /**
     * Constructeur de la classe Database.
     *
     * Ce constructeur est appelé lors de la création d'une instance de la classe Database.
     * Il a pour rôle de charger la configuration de la base de données depuis le fichier 'config.php'
     * situé dans le même répertoire que cette classe. Ensuite, il initialise les propriétés
     * de la classe ($host, $db_name, $username, $password) avec les valeurs lues depuis la configuration.
     *
     * @throws Exception Si le fichier 'config.php' n'est pas trouvé ou si la configuration est invalide.
     */
    public function __construct()
    {
        // Chemin vers le fichier de configuration
        $configFile = __DIR__ . '/config.php';

        // Vérifier si le fichier de configuration existe
        if (!file_exists($configFile)) {
            throw new Exception("Le fichier de configuration 'config.php' est introuvable.");
        }

        // Charger la configuration sous forme de tableau associatif
        $config = require($configFile);

        // Vérifier si les clés de configuration nécessaires existent
        if (!isset($config['host'], $config['db_name'], $config['username'], $config['password'])) {
            throw new Exception("Le fichier de configuration 'config.php' est incomplet. Veuillez vérifier les clés 'host', 'db_name', 'username' et 'password'.");
        }

        // Initialiser les propriétés à partir de la configuration
        $this->host = $config['host'];
        $this->db_name = $config['db_name'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    /**
     * Établit et retourne une connexion PDO à la base de données MySQL.
     *
     * Cette méthode tente d'établir une connexion à la base de données MySQL en utilisant les informations
     * de connexion stockées dans les propriétés de la classe. En cas de succès, elle retourne l'objet
     * de connexion PDO. En cas d'erreur lors de la tentative de connexion, elle affiche un message
     * d'erreur et retourne null. La méthode configure également PDO pour lancer des exceptions en cas
     * d'erreur, ce qui facilite la gestion des erreurs dans le code appelant.
     *
     * @return PDO|null L'objet de connexion PDO en cas de succès, null en cas d'erreur de connexion.
     */
    public function getConnexion()
    {
        // Initialiser la connexion à null avant de tenter d'en établir une nouvelle
        $this->connexion = null;

        try {
            // Créer une nouvelle instance de PDO pour établir la connexion
            $this->connexion = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            // Configuration des erreurs PDO en mode exception pour une meilleure gestion
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // En cas d'erreur de connexion, afficher le message d'erreur
            echo "Erreur de connexion à la base de données : {$e->getMessage()}";
        }
        // Retourner l'objet de connexion (ou null en cas d'échec)
        return $this->connexion;
    }
}

?>