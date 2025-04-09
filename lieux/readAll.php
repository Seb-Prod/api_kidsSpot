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

    // On récupère les coordonnées passées dans l'URL
    $latitude = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
    $longitude = isset($_GET['lng']) ? floatval($_GET['lng']) : null;

    if ($latitude !== null && $longitude !== null) {
        // Appel à la méthode avec géolocalisation
        $stmt = $lieux->lireGeolocal($latitude, $longitude);

        if ($stmt->rowCount() > 0) {
            $tableauLieux = [];
            $tableauLieux['lieux'] = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $unLieux = [
                    "id" => $id_lieu,
                    "nom" => $nom_lieu,
                    "latitude" => floatval($latitude),
                    "longitude" => floatval($longitude),
                    "distance_km" => round($row['distance'], 2),
                    "equipements" => array_map('trim', explode(',', $equipements))
                ];
                $tableauLieux['lieux'][] = $unLieux;
            }

            http_response_code(200);
            echo json_encode($tableauLieux);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Aucun lieu trouvé."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Latitude et longitude sont requises."]);
    }
} else {
    // Méthode non autorisée
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}