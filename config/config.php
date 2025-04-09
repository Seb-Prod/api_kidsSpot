<?php
// Définir l'environnement ('development' ou 'production')
$env = 'development'; // À modifier selon l'environnement

// Configurations spécifiques aux environnements
$configurations = [
    'development' => [
        'host' => 'localhost',
        'db_name' => 'kids_spot',
        'username' => 'root',
        'password' => ''
    ],
    'production' => [
        'host' => 'production_server',
        'db_name' => 'kids_spot_prod',
        'username' => 'prod_user',
        'password' => 'strong_password'
    ]
];

// On vérifie que l’environnement existe bien
if (!isset($configurations[$env])) {
    die("Environnement de configuration '$env' introuvable.");
}

// On charge la bonne configuration dans $config
$config = $configurations[$env];
return $config;