<?php
// Définit le code de réponse HTTP à 400 Bad Request
http_response_code(400);

// Définit le type de contenu à JSON et l'encodage UTF-8
header('Content-Type: application/json; charset=UTF-8');

// Crée un tableau associatif représentant l'erreur
$erreur = [
    "code" => 400,
    "message" => "Requête invalide. Veuillez vérifier les paramètres fournis dans l'URL."
];

// Détail possible selon contexte
if (isset($_GET['reason']) && $_GET['reason'] === 'coords') {
    $erreur["details"] = "Les coordonnées fournies ne sont pas valides. Utilisez des nombres (ex : /autour/48.8566/2.3522).";
}

// Encode le tableau en JSON et l'affiche
echo json_encode($erreur);

// Termine l'exécution du script
exit();