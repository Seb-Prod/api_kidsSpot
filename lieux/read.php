<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Vérification que la méthode utilisée est correct
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // On instancie les lieux
    $lieux = new Lieux($db);

    // Vérification de la présence de l'ID dans l'URL
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if ($id !== false && $id > 0) {
            // On récupère le lieu en fonction de l'ID
            $stmt = $lieux->obtenirLieu($id);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Initialisation du tableau
               
                $tableauLieux = [];
                $tableauLieux['lieu'] = [
                    "id" => $row['id_lieu'],
                    "nom" => json_decode('"' . $row['nom_lieu'] . '"'),
                    "description" => json_decode('"' . $row['description'] . '"'),
                    "type_lieu" => json_decode('"' . $row['type_lieu'] . '"'),
                    "est_evenement" => boolval($row['est_evenement']),
                    "date_evenement" => [
                        "debut" => $row['date_debut'],
                        "fin" => $row['date_fin']
                    ],
                    "adresse" => [
                        "adresse" => json_decode('"' . $row['adresse'] . '"'),
                        "ville" => json_decode('"' . $row['ville'] . '"'),
                        "code_postal" => json_decode('"' . $row['code_postal'] . '"'),
                        "telephone" => json_decode('"' . $row['telephone'] . '"'),
                        "site_web" => json_decode('"' . $row['site_web'] . '"'),
                    ],
                    "position" => [
                        "latitude" => round(floatval($row['latitude']), 5),
                        "longitude" => round(floatval($row['longitude']), 5)
                    ],

                    "equipements" => array_map('trim', explode(',', $row['equipements']))
                ];

                http_response_code(200);
                echo json_encode($tableauLieux);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Le lieu n'existe pas."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "L'ID fourni n'est pas valide."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "L'ID du lieu est manquant dans l'URL."]);
    }
} else {
    // Gestion de l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
