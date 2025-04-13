<?php

/**
 * Génère une réponse d'erreur HTTP 404 Not Found au format JSON.
 *
 * Ce script est utilisé pour indiquer que la ressource demandée par le client
 * n'existe pas sur le serveur. Il renvoie un code de statut HTTP 404
 * et un corps de réponse au format JSON décrivant l'erreur.
 */

// Définit le code de réponse HTTP à 404 Not Found
http_response_code(404);

// Définit l'en-tête Content-Type pour indiquer que la réponse est au format JSON et utilise l'encodage UTF-8
header('Content-Type: application/json; charset=UTF-8');

// Crée un tableau associatif PHP qui représente la structure de l'erreur JSON
$erreur = [
    "code" => 404,
    "message" => "La ressource demandée n'a pas été trouvée."
];

// Vérifie si le paramètre 'invalid_coords' est présent dans l'URL (via la méthode GET)
// Si c'est le cas, cela signifie qu'il y a une information supplémentaire à fournir
// concernant la raison pour laquelle la ressource n'a pas été trouvée (dans ce contexte,
// potentiellement lié à des coordonnées invalides).
if (isset($_GET['invalid_coords'])) {
    $erreur["details"] = "Les coordonnées fournies dans l'URL n'étaient pas valides.";
}

// Encode le tableau PHP `$erreur` en une chaîne de caractères JSON
$json_erreur = json_encode($erreur);

// Affiche la chaîne JSON encodée. Ceci constitue le corps de la réponse HTTP.
echo $json_erreur;

// Termine l'exécution du script PHP pour s'assurer qu'aucune autre sortie n'est envoyée.
exit();
