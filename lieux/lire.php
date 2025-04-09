<?php
// Headers requis
header("Access-Control_Allow-Origin: *");
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

    // On récupère les données
    $stmt = $lieux->lire();

    // On vérifie si on a au moins 1 lieux
    if($stmt->rowCount() > 0){
        // Initialisation du tableau
        $tableauLieux = [];
        $tableauLieux['lieux'] = [];

        // Parcourt les lieux
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $unLieux = [
                "id" => $id_lieu,
                "nom" => $nom_lieu,
                "equipements" => array_map('trim', explode(',', $equipements))
            ];
            $tableauLieux['lieux'][] = $unLieux;
        }

        http_response_code(200);
        echo json_encode($tableauLieux);
    }


} else {
    // Gestion de l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
