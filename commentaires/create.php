<?php

/**
 * @file
 * API Endpoint l'ajout d'un commentaire et d'une note sur un lieu par un utilisateur.
 *
 * Gère les requêtes HTTP POST. Le corps de la requête doit contenir un JSON avec les informations du nouveau commentaire et note sur un lieu. Retourne un statut HTTP et un message JSON indiquant le succès ou l'échec de l'opération, ainsi que les erreurs de validation si nécessaire.
 *
 */

// Configuration des Headers HTTP
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        'id_lieu' => function ($val) {
            return is_numeric($val) && $val > 0;
        },
        'commentaire' => function ($val) {
            return !empty($val) && is_string($val);
        },
        'note' => function ($val) {
            return is_numeric($val) && $val >= 0 && $val < 6;
        }
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

        // Assignation de l'id de l'user
        $commentaire->id_user = $donnees_utilisateur['id'];

        // Vérification si l'user à déjà commenté le lieu
        if ($commentaire->alreadyExists()) {
            // Si l'user à déjà mis un commentaire, envoie un code de réponse HTTP 409 (Conflict)
            http_response_code(409);
            echo json_encode(["message" => "Vous avez déjà commenté ce lieu."]);
            exit;
        }

        // Tentative de création du lieu dans la base de données.
        if ($commentaire->create()) {
            // Si la création a réussi, envoie un code de réponse HTTP 201 (Created)
            http_response_code(201);
            echo json_encode(["message" => "L'ajout a été effectué"]);
        } else {
            // Si la création a échoué, envoie un code de réponse HTTP 503 (Service Unavailable)
            http_response_code(503);
            echo json_encode(["message" => "L'ajout n'a pas été effectué"]);
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
