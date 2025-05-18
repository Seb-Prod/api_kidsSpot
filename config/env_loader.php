<?php
/**
 * Fonctions pour gérer les variables d'environnement
 */

/**
 * Détermine si l'environnement est local ou production
 * 
 * @return string 'local' ou 'production'
 */
function determineEnvironment() {
    $hostname = gethostname();
    $server_addr = $_SERVER['SERVER_ADDR'] ?? '';
    
    $is_local = (
        $hostname === 'localhost' || 
        in_array($server_addr, ['127.0.0.1', '::1']) ||
        strpos($hostname, '.local') !== false ||
        strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false
    );
    
    return $is_local ? 'local' : 'production';
}

/**
 * Charge les variables d'environnement depuis un fichier .env
 * 
 * @param string $path Chemin vers le fichier .env
 * @return array Tableau associatif des variables chargées
 */
function loadEnv($path) {
    // Vérifier que le fichier existe
    if (!file_exists($path)) {
        throw new Exception("Le fichier d'environnement n'existe pas: $path");
    }
    
    // Lire le fichier
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env_vars = [];
    
    // Traiter chaque ligne
    foreach ($lines as $line) {
        // Ignorer les commentaires
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parser les lignes au format KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Supprimer les guillemets entourant les valeurs
            if (preg_match('/^"(.+)"$/', $value, $matches)) {
                $value = $matches[1];
            } elseif (preg_match("/^'(.+)'$/", $value, $matches)) {
                $value = $matches[1];
            }
            
            $env_vars[$key] = $value;
        }
    }
    
    return $env_vars;
}

/**
 * Charge les variables d'environnement appropriées selon l'environnement détecté
 *
 * @return array Variables d'environnement chargées
 */
function loadEnvironmentVars() {
    $env_mode = determineEnvironment();
    $env_file = __DIR__ . "/../.env.$env_mode";
    
    // Fallback au fichier .env par défaut si le fichier spécifique n'existe pas
    if (!file_exists($env_file)) {
        $env_file = __DIR__ . "/.env";
    }
    
    return loadEnv($env_file);
}