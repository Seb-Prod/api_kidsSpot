<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Vérification que la méthode utilisée est correcte
// On s'assure que la requête envoyée au serveur est bien de type DELETE.
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // On instancie les lieux
    $lieux = new Lieux($db);

    // On récupère l'id du lieu
    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = json_decode(file_get_contents("php://input"));

    if (!empty($donnees->id)) {
        $lieux->id = $donnees->id;
        echo $lieux->id;
        if ($lieux->supprimer()) {
            // Si la suppression a réussi, on envoie un code de réponse 200 (Créé) et un message JSON de succès.
            http_response_code(200);
            echo json_encode(["message" => "La suppression a été effectuée"]);
        }else{
            // Si la création a échoué, on envoie un code de réponse 503 (Service Unavailable) et un message JSON d'échec.
            http_response_code(503);
            echo json_encode(["message" => "La suppression n'a pas été effectuée"]);
        }
    } else {
        // Si des erreurs de validation ont été détectées, on envoie un code de réponse 400 (Bad Request) et un message JSON contenant la liste des champs invalides.
        http_response_code(400);
        echo json_encode([
            "message" => "Données invalides."
        ]);
    }
} else {
    // Gestion de l'erreur si la méthode HTTP utilisée n'est pas POST
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
