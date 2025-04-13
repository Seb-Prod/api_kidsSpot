<?php

/**
 * @file
 * API Endpoint pour la suppression d'un commentaire et d'une note sur un lieu par l'utilisateur.
 *
 * Gère les requêtes HTTP POST. Le corps de la requête doit contenir un JSON avec l'id du commentaire à supprimer. Seul l'auteur ou un administrateur peut supprimer un commentaire. Retourne un statut HTTP et un message JSON indiquant le succès ou l'échec de l'opération, ainsi que les erreurs de validation si nécessaire.
 *
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion du middleware d'authentification
include_once '../middleware/auth_middleware.php';

// Vérification de l'authentification
$donnees_utilisateur = verifierAuthentification();

if (!$donnees_utilisateur) {
    http_response_code(401);
    echo json_encode(["message" => "Accès non autorisé. Veuillez vous connecter."]);
    exit;
}

// Vérification du niveau d'autorisation (grade 1 minimum pour ajouter un commentaire)
if (!verifierAutorisation($donnees_utilisateur, 1)) {
    http_response_code(403);
    echo json_encode(["message" => "Vous n'avez pas les droits suffisants pour effectuer cette action."]);
    exit;
}

// Vérification de la Méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Commentaires.php';

    // --- Instanciation des Objets ---

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux, en passant l'objet de connexion à la base de données comme dépendance.
    $commentaire = new Commentaires($db);

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = json_decode(file_get_contents("php://input"));

    // --- Validation des Données Reçues ---
    $validation_regles = [
        'id' => function ($val) {
            return is_numeric($val) && $val > 0;
        },
    ];

    // Tableau pour stocker les erreurs de validation.
    $erreurs = [];
    // Parcours des règles de validation pour chaque champ.
    foreach ($validation_regles as $champ => $regle) {
        // Vérifie si le champ existe dans les données reçues et si la règle de validation est respectée.
        if (!isset($donnees->$champ) || !$regle($donnees->$champ)) {
            // Si la validation échoue, ajoute le nom du champ au tableau des erreurs.
            $erreurs[] = $champ;
        }
    }

    // --- Création du Commentaire et de la note si les Données sont Valides ---

    // Si aucune erreur de validation n'a été détectée.
    if (empty($erreurs)) {
        // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $lieux.
        foreach (array_keys($validation_regles) as $champ) {
            $commentaire->$champ = $donnees->$champ;
        }

        // Vérification si un commentaire existe
        if(!$commentaire->exists()){
            http_response_code(404);
            echo json_encode(["message" => "Ce commentaire n'existe pas."]);
            exit;
        }

        // Assignation de l'id de l'user
        $commentaire->id_user = $donnees_utilisateur['id'];

        // Vérification si s'est l'auteur du commentaire ou d'un admin
        if ($commentaire->getUserIdByCommentId($commentaire->id) === $donnees_utilisateur['id'] || $donnees_utilisateur['grade'] != 4) {
            http_response_code(403);
            echo json_encode(["message" => "Vous n'avez pas les droits pour effectuer cette action."]);
            exit;
        }

        if ($commentaire->delete()) {
            // Si la suppression a réussi, on envoie un code de réponse HTTP 200 (OK) et un message JSON de succès.
            http_response_code(200);
            echo json_encode(["message" => "La suppression a été effectuée"]);
        } else {
            // Si la suppression a échoué (par exemple, si l'ID n'existe pas ou s'il y a une erreur de base de données), on envoie un code de réponse HTTP 503 (Service Unavailable) et un message JSON d'échec.
            http_response_code(503);
            echo json_encode(["message" => "La suppression n'a pas été effectuée"]);
        }
    } else {
        // Si des erreurs de validation ont été détectées, envoie un code de réponse HTTP 400 (Bad Request)
        http_response_code(400);
        echo json_encode([
            "message" => "Les données fournies sont invalides.",
            "erreurs" => $erreurs,
        ]);
    }
} else {
    // Si la méthode de la requête HTTP n'est pas POST, envoie un code de réponse HTTP 405 (Method Not Allowed)
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
