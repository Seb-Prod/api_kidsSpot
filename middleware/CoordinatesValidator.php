<?php
/**
 * Valide et formate les coordonnées géographiques (latitude et longitude) reçues via la méthode GET.
 *
 * Cette fonction vérifie si les paramètres 'lat' et 'lng' sont présents dans la requête GET.
 * Elle s'assure ensuite que ces paramètres sont des nombres flottants valides et qu'ils
 * se situent dans les plages géographiques acceptables pour la latitude (-90 à 90 degrés)
 * et la longitude (-180 à 180 degrés). Si une de ces validations échoue, une réponse
 * HTTP 400 (Bad Request) est envoyée avec un message d'erreur en JSON, et l'exécution
 * du script est interrompue. Si les coordonnées sont valides, elles sont arrondies à
 * 6 décimales pour une précision raisonnable et retournées sous forme de tableau associatif.
 *
 * @return array Un tableau associatif contenant les clés 'latitude' et 'longitude' avec
 * leurs valeurs validées et arrondies, ou la fonction termine l'exécution du
 * script en cas d'erreur de validation.
 */
function validateCoordinates(): array
{
    // --- Vérification de la présence des paramètres ---

    // Vérifie si les paramètres 'lat' (latitude) et 'lng' (longitude) existent dans le tableau $_GET.
    if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
        // Si l'un des paramètres est manquant, configure le code de réponse HTTP à 400 (Bad Request).
        http_response_code(400);
        // Encode et affiche un message JSON indiquant que les paramètres 'lat' et 'lng' sont requis.
        echo json_encode(["message" => "Les paramètres lat et lng sont requis."]);
        // Termine l'exécution du script pour éviter de poursuivre avec des données manquantes.
        exit();
    }

    // --- Conversion et Validation du format numérique ---

    // Récupère et filtre la valeur du paramètre 'lat' pour s'assurer qu'il s'agit d'un nombre à virgule flottante valide.
    $latitude = filter_var($_GET['lat'], FILTER_VALIDATE_FLOAT);
    // Récupère et filtre la valeur du paramètre 'lng' de la même manière pour la longitude.
    $longitude = filter_var($_GET['lng'], FILTER_VALIDATE_FLOAT);

    // Vérifie si la conversion en flottant a réussi pour les deux coordonnées.
    if ($latitude === false || $longitude === false) {
        // Si l'une des conversions échoue, configure le code de réponse HTTP à 400.
        http_response_code(400);
        // Encode et affiche un message JSON indiquant que les coordonnées doivent être des nombres valides.
        echo json_encode(["message" => "Les coordonnées doivent être des nombres"]);
        // Termine l'exécution du script.
        exit();
    }

    // --- Validation des limites géographiques ---

    // Vérifie si la latitude se trouve dans la plage valide de -90 à 90 degrés inclus.
    // Vérifie également si la longitude se trouve dans la plage valide de -180 à 180 degrés inclus.
    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        // Si les coordonnées sont hors des limites acceptables, configure le code de réponse HTTP à 400.
        http_response_code(400);
        // Encode et affiche un message JSON expliquant les plages de valeurs valides pour la latitude et la longitude.
        echo json_encode(["message" => "Coordonnées hors limites. Latitude: -90 à 90, Longitude: -180 à 180."]);
        // Termine l'exécution du script.
        exit();
    }

    // --- Formatage des coordonnées ---

    // Arrondit la latitude à 6 décimales pour une précision standard dans les applications géospatiales.
    $latitude = round($latitude, 6);
    // Arrondit la longitude à 6 décimales pour la cohérence.
    $longitude = round($longitude, 6);

    // Retourne un tableau associatif contenant la latitude et la longitude validées et formatées.
    return [
        'latitude' => $latitude,
        'longitude' => $longitude
    ];
}