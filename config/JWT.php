<?php

/**
 * @file
 * Classe pour gérer les tokens JWT (JSON Web Tokens).
 *
 * Cette classe fournit des méthodes pour générer et vérifier des tokens JWT,
 * permettant ainsi une authentification et une transmission sécurisée d'informations
 * entre différentes parties d'une application ou entre des applications.
 * Elle utilise l'algorithme de signature HMAC SHA256 (HS256) pour garantir l'intégrité
 * du token.
 */

class JWT
{
    /**
     * @var string La clé secrète utilisée pour signer le token.
     * Il est crucial de conserver cette clé en sécurité.
     */
    private $cle_secrete;

    /**
     * @var int La durée de validité du token en secondes. Par défaut, elle est
     * définie à 3600 secondes (soit 1 heure).
     */
    private $duree_validite = 3600;

    /**
     * Constructeur de la classe JWT.
     *
     * Lors de l'instanciation de la classe, il est nécessaire de fournir une
     * configuration contenant la clé secrète pour la génération et la vérification
     * des tokens. La clé secrète est récupérée du tableau de configuration.
     *
     * @param array $config Un tableau associatif contenant la configuration de l'application.
     * Ce tableau doit contenir une clé 'jwt_secret' dont la valeur
     * est la clé secrète à utiliser.
     * @throws Exception Si la clé 'jwt_secret' n'est pas définie dans le tableau de configuration.
     */
    public function __construct($config)
    {
        // Récupérer la clé depuis la configuration
        if (isset($config['jwt_secret'])) {
            $this->cle_secrete = $config['jwt_secret'];
        } else {
            // Lancer une exception si la clé n'est pas fournie
            throw new Exception("Erreur: clé JWT non définie dans la configuration");
        }
    }

    /**
     * Génère un token JWT avec les données fournies.
     *
     * Cette méthode construit un token JWT en trois parties : l'en-tête (header),
     * la charge utile (payload) et la signature. L'en-tête spécifie le type de token
     * (JWT) et l'algorithme de signature (HS256). La charge utile contient les
     * données à inclure dans le token, y compris la date de création ('iat'),
     * la date d'expiration ('exp') et les données spécifiques fournies. La signature
     * est générée en utilisant l'algorithme HMAC SHA256 avec la clé secrète et garantit
     * que le token n'a pas été altéré. Les trois parties sont ensuite encodées en
     * base64 URL-safe et jointes par des points (.).
     *
     * @param array $donnees Un tableau associatif contenant les données à inclure
     * dans la charge utile du token. Ces données peuvent
     * représenter des informations sur l'utilisateur authentifié,
     * des permissions, etc.
     * @return string Le token JWT généré sous forme de chaîne de caractères.
     */
    public function generer($donnees)
    {
        // En-tête du JWT
        $header = $this->encoderBase64(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]));

        // Charge utile (payload) du JWT
        $payload = $this->encoderBase64(json_encode([
            'iat' => time(), // Timestamp de la création du token
            'exp' => time() + $this->duree_validite, // Timestamp de l'expiration du token
            'data' => $donnees // Les données à inclure dans le token
        ]));

        // Signature du JWT
        $signature = $this->encoderBase64(
            hash_hmac('sha256', "$header.$payload", $this->cle_secrete, true)
        );

        // Assembler le token JWT
        return "$header.$payload.$signature";
    }

    /**
     * Vérifie et décode un token JWT.
     *
     * Cette méthode prend un token JWT en entrée, le divise en ses trois parties
     * (en-tête, charge utile, signature), vérifie la signature pour s'assurer que
     * le token n'a pas été modifié, et vérifie la date d'expiration pour s'assurer
     * qu'il est toujours valide. Si toutes les vérifications réussissent, la méthode
     * décode la charge utile et retourne les données qu'elle contient. Sinon, elle
     * retourne false.
     *
     * @param string $token Le token JWT à vérifier et à décoder.
     * @return array|false Les données de la charge utile du token sous forme de
     * tableau associatif si le token est valide, false sinon.
     */
    public function verifier($token)
    {
        // Séparer les trois parties du token en utilisant le point comme délimiteur
        $parties = explode('.', $token);
        // Un token JWT valide doit contenir exactement trois parties
        if (count($parties) != 3) {
            return false;
        }

        // Assigner chaque partie à une variable distincte pour une meilleure lisibilité
        list($header, $payload, $signature) = $parties;

        // Calculer la signature attendue en utilisant l'en-tête, la charge utile et la clé secrète
        $signature_calculee = $this->encoderBase64(
            hash_hmac('sha256', "$header.$payload", $this->cle_secrete, true)
        );

        // Comparer la signature calculée avec la signature fournie dans le token
        if ($signature_calculee !== $signature) {
            // Si les signatures ne correspondent pas, le token est invalide (altéré)
            return false;
        }

        // Décoder la charge utile (payload) du token de la base64 au format JSON, puis en tableau associatif
        $payload_decode = json_decode($this->decoderBase64($payload), true);

        // Vérifier si la clé 'exp' (expiration) existe dans la charge utile décodée
        if (!isset($payload_decode['exp'])) {
            return false; // Le token est malformé s'il n'a pas d'expiration
        }

        // Vérifier si la date d'expiration du token est dans le passé
        if ($payload_decode['exp'] < time()) {
            // Si la date d'expiration est antérieure à l'heure actuelle, le token a expiré
            return false;
        }

        // Si toutes les vérifications ont réussi, retourner les données de la charge utile
        return $payload_decode['data'];
    }

    /**
     * Encode une chaîne de caractères en base64 URL-safe.
     *
     * Cette méthode prend une chaîne de caractères en entrée, l'encode en base64,
     * puis remplace les caractères '+' par '-', '/' par '_' et supprime les
     * caractères '=' de remplissage à la fin de la chaîne encodée. Cette
     * modification rend la chaîne base64 compatible avec les URL.
     *
     * @param string $donnees La chaîne de caractères à encoder.
     * @return string La chaîne de caractères encodée en base64 URL-safe.
     */
    private function encoderBase64($donnees)
    {
        return rtrim(strtr(base64_encode($donnees), '+/', '-_'), '=');
    }

    /**
     * Décode une chaîne de caractères encodée en base64 URL-safe.
     *
     * Cette méthode prend une chaîne de caractères encodée en base64 URL-safe,
     * remplace les caractères '-' par '+' et '_' par '/', puis la décode en
     * base64 standard. Le résultat est la chaîne de caractères originale.
     *
     * @param string $donnees La chaîne de caractères encodée en base64 URL-safe à décoder.
     * @return string La chaîne de caractères décodée.
     */
    private function decoderBase64($donnees)
    {
        return base64_decode(strtr($donnees, '-_', '+/'));
    }
}

?>