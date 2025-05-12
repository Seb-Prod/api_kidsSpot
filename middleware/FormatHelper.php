<?php

/**
 * Classe d'aide au formatage de données pour l'API.
 *
 * Cette classe fournit des méthodes statiques pour formater des tableaux de données
 * provenant de la base de données dans un format spécifique pour être renvoyé
 * en tant que réponse d'API. Elle inclut des méthodes pour formater les commentaires
 * et les informations sur les lieux (version légère et détaillée). Elle gère également
 * le décodage sécurisé de chaînes JSON potentiellement encodées et le parsing de
 * chaînes séparées par des virgules.
 */
class FormatHelper
{
    /**
     * Formate les données d'un commentaire pour la réponse de l'API.
     *
     * Prend un tableau associatif représentant une ligne de données de commentaire
     * et retourne un tableau formaté contenant les informations pertinentes du
     * commentaire, de l'utilisateur et du lieu associé. Les identifiants sont
     * castés en entiers et les chaînes potentiellement encodées en JSON sont
     * décodées de manière sécurisée.
     *
     * @param array $row Un tableau associatif contenant les données du commentaire,
     * avec les clés possibles : 'id_commentaire', 'commentaire', 'note',
     * 'date_ajout', 'date_modification', 'id_user', 'pseudo_user',
     * 'id_lieu', 'nom_lieu'. Les clés manquantes sont traitées comme null.
     * @return array Un tableau formaté contenant les informations du commentaire.
     */
    public static function commentaire(array $row): array
    {
        return [
            "id" => (int)($row['id_commentaire'] ?? 0),
            "commentaire" => self::safeJsonDecode($row['commentaire'] ?? ''),
            "note" => (int)($row['note'] ?? 0),
            "date" => [
                "ajout" => self::safeJsonDecode($row['date_ajout'] ?? ''),
                "modification" => self::safeJsonDecode($row['date_modification'] ?? ''),
            ],
            "user" => [
                "id" => (int)($row['id_user'] ?? 0),
                "pseudo" => self::safeJsonDecode($row['pseudo_user'] ?? ''),
            ],
            "lieu" => [
                "id" => (int)($row['id_lieu'] ?? 0),
                "nom" => self::safeJsonDecode($row['nom_lieu'] ?? ''),
            ]
        ];
    }

    /**
     * Formate les données d'un lieu pour une réponse d'API légère (liste).
     *
     * Prend un tableau associatif représentant une ligne de données de lieu et
     * retourne un tableau formaté contenant les informations essentielles du lieu
     * pour une liste ou un aperçu. Les identifiants sont castés en entiers, les
     * chaînes potentiellement encodées en JSON sont décodées de manière sécurisée,
     * les types et les équipements sont parsés, et les coordonnées GPS sont arrondies.
     *
     * @param array $row Un tableau associatif contenant les données du lieu, avec les
     * clés possibles : 'id_lieu', 'nom_lieu', 'horaires', 'description',
     * 'adresse', 'code_postal', 'ville', 'type_lieu', 'est_evenement',
     * 'date_debut', 'date_fin', 'latitude', 'longitude', 'distance',
     * 'equipements', 'tranches_age'. Les clés manquantes sont traitées
     * comme null.
     * @return array Un tableau formaté contenant les informations légères du lieu.
     */
    public static function lieuLight(array $row): array
    {
        return [
            "id" => (int)($row['id_lieu'] ?? 0),
            "nom" => self::safeJsonDecode($row['nom_lieu'] ?? ''),
            "horaires" => self::safeJsonDecode($row['horaires'] ?? ''),
            "description" => self::safeJsonDecode($row['description'] ?? ''),
            "adresse" => [
                "adresse" => self::safeJsonDecode($row['adresse'] ?? ''),
                "code_postal" => self::safeJsonDecode($row['code_postal'] ?? ''),
                "ville" => self::safeJsonDecode($row['ville'] ?? ''),
            ],
            "type" => self::decodeJsonArray($row['type_lieu'] ?? ''),
            "est_evenement" => (bool)($row['est_evenement'] ?? false),
            "date_evenement" => [
                "debut" => $row['date_debut'] ?? null,
                "fin" => $row['date_fin'] ?? null
            ],
            "position" => [
                "latitude" => round((float)($row['latitude'] ?? 0), 5),
                "longitude" => round((float)($row['longitude'] ?? 0), 5),
                "distance_km" => round((float)($row['distance'] ?? 0), 5)
            ],
            "equipements" => self::parseCommaSeparated($row['equipements'] ?? ''),
            "ages" => self::parseCommaSeparated($row['tranches_age'] ?? '')
        ];
    }

