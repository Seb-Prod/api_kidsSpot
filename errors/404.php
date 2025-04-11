<?php
// Définit le code de réponse HTTP à 404 Not Found
http_response_code(404);

// Définit le type de contenu à JSON et l'encodage UTF-8
header('Content-Type: application/json; charset=UTF-8');

// Crée un tableau associatif représentant l'erreur
$erreur = [
    "code" => 404,
    "message" => "La ressource demandée n'a pas été trouvée."
];

// Vérifie si des informations supplémentaires sur l'erreur sont disponibles
if (isset($_GET['invalid_coords'])) {
    $erreur["details"] = "Les coordonnées fournies dans l'URL n'étaient pas valides.";
}

// Encode le tableau en JSON et l'affiche
echo json_encode($erreur);

// Termine l'exécution du script
exit();