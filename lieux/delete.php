<?php

/**
 * @file
 * Endpoint API pour supprimer un lieu spécifique en fonction de son ID.
 *
 * Gère les requêtes HTTP DELETE. Le corps de la requête doit contenir un JSON avec l'ID du lieu à supprimer.
 * Retourne un statut HTTP et un message JSON indiquant le succès ou l'échec de l'opération.
 *
 */

// --- Configuration des Headers HTTP ---

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- Vérification de la Méthode HTTP ---

// On s'assure que la requête HTTP reçue par le serveur est bien de type DELETE.
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';

    // --- Instanciation des Objets ---

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux, en passant l'objet de connexion à la base de données comme dépendance.
    $lieux = new Lieux($db);

    // --- Récupération de l'ID du Lieu ---

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = json_decode(file_get_contents("php://input"));

    // --- Vérification de la Présence de l'ID ---

    // Vérifie si la propriété 'id' existe dans l'objet de données décodé.
    if (!empty($donnees->id)) {
        // Assigne l'ID du lieu à la propriété 'id' de l'objet Lieux.
        $lieux->id = filter_var($donnees->id, FILTER_VALIDATE_INT);

        if ($lieux->id !== false && $lieux->id > 0) {
            // --- Tentative de Suppression du Lieu ---

            // Appelle la méthode 'supprimer' de l'objet Lieux pour tenter de supprimer le lieu correspondant à l'ID.
            if ($lieux->supprimer()) {
                // Si la suppression a réussi, on envoie un code de réponse HTTP 200 (OK) et un message JSON de succès.
                http_response_code(200);
                echo json_encode(["message" => "La suppression a été effectuée"]);
            } else {
                // Si la suppression a échoué (par exemple, si l'ID n'existe pas ou s'il y a une erreur de base de données), on envoie un code de réponse HTTP 503 (Service Unavailable) et un message JSON d'échec.
                http_response_code(503);
                echo json_encode(["message" => "La suppression n'a pas été effectuée"]);
            }
        } else {
            // Si l'ID fourni n'est pas un entier positif valide, on envoie un code de réponse HTTP 400 (Bad Request).
            http_response_code(400);
            echo json_encode(["message" => "l'ID fourni est invalide."]);
        }
    } else {
        // --- Gestion de l'Absence de l'ID ---

        // Si la propriété 'id' est vide ou n'existe pas dans les données reçues, on envoie un code de réponse HTTP 400 (Bad Request) et un message JSON indiquant que les données sont invalides (l'ID est manquant).
        http_response_code(400);
        echo json_encode([
            "message" => "Données invalides."
        ]);
    }
} else {
    // --- Gestion des Méthodes HTTP Non Autorisées ---

    // Si la méthode de la requête HTTP n'est pas DELETE, on envoie un code de réponse HTTP 405 (Method Not Allowed).
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
