<?php
/**
 * Configuration de l'application en fonction de l'environnement.
 *
 * Ce script détermine l'environnement actuel (développement ou production)
 * et charge le fichier de configuration correspondant.
 *
 * @return array Un tableau associatif contenant la configuration de l'application.
 * Retourne `null` si le fichier de configuration n'est pas trouvé
 * et envoie une réponse JSON d'erreur.
 */

// Définir l'environnement ('development' ou 'production')
//$env = 'development'; // À commenter si en production
$env = 'production'; // A commenter si en dévelopement

// Chemin vers le fichier de configuration approprié
$config_file = __DIR__ . "/config.{$env}.php";
$mail_config_file = __DIR__ . "/congig.mail.{$env}.php";

// Vérifier que le fichier existe
if (!file_exists($config_file) || !file_exists($mail_config_file)) {
    // Réponse d'erreur en JSON
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => "Fichier de configuration pour l'environnement '$env' introuvable."
    ]);
    exit;
}

// Charger la configuration
$config = require $config_file;
$mail_config = require $mail_config_file;

// Return la configuration
return array_merge($db_config, ['mail' => $mail_config]);