    /**
     * Formate les données d'un lieu pour une réponse d'API détaillée.
     *
     * Prend un tableau associatif représentant une ligne de données de lieu et
     * retourne un tableau formaté contenant toutes les informations disponibles du lieu,
     * y compris les détails de contact, les types, les équipements, les commentaires,
     * la note moyenne et le nombre de commentaires. Les identifiants sont castés en
     * entiers, les chaînes potentiellement encodées en JSON sont décodées de manière
     * sécurisée, et les tableaux JSON sont décodés.
     *
     * @param array $row Un tableau associatif contenant les données détaillées du lieu,
     * avec les clés possibles : 'id_lieu', 'nom_lieu', 'description', 'horaires',
     * 'adresse', 'ville', 'code_postal', 'telephone', 'site_web', 'type_lieu',
     * 'est_evenement', 'date_debut', 'date_fin', 'latitude', 'longitude',
     * 'equipements', 'tranches_age', 'commentaires', 'note_moyenne',
     * 'nombre_commentaires'. Les clés manquantes sont traitées comme null.
     * @return array Un tableau formaté contenant les informations détaillées du lieu.
     */
    public static function lieuDetail(array $row): array
    {
        return [
            "id" => (int)($row['id_lieu'] ?? 0),
            "nom" => self::safeJsonDecode($row['nom_lieu'] ?? ''),
            "description" => self::safeJsonDecode($row['description'] ?? ''),
            "horaires" => self::safeJsonDecode($row['horaires'] ?? ''),
            "adresse" => [
                "adresse" => self::safeJsonDecode($row['adresse'] ?? ''),
                "ville" => self::safeJsonDecode($row['ville'] ?? ''),
                "code_postal" => self::safeJsonDecode($row['code_postal'] ?? ''),
                "telephone" => self::safeJsonDecode($row['telephone'] ?? ''),
                "site_web" => self::safeJsonDecode($row['site_web'] ?? ''),
            ],
            "type" => self::decodeJsonArray($row['type_lieu'] ?? ''),
            "est_evenement" => (bool)($row['est_evenement'] ?? false),
            "date_evenement" => [
                "debut" => $row['date_debut'] ?? null,
                "fin" => $row['date_fin'] ?? null
            ],
            "position" => [
                "latitude" => round((float)($row['latitude'] ?? 0), 5),
                "longitude" => round((float)($row['longitude'] ?? 0), 5)
            ],
            "equipements" => self::decodeJsonArray($row['equipements'] ?? ''),
            "ages" => self::decodeJsonArray($row['tranches_age'] ?? ''),
            "commentaires" => self::decodeJsonArray($row['commentaires'] ?? ''),
            "note_moyenne" => round((float)($row['note_moyenne'] ?? 0), 2),
            "nombre_commentaires" => (int)($row['nombre_commentaires'] ?? 0)
        ];
    }

