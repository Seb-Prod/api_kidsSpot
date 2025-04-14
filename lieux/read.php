<?php

/**
 * @file
 * Endpoint API pour récupérer les détails d'un lieu spécifique via son ID.
 *
 * Ce script PHP gère les requêtes HTTP de type GET. Il attend le paramètre 'id' dans l'URL
 * pour identifier le lieu à récupérer. La réponse est formatée en JSON.
 */

// --- Configuration des Headers HTTP ---

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- Vérification de la Méthode HTTP ---

// On s'assure que la requête HTTP reçue par le serveur est bien de type GET.
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';

    // --- Instanciation des Objets ---

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux, en passant l'objet de connexion à la base de données comme dépendance.
    $lieux = new Lieux($db);

    // --- Vérification de la Présence et de la Validité de l'ID ---

    // Vérifie si le paramètre 'id' est présent dans l'URL de la requête GET.
    if (isset($_GET['id'])) {
        // Filtre et valide la valeur du paramètre 'id' pour s'assurer que c'est un entier.
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        // Vérifie si la validation a réussi et si l'ID est un nombre entier positif.
        if ($id !== false && $id > 0) {
            // --- Récupération du Lieu par ID ---

            // Appelle la méthode 'obtenirLieu' de l'objet Lieux, en passant l'ID validé.
            // Cette méthode devrait retourner un objet PDOStatement contenant le résultat de la requête SQL.
            $stmt = $lieux->obtenirLieu($id);

            // --- Traitement du Résultat de la Requête ---

            // Vérifie si l'exécution de la requête a réussi et s'il y a au moins un résultat (le lieu existe).
            if ($stmt && $stmt->rowCount() > 0) {
                // Récupère la première (et unique) ligne de résultat sous forme de tableau associatif.
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Initialisation du tableau associatif pour stocker les informations du lieu.
                $tableauLieux = [];
                $tableauLieux['lieu'] = [
                    "id" => $row['id_lieu'],
                    "nom" => json_decode('"' . $row['nom_lieu'] . '"'),
                    "description" => json_decode('"' . $row['description'] . '"'),
                    "horaires" => json_decode('"' . $row['horaires'] . '"'),
                    "adresse" => [
                        "adresse" => json_decode('"' . $row['adresse'] . '"'),
                        "ville" => json_decode('"' . $row['ville'] . '"'),
                        "code_postal" => json_decode('"' . $row['code_postal'] . '"'),
                        "telephone" => json_decode('"' . $row['telephone'] . '"'),
                        "site_web" => json_decode('"' . $row['site_web'] . '"'),
                    ],
                    "type_lieu" => json_decode('"' . $row['type_lieu'] . '"'),
                    "est_evenement" => boolval($row['est_evenement']),
                    "date_evenement" => [
                        "debut" => $row['date_debut'],
                        "fin" => $row['date_fin']
                    ],
                    "position" => [
                        "latitude" => round(floatval($row['latitude']), 5),
                        "longitude" => round(floatval($row['longitude']), 5)
                    ],
                    // Ici, les JSON_OBJECT sont automatiquement transformés en tableau PHP
                    "equipements" => $row['equipements'] ? json_decode('[' . $row['equipements'] . ']') : [],
                    "ages" => $row['tranches_age'] ? json_decode('[' . $row['tranches_age'] . ']') : [],
                    "commentaires" => $row['commentaires'] ? json_decode('[' . $row['commentaires'] . ']') : [],
                    "note_moyenne" => floatval($row['note_moyenne']),
                    "nombre_commentaires" => intval($row['nombre_commentaires'])
                ];
                // Envoie un code de réponse HTTP 200 (OK) indiquant que la requête a réussi.
                http_response_code(200);
                // Retourne le tableau du lieu au format JSON.
                echo json_encode($tableauLieux);
            } else {
                // Si aucun lieu correspondant à l'ID n'a été trouvé, on envoie un code de réponse HTTP 404 (Not Found).
                http_response_code(404);
                // Et on retourne un message JSON indiquant que le lieu n'existe pas.
                echo json_encode(["message" => "Le lieu n'existe pas."]);
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
        echo json_encode(["message" => "L'ID du lieu est manquant dans l'URL."]);
    }
} else {
    // --- Gestion des Méthodes HTTP Non Autorisées ---

    // Si la méthode de la requête HTTP n'est pas GET, on envoie un code de réponse HTTP 405 (Method Not Allowed).
    http_response_code(405);
    // Et on retourne un message JSON indiquant que la méthode n'est pas autorisée pour cet endpoint.
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
