<?php

/**
 * @file
 * API Endpoint pour la création d'un nouvel utilisateur.
 *
 * Gère les requêtes HTTP POST. Le corps de la requête doit contenir un JSON avec les informations
 * du nouvel utilisateur à créer. Retourne un statut HTTP et un message JSON indiquant le succès
 * ou l'échec de l'opération, ainsi que les erreurs de validation si nécessaire.
 *
 * @date 2025-04-12
 */

// --- Configuration des Headers HTTP ---

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- Vérification de la Méthode HTTP ---

// On s'assure que la requête HTTP reçue par le serveur est bien de type POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // --- Inclusion des Fichiers Nécessaires ---
    include_once '../config/Database.php';
    include_once '../models/Users.php';

    // --- Instanciation des Objets ---

    // Crée une nouvelle instance de la classe Database pour établir une connexion à la base de données.
    $database = new Database();
    $db = $database->getConnexion();

    // Crée une nouvelle instance de la classe Users, en passant l'objet de connexion à la base de données comme dépendance.
    $users = new Users($db);

    // --- Récupération des Données Envoyées ---

    // Les données envoyées au format JSON dans le corps de la requête sont décodées en un objet PHP.
    $donnees = json_decode(file_get_contents("php://input"));

    // --- Validation des Données Reçues ---

    // Liste des champs requis avec leurs règles de validation (fonctions anonymes).
    $validation_regles = [
        'pseudo' => function ($val) {
            return !empty($val) && is_string($val) && strlen($val) <= 50;
        },
        'mail' => function ($val) {
            return !empty($val) && filter_var($val, FILTER_VALIDATE_EMAIL) && strlen($val) <= 100;
        },
        'mot_de_passe' => function ($val) {
            // Au moins 8 caractères, une majuscule, une minuscule, un chiffre
            return !empty($val) && is_string($val) && 
                   strlen($val) >= 8 && 
                   preg_match('/[A-Z]/', $val) && 
                   preg_match('/[a-z]/', $val) && 
                   preg_match('/[0-9]/', $val);
        },
        'telephone' => function ($val) {
            // Le téléphone est optionnel, mais s'il est fourni, il doit être valide
            return $val === null || empty($val) || 
                  (is_string($val) && preg_match('/^[0-9]{10}$/', $val) && strlen($val) <= 20);
        }
    ];

    // Tableau pour stocker les erreurs de validation.
    $erreurs = [];
    // Parcours des règles de validation pour chaque champ.
    foreach ($validation_regles as $champ => $regle) {
        // Vérifie si le champ existe dans les données reçues et si la règle de validation est respectée.
        if ($champ === 'telephone') {
            // Traitement spécial pour le champ téléphone qui est optionnel
            if (isset($donnees->$champ) && !$regle($donnees->$champ)) {
                $erreurs[] = $champ;
            }
        } else {
            // Traitement pour les champs obligatoires
            if (!isset($donnees->$champ) || !$regle($donnees->$champ)) {
                $erreurs[] = $champ;
            }
        }
    }

    // Vérification d'unicité du pseudo et de l'email
    // Cette vérification nécessiterait d'implémenter des méthodes dans la classe Users pour vérifier si un pseudo ou un email existe déjà
    // Pour l'exemple, nous supposons que ces validations seront gérées par les contraintes de clé unique dans la base de données

    // --- Création de l'Utilisateur si les Données sont Valides ---

    // Si aucune erreur de validation n'a été détectée.
    if (empty($erreurs)) {
        // On assigne les valeurs des données reçues aux propriétés correspondantes de l'objet $users.
        $users->pseudo = $donnees->pseudo;
        $users->mail = $donnees->mail;
        $users->mot_de_passe = $donnees->mot_de_passe;
        
        // Le téléphone est optionnel
        if (isset($donnees->telephone) && !empty($donnees->telephone)) {
            $users->telephone = $donnees->telephone;
        }
        
        // Grade par défaut (1 = utilisateur standard)
        $users->grade = isset($donnees->grade) && is_numeric($donnees->grade) ? $donnees->grade : 1;
        
        // Tentative de création de l'utilisateur dans la base de données.
        if ($users->creer()) {
            // Si la création a réussi, envoie un code de réponse HTTP 201 (Created) et un message JSON de succès.
            http_response_code(201);
            echo json_encode(["message" => "L'utilisateur a été créé avec succès"]);
        } else {
            // Si la création a échoué, envoie un code de réponse HTTP 503 (Service Unavailable) et un message JSON d'échec.
            http_response_code(503);
            echo json_encode(["message" => "La création de l'utilisateur a échoué"]);
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

    // Si la méthode de la requête HTTP n'est pas POST, envoie un code de réponse HTTP 405 (Method Not Allowed) et un message JSON indiquant que la méthode n'est pas autorisée.
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}