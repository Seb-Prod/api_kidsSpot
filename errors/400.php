<?php
/**
 * Génère une réponse d'erreur HTTP 400 Bad Request au format JSON.
 *
 * Ce script est conçu pour être utilisé lorsqu'une requête HTTP entrante
 * est considérée comme invalide en raison de problèmes avec les paramètres
 * fournis dans l'URL.
 */

// Définit le code de réponse HTTP à 400 Bad Request
http_response_code(400);

// Définit le type de contenu de la réponse HTTP à JSON avec l'encodage UTF-8
header('Content-Type: application/json; charset=UTF-8');

// Crée un tableau associatif PHP représentant la structure de l'erreur JSON
$erreur = [
    "code" => 400,
    "message" => "Requête invalide. Veuillez vérifier les paramètres fournis dans l'URL."
];

// Fournit un détail supplémentaire sur l'erreur si le paramètre 'reason' avec la valeur 'coords' est présent dans l'URL
if (isset($_GET['reason']) && $_GET['reason'] === 'coords') {
    $erreur["details"] = "Les coordonnées fournies ne sont pas valides. Utilisez des nombres (ex : /48.8566/2.3522).";
}

// Encode le tableau PHP `$erreur` en une chaîne JSON
$json_erreur = json_encode($erreur);

// Affiche la chaîne JSON encodée comme corps de la réponse HTTP
echo $json_erreur;

// Termine l'exécution du script PHP pour s'assurer qu'aucune autre sortie n'est envoyée
exit();