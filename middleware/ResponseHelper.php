<?php

function sendErrorResponse($message, $status = 400) {
    http_response_code($status);
    echo json_encode([
        "status" => "error",
        "message" => $message
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function sendValidationErrorResponse($message, $erreurs, $status = 400) {
    http_response_code($status);
    echo json_encode([
        "status" => "error",
        "message" => $message,
        "erreurs" => $erreurs
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function sendSuccessResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode([
        "status" => "success",
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function sendCreatedResponse($message = "Ressource créée avec succès.") {
    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => $message
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function sendDeletedResponse(){
    http_response_code(204);
    exit;
}