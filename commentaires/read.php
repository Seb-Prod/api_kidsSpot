<?php

/**
 * @file
 * API Endpoint pour lire un commentaire et d'une note par son id.
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On s'assure que la requête HTTP reçue par le serveur est bien de type GET.
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Commentaires.php';

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe commentaire, en passant l'objet de connexion à la base de données comme dépendance.
    $commentaire = new Commentaires($db);

    // Vérifie si le paramètre 'id' est présent dans l'URL de la requête GET.
    if (isset($_GET['id'])) {
        // Filtre et valide la valeur du paramètre 'id' pour s'assurer que c'est un entier.
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        // Vérifie si la validation a réussi et si l'ID est un nombre entier positif.
        if ($id !== false && $id > 0) {
            // Récupération du commentaire par ID
            $stmt = $commentaire->read($id);

            // Vérifie si l'exécution de la requête a réussi et s'il y a au moins un résultat.
            if ($stmt && $stmt->rowCount() > 0) {
                // Récupère la première (et unique) ligne de résultat sous forme de tableau associatif.
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Initialisation du tableau associatif pour stocker les informations.
                $tableauCommentaire = [];
                $tableauCommentaire['commentaire'] = [
                    "id" => $row['id_commentaire'],
                    "commentaire" => json_decode('"' . $row['commentaire'] . '"'),
                    "note" => json_decode('"' . $row['note'] . '"'),
                    "date" => [
                        "ajout" => json_decode('"' . $row['date_ajout'] . '"'),
                        "modification" => json_decode('"' . $row['date_modification'] . '"'),
                    ],
                    "user" => [
                        "id" => json_decode('"' . $row['id_user'] . '"'),
                        "pseudo" => json_decode('"' . $row['pseudo_user'] . '"'),
                    ],
                    "lieu" => [
                        "id" => json_decode('"' . $row['id_lieu'] . '"'),
                        "nom" => json_decode('"' . $row['nom_lieu'] . '"'),
                    ]
                ];
                // Envoie un code de réponse HTTP 200 (OK) indiquant que la requête a réussi.
                http_response_code(200);
                // Retourne le tableau du lieu au format JSON.
                echo json_encode($tableauCommentaire);
            } else {
                // Si aucun lieu correspondant à l'ID n'a été trouvé, on envoie un code de réponse HTTP 404 (Not Found).
                http_response_code(404);
                // Et on retourne un message JSON indiquant que le lieu n'existe pas.
                echo json_encode(["message" => "Le commentaire n'existe pas."]);
            }
        } else {
            // Si l'ID fourni n'est pas un entier positif valide, on envoie un code de réponse HTTP 400 (Bad Request).
            http_response_code(400);
            // Et on retourne un message JSON indiquant que l'ID n'est pas valide.
            echo json_encode(["message" => "L'ID fourni n'est pas valide."]);
        }
    } else {
        // Si le paramètre 'id' est manquant dans l'URL, on envoie un code de réponse HTTP 400 (Bad Request).
        http_response_code(400);
        // Et on retourne un message JSON indiquant que l'ID est manquant.
        echo json_encode(["message" => "L'ID du commentaire est manquant dans l'URL."]);
    }
} else {
    // Si la méthode de la requête HTTP n'est pas GET, on envoie un code de réponse HTTP 405 (Method Not Allowed).
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
