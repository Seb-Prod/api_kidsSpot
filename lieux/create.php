<?php

/**
 * @file
 * API Endpoint pour la création d'un nouveau lieu.
 *
 * Gère les requêtes HTTP POST. Le corps de la requête doit contenir un JSON avec les informations
 * du nouveau lieu à créer. Retourne un statut HTTP et un message JSON indiquant le succès
 * ou l'échec de l'opération, ainsi que les erreurs de validation si nécessaire.
 *
 * @date 2025-04-12
 * @author Votre Nom <votre.email@example.com>
 */

// --- Configuration des Headers HTTP ---

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

// Vérification du niveau d'autorisation (par exemple, grade 2 minimum pour créer un lieu)
if (!verifierAutorisation($donnees_utilisateur, 2)) {
    http_response_code(403);
    echo json_encode(["message" => "Vous n'avez pas les droits suffisants pour effectuer cette action."]);
    exit;
}

// --- Vérification de la Méthode HTTP ---

// On s'assure que la requête HTTP reçue par le serveur est bien de type POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Lieux.php';

    // --- Instanciation des Objets ---

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Lieux, en passant l'objet de connexion à la base de données comme dépendance.
    $lieux = new Lieux($db);

    // --- Récupération des Données Envoyées ---


    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = json_decode(file_get_contents("php://input"));

    // --- Validation des Données Reçues ---

    // Liste des champs requis avec leurs règles de validation (fonctions anonymes).
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

    // --- Création du Lieu si les Données sont Valides ---

    // Si aucune erreur de validation n'a été détectée.
    if (empty($erreurs)) {
        // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $lieux.
        foreach (array_keys($validation_regles) as $champ) {
            $lieux->$champ = $donnees->$champ;
        }

        // Assignation des dates de création et de modification à la date actuelle.
        $lieux->date_creation = date('Y-m-d');
        $lieux->date_modification = date('Y-m-d');

        // Tentative de création du lieu dans la base de données.
        if ($lieux->creer()) {
            // Si la création a réussi, envoie un code de réponse HTTP 201 (Created) et un message JSON de succès.
            http_response_code(201);
            echo json_encode(["message" => "L'ajout a été effectué"]);
        } else {
            // Si la création a échoué, envoie un code de réponse HTTP 503 (Service Unavailable) et un message JSON d'échec.
            http_response_code(503);
            echo json_encode(["message" => "L'ajout n'a pas été effectué"]);
        }
    } else {
        // --- Gestion des Erreurs de Validation ---

        // Si des erreurs de validation ont été détectées, envoie un code de réponse HTTP 400 (Bad Request) et un message JSON contenant la liste des champs invalides.
        http_response_code(400);
        echo json_encode([
            "message" => "Les données fournies sont invalides.",
            "erreurs" => $erreurs,
        ]);
    }
} else {
    // --- Gestion des Méthodes HTTP Non Autorisées ---

    // Si la méthode de la requête HTTP n'est pas POST, envoie un code de réponse HTTP 405 (Method Not Allowed) et un message JSON indiquant que la méthode n'est pas autorisée.    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
