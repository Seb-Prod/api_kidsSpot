<?php
// Définir l'environnement ('development' ou 'production')
//$env = 'development'; // À commenter si en production
$env = 'production'; // A commenter si en dévelopement

// Chemin vers le fichier de configuration approprié
$config_file = __DIR__ . "/config.{$env}.php";

// Vérifier que le fichier existe
if (!file_exists($config_file)) {
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

// Return la configuration
return $config;