    /**
     * Parse une chaîne de valeurs séparées par des virgules, gérant les cas où les éléments sont eux-mêmes des objets JSON.
     *
     * Si la chaîne commence par '{', elle est traitée comme une série d'objets JSON séparés par des virgules.
     * Chaque objet est décodé et un tableau associatif contenant 'id' (entier) et 'nom' (décodé en entités HTML)
     * est ajouté au résultat.
     * Si la chaîne ne commence pas par '{', elle est traitée comme une simple liste de noms séparés par des virgules.
     * Chaque nom est nettoyé des espaces et un tableau associatif avec 'id' à null et 'nom' (décodé en entités HTML)
     * est ajouté au résultat. Les éléments vides sont ignorés.
     *
     * @param string $data La chaîne de caractères à parser.
     * @return array Un tableau d'objets ou de tableaux associatifs représentant les éléments parsés.
     */
    private static function parseCommaSeparated($data): array
    {
        if (empty($data)) {
            return [];
        }

        if (str_starts_with($data, '{')) {
            $items = explode(',', $data);
            $result = [];

            foreach ($items as $item) {
                $jsonObj = json_decode(trim($item), true);
                if ($jsonObj) {
                    $result[] = [
                        'id' => (int)($jsonObj['id'] ?? 0),
                        'nom' => html_entity_decode($jsonObj['nom'] ?? '')
                    ];
                }
            }

            return array_filter($result); // Filtrer les potentiels éléments vides après le décodage JSON.
        } else {
            return array_map(function ($item) {
                return [
                    'id' => null,
                    'nom' => html_entity_decode(trim($item))
                ];
            }, explode(',', $data));
        }
    }

    /**
     * Décode une chaîne JSON représentant un tableau.
     *
     * Prend une chaîne JSON qui représente un tableau (potentiellement malformé s'il
     * manque les crochets englobants) et tente de la décoder en un tableau PHP.
     * Si la chaîne n'est pas vide, elle est entourée de crochets pour la rendre un
     * tableau JSON valide avant le décodage. En cas d'échec du décodage ou si la
     * chaîne est vide, un tableau vide est retourné.
     *
     * @param string $json La chaîne JSON à décoder.
     * @return array Le tableau PHP résultant du décodage JSON, ou un tableau vide en cas d'échec ou si la chaîne est vide.
     */
    private static function decodeJsonArray(string $json): array
    {
        if (empty($json)) {
            return [];
        }

        $decoded = json_decode('[' . $json . ']', true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Décode de manière sécurisée une chaîne potentiellement encodée en JSON.
     *
     * Prend une chaîne de caractères et tente de la décoder comme une chaîne JSON
     * entre guillemets. Si le décodage réussit (et n'est pas null), la valeur décodée
     * est retournée. Sinon, la chaîne d'origine est décodée des entités HTML pour
     * gérer le cas où la valeur n'était pas encodée en JSON mais contenait des
     * entités HTML.
     *
     * @param string $value La chaîne de caractères à décoder.
     * @return string La valeur décodée (soit depuis JSON, soit depuis les entités HTML),
     * ou la chaîne d'origine si aucune des tentatives de décodage ne réussit.
     */
    private static function safeJsonDecode(string $value)
    {
        $decoded = json_decode('"' . $value . '"', true);
        return $decoded === null ? html_entity_decode($value) : $decoded;
    }


    /**
     * Méthode pour formater les données d'un utilisateur et ses préférences.
     *
     * Prend un tableau associatif représentant une ligne de la vue user_preference
     * et retourne un tableau formaté contenant les informations de l'utilisateur
     * ainsi que ses préférences (tranches d'âge et équipements).
     *
     * @param array $row Un tableau associatif contenant les données de l'utilisateur,
     * avec les clés possibles : 'id_user', 'pseudo', 'mail', 'telephone', 'grade',
     * 'date_creation', 'derniere_connexion', 'tranches_age', 'equipements'.
     * @return array Un tableau formaté contenant les informations de l'utilisateur et ses préférences.
     */
    public static function userPreference(array $row): array
    {
        return [
            "id" => (int)($row['id_user'] ?? 0),
            "pseudo" => self::safeJsonDecode($row['pseudo'] ?? ''),
            "mail" => self::safeJsonDecode($row['mail'] ?? ''),
            "telephone" => self::safeJsonDecode($row['telephone'] ?? ''),
            "grade" => (int)($row['grade'] ?? 0),
            "dates" => [
                "creation" => $row['date_creation'] ?? null,
                "derniere_connexion" => $row['derniere_connexion'] ?? null,
            ],
            "recevoirMail" => (bool)($row['opt_in_email'] ?? false),
            "preferences" => [
                "tranches_age" => self::decodeJsonArray($row['tranches_age'] ?? ''),
                "equipements" => self::decodeJsonArray($row['equipements'] ?? '')
            ]
        ];
    }
}
