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
                    // Décode la chaîne JSON potentiellement encodée pour le nom.
                    "nom" => json_decode('"' . $row['nom_lieu'] . '"'),
                    // Décode la chaîne JSON potentiellement encodée pour la description.
                    "description" => json_decode('"' . $row['description'] . '"'),
                    // Décode la chaîne JSON potentiellement encodée pour le type de lieu.
                    "adresse" => [
                        // Décode la chaîne JSON potentiellement encodée pour l'adresse.
                        "adresse" => json_decode('"' . $row['adresse'] . '"'),
                        // Décode la chaîne JSON potentiellement encodée pour la ville.
                        "ville" => json_decode('"' . $row['ville'] . '"'),
                        // Décode la chaîne JSON potentiellement encodée pour le code postal.
                        "code_postal" => json_decode('"' . $row['code_postal'] . '"'),
                        // Décode la chaîne JSON potentiellement encodée pour le téléphone.
                        "telephone" => json_decode('"' . $row['telephone'] . '"'),
                        // Décode la chaîne JSON potentiellement encodée pour le site web.
                        "site_web" => json_decode('"' . $row['site_web'] . '"'),
                    ],
                    "type_lieu" => json_decode('"' . $row['type_lieu'] . '"'),
                    // Convertit la valeur de 'est_evenement' en un booléen.
                    "est_evenement" => boolval($row['est_evenement']),
                    "date_evenement" => [
                        "debut" => $row['date_debut'],
                        "fin" => $row['date_fin']
                    ],
                    "position" => [
                        // Convertit et arrondit la latitude à 5 décimales.
                        "latitude" => round(floatval($row['latitude']), 5),
                        // Convertit et arrondit la longitude à 5 décimales.
                        "longitude" => round(floatval($row['longitude']), 5)
                    ],
                    // Explose la chaîne des équipements en un tableau, en supprimant les espaces blancs autour de chaque équipement.
                    "equipements" => array_map('trim', explode(',', $row['equipements']))
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
