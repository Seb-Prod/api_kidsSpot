<?php
/**
 * @file
 * Middleware de vérification d'authentification et d'autorisation via JWT.
 *
 * Ce fichier contient des fonctions pour vérifier la présence et la validité
 * d'un token JWT dans les en-têtes de la requête, ainsi que pour vérifier
 * si l'utilisateur associé à ce token possède le niveau d'autorisation
 * (grade) requis pour accéder à une ressource.
 */

// Inclure la classe JWT pour la gestion des tokens.
include_once '../config/JWT.php';

/**
 * Vérifie l'authentification de l'utilisateur en recherchant et en validant un token JWT
 * dans l'en-tête 'Authorization' de la requête.
 *
 * La fonction récupère tous les en-têtes de la requête HTTP. Elle recherche ensuite
 * l'en-tête 'Authorization'. Si cet en-tête est présent et suit le format "Bearer {token}",
 * elle extrait le token. Un objet JWT est instancié en utilisant la configuration
 * de l'application (notamment la clé secrète). Le token est ensuite vérifié et décodé
 * à l'aide de la méthode `verifier()` de la classe JWT.
 *
 * @return array|false Les données décodées du token JWT si celui-ci est présent,
 * valide et non expiré. Retourne `false` si l'en-tête 'Authorization' est
 * manquant ou mal formaté, ou si le token n'est pas valide (signature
 * incorrecte ou expiré).
 */
function verifierAuthentification() {
    // Récupérer tous les en-têtes de la requête HTTP.
    $headers = getallheaders();
    // Récupérer la valeur de l'en-tête 'Authorization' si elle existe, sinon une chaîne vide.
    $auth_header = $headers['Authorization'] ?? '';

    // Vérifier si l'en-tête 'Authorization' est présent et correspond au format "Bearer {token}".
    if (!$auth_header || !preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
        return false; // L'en-tête est manquant ou mal formaté.
    }

    // Extraire le token JWT de la chaîne 'Bearer {token}'.
    $token = $matches[1];
    // Inclure le fichier de configuration pour récupérer les paramètres (notamment la clé secrète).
    $config = require '../config/config.php';
    // Créer une instance de la classe JWT en lui passant la configuration.
    $jwt = new JWT($config);

    // Vérifier et décoder le token JWT à l'aide de la méthode 'verifier' de la classe JWT.
    $donnees_token = $jwt->verifier($token);

    return $donnees_token; // Retourner les données du token décodé ou false si la vérification échoue.
}

/**
 * Vérifie si un utilisateur possède un niveau d'autorisation (grade) suffisant pour
 * effectuer une action ou accéder à une ressource.
 *
 * Cette fonction prend en entrée les données de l'utilisateur (généralement issues
 * du décodage du token JWT) et un niveau de grade minimum requis. Elle vérifie
 * si les données de l'utilisateur sont présentes et contiennent une clé 'grade'.
 * Si c'est le cas, elle compare le grade de l'utilisateur avec le grade minimum requis.
 *
 * @param array|false $donnees_utilisateur Un tableau associatif contenant les données
 * de l'utilisateur, incluant potentiellement son grade (par exemple, sous la
 * clé 'grade'). Peut être `false` si aucune information d'utilisateur n'est
 * disponible (par exemple, si l'authentification a échoué).
 * @param int $grade_minimum Un entier représentant le niveau de grade minimum requis
 * (par défaut 1). L'utilisateur doit avoir un grade supérieur ou égal à cette
 * valeur pour être autorisé. La signification des niveaux de grade est
 * spécifique à l'application.
 * @return bool Retourne `true` si l'utilisateur est authentifié, que ses données
 * contiennent un grade et que ce grade est supérieur ou égal au grade minimum
 * requis. Retourne `false` dans tous les autres cas (pas d'utilisateur, pas de
 * grade dans les données, ou grade insuffisant).
 */
function verifierAutorisation($donnees_utilisateur, $grade_minimum = 1) {
    // Vérifier si les données de l'utilisateur sont présentes et si elles contiennent la clé 'grade'.
    if (!$donnees_utilisateur || !isset($donnees_utilisateur['grade'])) {
        return false; // Pas d'informations d'utilisateur ou pas de grade trouvé.
    }

    // Comparer le grade de l'utilisateur avec le grade minimum requis.
    return $donnees_utilisateur['grade'] >= $grade_minimum;
}