<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On s'assure que la requête envoyée au serveur est bien de type POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnexion();

    // On instancie les lieux
    $lieux = new Lieux($db);

    // On récupère les informations envoyés
    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = json_decode(file_get_contents("php://input"));

    // Liste des champs requis avec leurs règles de validation
    // Ce tableau définit les champs attendus et une fonction de validation pour chacun.
    $validation_regles = [
        'nom' => function ($val) {
            return !empty($val) && is_string($val) && strlen($val) <= 150;
        },
        'description' => function ($val) {
            return !empty($val) && is_string($val);
        },
        'adresse' => function ($val) {
            return !empty($val) && is_string($val) && strlen($val) <= 100;
        },
        'ville' => function ($val) {
            return !empty($val) && is_string($val) && strlen($val) <= 50;
        },
        'code_postal' => function ($val) {
            return !empty($val) && preg_match('/^[0-9]{5}$/', $val) && strlen($val) <= 5;
        },
        'latitude' => function ($val) {
            return is_numeric($val) && $val >= -90 && $val <= 90;
        },
        'longitude' => function ($val) {
            return is_numeric($val) && $val >= -180 && $val <= 180;
        },
        'telephone' => function ($val) {
            return !empty($val) && preg_match('/^[0-9]{10}$/', $val) <= 15;
        },
        'site_web' => function ($val) {
            return !empty($val) && filter_var($val, FILTER_VALIDATE_URL) && strlen($val) <= 255;
        },
        'id_type' => function ($val) {
            return is_numeric($val) && $val > 0 && $val <= 3;
        }
    ];

    // Vérification de la validité de tous les champs
    $erreurs = [];
    foreach ($validation_regles as $champ => $regle) {
        // Pour chaque champ, on vérifie s'il existe dans les données reçues et si la règle de validation est respectée.
        if (!isset($donnees->$champ) || !$regle($donnees->$champ)) {
            // Si la validation échoue, on ajoute le nom du champ au tableau des erreurs.
            $erreurs[] = $champ;
        }
    }

    // Si aucune erreur n'a été détectée lors de la validation
    if (empty($erreurs)) {
        // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $lieux.
        foreach (array_keys($validation_regles) as $champ) {
            $lieux->$champ = $donnees->$champ;
        }

        // On assigne les dates de création et de modification à la date actuelle.
        $lieux->date_creation = date('Y-m-d');
        $lieux->date_modification = date('Y-m-d');

        if ($lieux->creer()) {
            // Si la création a réussi, on envoie un code de réponse 201 (Créé) et un message JSON de succès.
            http_response_code(201);
            echo json_encode(["message" => "L'ajout a été effectué"]);
        } else {
            // Si la création a échoué, on envoie un code de réponse 503 (Service Unavailable) et un message JSON d'échec.
            http_response_code(503);
            echo json_encode(["message" => "L'ajout n'a pas été effectué"]);
        }
    } else {
        // Si des erreurs de validation ont été détectées, on envoie un code de réponse 400 (Bad Request) et un message JSON contenant la liste des champs invalides.
        http_response_code(400);
        echo json_encode([
            "message" => "Données invalides.",
            "erreurs" => $erreurs,
        ]);
    }
} else {
    // Gestion de l'erreur si la méthode HTTP utilisée n'est pas POST
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
