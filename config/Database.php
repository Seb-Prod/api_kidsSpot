<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $connexion;
    
    public function __construct()
    {
        // Charger la configuration
        $config = require_once(__DIR__ . '/config.php');
        
        // Initialiser les propriétés
        $this->host = $config['host'];
        $this->db_name = $config['db_name'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }
    
    public function getConnexion()
    {
        $this->connexion = null;
        
        try {
            $this->connexion = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;charset=utf8",
                $this->username,
                $this->password,
            );
        } catch (PDOException $e) {
            echo "Erreur de connexion : {$e->getMessage()}";
        }
        
        return $this->connexion;
    }
}