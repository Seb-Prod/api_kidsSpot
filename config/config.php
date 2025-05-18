<?php
/**
 * Configuration de l'application utilisant les variables d'environnement
 * avec support pour plusieurs environnements (local et production)
 * 
 * @return array Un tableau associatif contenant la configuration de l'application.
 */

// Inclusion du chargeur d'environnement
require_once __DIR__ . '/env_loader.php';

try {
    // Déterminer l'environnement actuel
    $current_env = determineEnvironment();
    
    // Charger les variables d'environnement selon l'environnement détecté
    $env = loadEnvironmentVars();
    
    // Afficher l'environnement actuel dans les commentaires (pour déboguer)
    // error_log('Environnement actuel: ' . ($env['APP_ENV'] ?? 'non défini'));
    
    // Vérifier les variables requises
    $required_vars = [
        'DB_HOST', 'DB_NAME', 'DB_USER', 'JWT_SECRET',
        'MAIL_HOST', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_PORT'
    ];
    
    foreach ($required_vars as $var) {
        if (!isset($env[$var]) || empty($env[$var])) {
            throw new Exception("Variable d'environnement requise non définie: $var");
        }
    }

    // Ajouter les variables spécifiques à l'environnement
    if ($current_env === 'production') {
        // En production, on exige toutes les variables
        $required_vars = array_merge($required_vars, [
            'DB_PASSWORD'
        ]);
    }
    
    // Configuration de la base de données
    $db_config = [
        'host' => $env['DB_HOST'],
        'db_name' => $env['DB_NAME'],
        'username' => $env['DB_USER'],
        'password' => $env['DB_PASSWORD'] ?? '',
        'jwt_secret' => $env['JWT_SECRET']
    ];
    
    // Configuration des emails
    $mail_config = [
        'smtp_host' => $env['MAIL_HOST'],
        'smtp_auth' => isset($env['MAIL_AUTH']) ? filter_var($env['MAIL_AUTH'], FILTER_VALIDATE_BOOLEAN) : true,
        'smtp_username' => $env['MAIL_USERNAME'],
        'smtp_password' => $env['MAIL_PASSWORD'],
        'smtp_secure' => $env['MAIL_SECURE'] ?? 'tls',
        'smtp_port' => (int)$env['MAIL_PORT'],
        'from_email' => $env['MAIL_FROM_EMAIL'],
        'from_name' => $env['MAIL_FROM_NAME']
    ];
    
    // Retourner la configuration complète
    return array_merge($db_config, [
        'mail' => $mail_config,
        'environment' => $env['APP_ENV'] ?? determineEnvironment()
    ]);
    
} catch (Exception $e) {
    // Gestion des erreurs avec réponse JSON
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}