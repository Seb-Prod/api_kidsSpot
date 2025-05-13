<?php

require_once __DIR__ . '/../../config/JWT.php';

class JWTTest
{
    private $jwt;
    private $verbose;
    public $ok = 0;
    public $ko = 0;

    public function __construct($verbose = true)
    {
        $this->verbose = $verbose;
        $config = ['jwt_secret' => 'ma_clé_secrète_de_test'];
        $this->jwt = new JWT($config);
    }

    public function runTests()
    {
        $this->log("\n=== Tests de la classe JWT ===\n");

        $this->testGenererEtVerifierToken();
        $this->testTokenInvalide();
        $this->testTokenExpiré();
        $this->testTokenModifié();
    }

    private function testGenererEtVerifierToken()
    {
        $this->log("\n--- Test : Génération et vérification d'un token valide ---\n");
        $donnees = ['id' => 123, 'email' => 'test@example.com'];
        $token = $this->jwt->generer($donnees);
        $result = $this->jwt->verifier($token);

        $this->assertEqual($result, $donnees, "Token valide");
    }

    private function testTokenInvalide()
    {
        $this->log("\n--- Test : Token avec structure invalide ---\n");
        $token = "invalid.token.structure";
        $result = $this->jwt->verifier($token);

        $this->assertEqual($result, false, "Token structure invalide");
    }

    private function testTokenExpiré()
    {
        $this->log("\n--- Test : Token expiré ---\n");

        $donnees = ['id' => 456];
        $header = $this->encoderBase64(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload = $this->encoderBase64(json_encode([
            'iat' => time() - 7200,
            'exp' => time() - 3600,
            'data' => $donnees
        ]));

        $signature = $this->encoderBase64(
            hash_hmac('sha256', "$header.$payload", 'ma_clé_secrète_de_test', true)
        );

        $token = "$header.$payload.$signature";
        $result = $this->jwt->verifier($token);

        $this->assertEqual($result, false, "Token expiré");
    }

    private function testTokenModifié()
    {
        $this->log("\n--- Test : Token modifié ---\n");
        $donnees = ['id' => 789];
        $token = $this->jwt->generer($donnees);
        $tokenParts = explode('.', $token);
        $tokenParts[1] = strrev($tokenParts[1]); // altération
        $tokenAltered = implode('.', $tokenParts);
        $result = $this->jwt->verifier($tokenAltered);

        $this->assertEqual($result, false, "Token altéré");
    }

    private function encoderBase64($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function assertEqual($actual, $expected, $label)
    {
        if ($actual === $expected) {
            $this->ok++;
            $this->log("$label : ✅ SUCCÈS\n");
        } else {
            $this->ko++;
            $this->log("$label : ❌ ÉCHEC (attendu: " . var_export($expected, true) . ", obtenu: " . var_export($actual, true) . ")\n");
        }
    }

    private function log($message)
    {
        if ($this->verbose) {
            echo $message;
        }
    }
}
