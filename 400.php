<?php
http_response_code(400);
echo json_encode([
    "status" => 400,
    "message" => "Format invalide : veuillez saisir des nombres valides pour latitude et longitude."
]);
