<?php
/**
 * @file
 * Middleware de vérification d'authentification.
 * 
 * Ce fichier vérifie la présence et la validité du token JWT
 * dans les en-têtes de la requête.
 */

include_once '../config/JWT.php';

/**
 * Vérifie l'authentification par token JWT.
 * 
 * @return array|false Les données du token si valide, false sinon.
 */
function verifierAuthentification() {
    // Récupérer tous les headers
    $headers = getallheaders();
    $auth_header = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    
    // Vérifier si le header Authorization est présent avec le format "Bearer {token}"
    if (!$auth_header || !preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
        return false;
    }
    
    $token = $matches[1];
    $config = require '../config/config.php';
    $jwt = new JWT($config);
    
    // Vérifier et décoder le token
    $donnees_token = $jwt->verifier($token);
    
    return $donnees_token;
}

/**
 * Vérifie si l'utilisateur a les droits suffisants.
 * 
 * @param array $donnees_utilisateur Les données de l'utilisateur.
 * @param int $grade_minimum Le grade minimum requis.
 * @return bool True si l'utilisateur a les droits, false sinon.
 */
function verifierAutorisation($donnees_utilisateur, $grade_minimum = 1) {
    if (!$donnees_utilisateur || !isset($donnees_utilisateur['grade'])) {
        return false;
    }
    
    return $donnees_utilisateur['grade'] >= $grade_minimum;
}