<?php
// --- Configuration des Headers HTTP ---

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusion du middleware des réponses
include_once '../middleware/ResponseHelper.php';

// Vérification de la Méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

} else {

    sendErrorResponse("La méthode n'est pas autorisée.", 405);
}