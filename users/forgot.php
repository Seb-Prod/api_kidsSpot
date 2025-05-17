<?php
/**
 * Endpoint pour la demande de réinitialisation de mot de passe (POST).
 */

 /**
  * Configuration des Headers HTTP pour autoriser les requêtes CORS
  * et définir le type de contenu.
  */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Vérifie si la méthode de la requête est POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /**
     * Inclusion des fichiers nécessaires :
     * - Connexion à la base de données
     * - Modèle utilisateur
     * - Envoi de mail
     * - Aide pour les réponses HTTP
     */
    include_once '../config/Database.php';
    include_once '../models/Users.php';
    include_once '../middleware/Mailer.php';
    include_once '../middleware/ResponseHelper.php';

    /**
     * Création d'une instance de la base de données et récupération de la connexion.
     */
    $db = (new Database())->getConnexion();

    /**
     * Création d'une instance de la classe Users.
     */
    $user = new Users($db);

    /**
     * Décodage des données JSON reçues dans le corps de la requête.
     */
    $data = json_decode(file_get_contents("php://input"));

    /**
     * Vérifie si le champ "mail" est présent et non vide.
     */
    if (!empty($data->mail)) {
        $email = $data->mail;

        /**
         * Vérifie si l'e-mail existe en base de données.
         */
        if (!$user->emailExists($email)) {
            sendErrorResponse("Aucun utilisateur trouvé avec cet e-mail.", 404);
            exit;
        }

        /**
         * Récupère les informations de l'utilisateur correspondant à l'e-mail.
         */
        $stmt = $user->rechercherParEmail($email);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_user = $row['id'];

        /**
         * Génère un token de réinitialisation pour l'utilisateur.
         */
        $result = $user->genererTokenReinitialisation($id_user);
        if (!$result) {
            sendErrorResponse("Impossible de générer le token.", 500);
            exit;
        }

        $token = $result['token'];

        /**
         * Prépare le contenu de l'e-mail (HTML et texte brut).
         */
        $html = "<p>Voici votre code de réinitialisation : <strong>$token</strong></p><p>Ce code est valable pendant 20 minutes.</p>";
        $texte = "Votre code de réinitialisation est : $token (valide 20 minutes)";

        /**
         * Envoie de l'e-mail de réinitialisation.
         */
        if (envoyerEmail($email, "Mot de passe oublié", $html, $texte)) {
            sendCreatedResponse("Un e-mail de réinitialisation a été envoyé.");
        } else {
            sendErrorResponse("Échec de l'envoi de l'e-mail.", 500);
        }
    } else {
        /**
         * Si l'e-mail n'est pas fourni, renvoie une erreur.
         */
        sendErrorResponse("L'e-mail est requis.", 400);
    }
} else {
    /**
     * Si la méthode de la requête n'est pas POST, envoie une réponse d'erreur.
     */
    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}