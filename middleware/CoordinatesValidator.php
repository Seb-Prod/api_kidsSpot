<?php
/**
 * Valide et formate les coordonnées géographiques
 * 
 * @return array Tableau associatif contenant latitude et longitude validées
 */
function validateCoordinates() 
{
    // Vérifie si les paramètres 'lat' (latitude) et 'lng' (longitude) sont présents dans la requête GET.
    if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
        // Si l'un des paramètres est manquant, on envoie un code de réponse HTTP 400 (Bad Request)
        http_response_code(400);
        // Et on retourne un message JSON indiquant que les paramètres sont requis.
        echo json_encode(["message" => "Les paramètres lat et lng sont requis."]);
        // On termine l'exécution du script.
        exit();
    }
    
    // --- Conversion et Validation des Valeurs des Paramètres ---
    
    // Filtre et convertit la valeur du paramètre 'lat' en un nombre à virgule flottante.
    $latitude = filter_var($_GET['lat'], FILTER_VALIDATE_FLOAT);
    // Filtre et convertit la valeur du paramètre 'lng' en un nombre à virgule flottante.
    $longitude = filter_var($_GET['lng'], FILTER_VALIDATE_FLOAT);
    
    // Vérifie si la conversion des paramètres en nombres flottants a réussi.
    if ($latitude === false || $longitude === false) {
        // Si la conversion échoue (les valeurs ne sont pas des nombres valides), on envoie un code de réponse HTTP 400.
        http_response_code(400);
        // Et on retourne un message JSON indiquant que les coordonnées doivent être des nombres.
        echo json_encode(["message" => "Les coordonnées doivent être des nombres"]);
        // On termine l'exécution du script.
        exit();
    }
    
    // --- Validation des Plages de Coordonnées ---
    
    // Vérifie si les coordonnées fournies se trouvent dans les plages géographiques valides.
    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        // Si les coordonnées sont hors limites, on envoie un code de réponse HTTP 400.
        http_response_code(400);
        // Et on retourne un message JSON expliquant les limites valides pour la latitude et la longitude.
        echo json_encode(["message" => "Coordonnées hors limites. Latitude: -90 à 90, Longitude: -180 à 180."]);
        // On termine l'exécution du script.
        exit();
    }
    
    // --- Arrondissement des Coordonnées ---
    
    // Arrondit les valeurs de latitude et de longitude à 6 décimales pour une meilleure précision dans la requête.
    $latitude = round($latitude, 6);
    $longitude = round($longitude, 6);
    
    return [
        'latitude' => $latitude,
        'longitude' => $longitude
    ];
}