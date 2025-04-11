<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Vérification que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // On instancie les lieux
    $lieux = new Lieux($db);

    // Vérification présence des paramètres
    if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
        http_response_code(400);
        echo json_encode(["message" => "Les paramètres lat et lng sont requis."]);
        exit();
    }

    // Convertie et validation des valeurs
    $latitude = filter_var($_GET['lat'], FILTER_VALIDATE_FLOAT);
    $longitude = filter_var($_GET['lng'], FILTER_VALIDATE_FLOAT);

    // Vérification si la conversion est valide
    if ($latitude === false || $longitude === false) {
        http_response_code(400);
        echo json_encode(["message" => "Les coordonnées doivent être des nombres"]);
        exit();
    }

    // Vérification si les coordonnées sont dans les plages valides
    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        http_response_code(400);
        echo json_encode(["message" => "Coordonnées hors limites. Latitude: -90 à 90, Longitude: -180 à 180."]);
        exit;
    }


    $latitude = round($latitude, 6);
    $longitude = round($longitude, 6);
    // Appel à la méthode avec géolocalisation
    $stmt = $lieux->obtenirLieuxAutour($latitude, $longitude);

    if ($stmt && $stmt->rowCount() > 0) {
        $tableauLieux = [];
        $tableauLieux['lieux'] = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $unLieux = [
                "id" => $id_lieu,
                "nom" => json_decode('"' . $nom_lieu . '"'),
                "adresse" => [
                    "adresse" => json_decode('"' . $adresse . '"'),
                    "code_postal" => json_decode('"' . $code_postal . '"'),
                    "ville" => json_decode('"' . $ville . '"'),
                ],
                "type" => json_decode('"' . $type_lieu . '"'),
                "est_evenement" => (bool)$est_evenement,
                "position" => [
                    "latitude" => round(floatval($row['latitude']), 5),
                    "longitude" => round(floatval($row['longitude']), 5),
                    "distance_km" => round(floatval($row['distance']), 5)
                ],

                "equipements" => array_map('trim', explode(',', $equipements))
            ];
            $tableauLieux['lieux'][] = $unLieux;
        }

        http_response_code(200);
        echo json_encode($tableauLieux, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Aucun lieu trouvé."]);
    }
} else {
    // Méthode non autorisée
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
