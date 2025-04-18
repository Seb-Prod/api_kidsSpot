<?php

/**
 * Envoie une réponse HTTP de succès avec des données.
 *
 * Cette fonction configure le code de réponse HTTP à `$status` (par défaut 200 OK)
 * et envoie une réponse JSON contenant un statut "success" et les données fournies.
 * L'option `JSON_UNESCAPED_UNICODE` est utilisée pour s'assurer que les caractères
 * Unicode sont correctement encodés dans la réponse JSON. Après l'envoi de la
 * réponse, la fonction termine l'exécution du script.
 *
 * @param mixed $data Les données à inclure dans la réponse JSON. Peut être un tableau,
 * un objet, une chaîne de caractères, etc.
 * @param int $status Le code de réponse HTTP à envoyer (par défaut: 200 OK).
 * @return void La fonction ne retourne rien. Elle envoie une réponse HTTP et termine le script.
 */
function sendSuccessResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode([
        "status" => "success",
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Envoie une réponse HTTP de succès indiquant qu'une modification a été effectuée.
 *
 * Cette fonction configure le code de réponse HTTP à 200 OK et envoie une réponse
 * JSON contenant un statut "success" et un message indiquant que la modification
 * a réussi. L'option `JSON_UNESCAPED_UNICODE` est utilisée pour s'assurer que
 * les caractères Unicode sont correctement encodés dans la réponse JSON. Après
 * l'envoi de la réponse, la fonction termine l'exécution du script.
 *
 * @return void La fonction ne retourne rien. Elle envoie une réponse HTTP et termine le script.
 */
function sendUpdatedResponse(){
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "La modification a été effectué" // Note: 'effectué' est correctement orthographié ici
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Envoie une réponse HTTP de création réussie (201 Created).
 *
 * Cette fonction configure le code de réponse HTTP à 201 Created et envoie une
 * réponse JSON contenant un statut "success" et un message indiquant que la
 * ressource a été créée avec succès. Un message personnalisé peut être fourni.
 * L'option `JSON_UNESCAPED_UNICODE` est utilisée pour s'assurer que les caractères
 * Unicode sont correctement encodés dans la réponse JSON. Après l'envoi de la
 * réponse, la fonction termine l'exécution du script.
 *
 * @param string $message Le message à inclure dans la réponse JSON (par défaut:
 * "Ressource créée avec succès.").
 * @return void La fonction ne retourne rien. Elle envoie une réponse HTTP et termine le script.
 */
function sendCreatedResponse($message = "Ressource créée avec succès.") {
    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => $message
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Envoie une réponse HTTP indiquant une suppression réussie (204 No Content).
 *
 * Cette fonction configure le code de réponse HTTP à 204 No Content. Ce code
 * indique que la requête a réussi mais qu'il n'y a pas de contenu à renvoyer.
 * Contrairement aux autres fonctions de réponse, celle-ci n'envoie pas de corps
 * JSON et termine l'exécution du script après avoir configuré le code de réponse.
 *
 * @return void La fonction ne retourne rien. Elle envoie un code de réponse HTTP et termine le script.
 */
function sendDeletedResponse(){
    http_response_code(204);
    exit;
}

/**
 * Envoie une réponse HTTP d'erreur avec un message.
 *
 * Cette fonction configure le code de réponse HTTP à `$status` (par défaut 400 Bad Request)
 * et envoie une réponse JSON contenant un statut "error" et un message d'erreur.
 * L'option `JSON_UNESCAPED_UNICODE` est utilisée pour s'assurer que les caractères
 * Unicode sont correctement encodés dans la réponse JSON. Après l'envoi de la
 * réponse, la fonction termine l'exécution du script.
 *
 * @param string $message Le message d'erreur à inclure dans la réponse JSON.
 * @param int $status Le code de réponse HTTP à envoyer (par défaut: 400 Bad Request).
 * @return void La fonction ne retourne rien. Elle envoie une réponse HTTP et termine le script.
 */
function sendErrorResponse($message, $status = 400) {
    http_response_code($status);
    echo json_encode([
        "status" => "error",
        "message" => $message
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Envoie une réponse HTTP d'erreur de validation avec un message et un tableau d'erreurs détaillées.
 *
 * Cette fonction configure le code de réponse HTTP à `$status` (par défaut 400 Bad Request)
 * et envoie une réponse JSON contenant un statut "error", un message d'erreur général
 * et un tableau associatif `$erreurs` contenant les détails des erreurs de validation
 * pour chaque champ. L'option `JSON_UNESCAPED_UNICODE` est utilisée pour s'assurer
 * que les caractères Unicode sont correctement encodés dans la réponse JSON. Après
 * l'envoi de la réponse, la fonction termine l'exécution du script.
 *
 * @param string $message Le message d'erreur général à inclure dans la réponse JSON.
 * @param array $erreurs Un tableau associatif où les clés sont les noms des champs
 * ayant échoué à la validation et les valeurs sont les messages d'erreur
 * correspondants.
 * @param int $status Le code de réponse HTTP à envoyer (par défaut: 400 Bad Request).
 * @return void La fonction ne retourne rien. Elle envoie une réponse HTTP et termine le script.
 */
function sendValidationErrorResponse($message, $erreurs, $status = 400) {
    http_response_code($status);
    echo json_encode([
        "status" => "error",
        "message" => $message,
        "errors" => $erreurs
    ], JSON_UNESCAPED_UNICODE);
    exit;
}