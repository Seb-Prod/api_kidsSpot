<?php
/**
 * @file
 * Classe pour gérer les tokens JWT.
 */

class JWT {
    // Clé secrète pour la signature des tokens
    private $cle_secrete;
    // Durée de validité du token en secondes (1 heure par défaut)
    private $duree_validite = 3600;
    
    /**
     * Constructeur de la classe JWT.
     * 
     * La clé secrète peut être définie ici ou récupérée d'une variable d'environnement.
     */
    public function __construct() {
        // Dans un environnement de production, utilisez une variable d'environnement
        // $this->cle_secrete = getenv('JWT_SECRET_KEY');
        
        // Pour l'exemple, nous utilisons une clé statique (à changer en production !)
        $this->cle_secrete = "votre_cle_secrete_tres_complexe_a_changer";
    }
    
    /**
     * Génère un token JWT avec les données fournies.
     * 
     * @param array $donnees Les données à inclure dans le token.
     * @return string Le token JWT généré.
     */
    public function generer($donnees) {
        $header = $this->encoderBase64(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]));
        
        $payload = $this->encoderBase64(json_encode([
            'iat' => time(), // Date de création
            'exp' => time() + $this->duree_validite, // Date d'expiration
            'data' => $donnees // Données utilisateur
        ]));
        
        $signature = $this->encoderBase64(
            hash_hmac('sha256', "$header.$payload", $this->cle_secrete, true)
        );
        
        return "$header.$payload.$signature";
    }
    
    /**
     * Vérifie et décode un token JWT.
     * 
     * @param string $token Le token JWT à vérifier.
     * @return array|false Les données du token si valide, false sinon.
     */
    public function verifier($token) {
        // Séparer les 3 parties du token
        $parties = explode('.', $token);
        if (count($parties) != 3) {
            return false;
        }
        
        list($header, $payload, $signature) = $parties;
        
        // Vérifier la signature
        $signature_calculee = $this->encoderBase64(
            hash_hmac('sha256', "$header.$payload", $this->cle_secrete, true)
        );
        
        if ($signature_calculee !== $signature) {
            return false;
        }
        
        // Décoder le payload
        $payload_decode = json_decode($this->decoderBase64($payload), true);
        
        // Vérifier l'expiration
        if ($payload_decode['exp'] < time()) {
            return false;
        }
        
        return $payload_decode['data'];
    }
    
    /**
     * Encode une chaîne en base64 URL-safe.
     * 
     * @param string $donnees Les données à encoder.
     * @return string Les données encodées.
     */
    private function encoderBase64($donnees) {
        return rtrim(strtr(base64_encode($donnees), '+/', '-_'), '=');
    }
    
    /**
     * Décode une chaîne en base64 URL-safe.
     * 
     * @param string $donnees Les données à décoder.
     * @return string Les données décodées.
     */
    private function decoderBase64($donnees) {
        return base64_decode(strtr($donnees, '-_', '+/'));
    }
}