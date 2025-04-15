<?php

/**
 * @file
 * Modèle de la classe Lieux.
 *
 * Cette classe fournit des méthodes pour interagir avec la table 'lieux' de la base de données,
 * incluant la récupération d'un lieu par ID, la récupération des lieux autour de coordonnées
 * géographiques, la création d'un nouveau lieu et la suppression d'un lieu.
 */
class Lieux
{
    /**
     * @var PDO $connexion Instance de connexion à la base de données.
     */
    private $connexion;

    /**
     * @var int $id Identifiant unique du lieu.
     */
    public $id;

    /**
     * @var string $nom Nom du lieu.
     */
    public $nom;

    /**
     * @var string $description Description du lieu (peut être null).
     */
    public $description;

    /**
     * @var string $adresse Adresse du lieu.
     */
    public $adresse;

    /**
     * @var string $ville Ville du lieu.
     */
    public $ville;

    /**
     * @var string $code_postal Code postal du lieu.
     */
    public $code_postal;

    /**
     * @var float $latitude Latitude du lieu.
     */
    public $latitude;

    /**
     * @var float $longitude Longitude du lieu.
     */
    public $longitude;

    /**
     * @var string $telephone Numéro de téléphone du lieu (peut être null).
     */
    public $telephone;

    /**
     * @var string $site_web Site web du lieu (peut être null).
     */
    public $site_web;

    /**
     * @var string $date_creation Date de création du lieu (format Y-m-d).
     */
    public $date_creation;

    /**
     * @var string $date_modification Date de dernière modification du lieu (format Y-m-d).
     */
    public $date_modification;

    /**
     * @var int $id_type Identifiant du type de lieu, clé étrangère vers la table 'types_lieux'.
     */
    public $id_type;

    /**
     * Constructeur de la classe Lieux.
     *
     * Initialise l'instance de connexion à la base de données.
     *
     * @param PDO $db Instance de connexion PDO.
     */
    public function __construct($db)
    {
        $this->connexion = $db;
    }

    /**
     * Obtenir un lieu par son identifiant unique.
     *
     * Effectue une requête SQL pour récupérer les informations d'un lieu spécifique en utilisant son ID.
     * Inclut également le nom du type de lieu, un indicateur si le lieu est un événement,
     * la liste des équipements associés et les dates de début et de fin pour les événements.
     *
     * @param int $id L'identifiant unique du lieu à récupérer.
     * @return PDOStatement|false Un objet PDOStatement contenant le résultat de la requête si elle réussit,
     * ou `false` en cas d'erreur d'exécution.
     */
    public function obtenirLieu($id)
    {
        $sql = "SELECT * FROM vue_detail_lieu WHERE id_lieu = :id";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':id', $id);

        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtenir une liste des lieux situés autour d'une latitude et d'une longitude données, triés par distance croissante.
     *
     * Effectue une requête SQL pour sélectionner les lieux dans un rayon donné autour des coordonnées fournies.
     * La distance est calculée en utilisant la formule haversine. Les résultats sont triés par ordre de distance croissante.
     * Inclut également le nom du type de lieu, un indicateur si le lieu est un événement,
     * la liste des équipements associés et les dates de début et de fin pour les événements.
     *
     * @param float $latitude La latitude du point central autour duquel rechercher les lieux.
     * @param float $longitude La longitude du point central autour duquel rechercher les lieux.
     * @return PDOStatement|false Un objet PDOStatement contenant le résultat de la requête si elle réussit,
     * ou `false` en cas d'erreur d'exécution.
     */
    public function obtenirLieuxAutour($latitude, $longitude)
    {
        $sql = "SELECT 
            v.*,
            CASE WHEN e.id IS NOT NULL THEN 1 ELSE 0 END AS est_evenement,
            e.date_debut,
            e.date_fin,
            (
                6371 * acos(
                    cos(radians(:latitude)) * 
                    cos(radians(v.latitude)) * 
                    cos(radians(v.longitude) - radians(:longitude)) + 
                    sin(radians(:latitude)) * 
                    sin(radians(v.latitude))
                )
            ) AS distance
        FROM 
            vue_lieux_complete v
        LEFT JOIN
            evenements e ON v.id_lieu = e.id_lieux
        GROUP BY
            v.id_lieu, e.id
        ORDER BY
            distance ASC";

        $query = $this->connexion->prepare($sql);
        $query->bindParam(':latitude', $latitude);
        $query->bindParam(':longitude', $longitude);

        try {
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Créer un nouveau lieu dans la base de données.
     *
     * Prépare et exécute une requête SQL d'insertion pour ajouter un nouveau lieu
     * avec les informations fournies dans les propriétés de l'objet.
     * Les données sont nettoyées et sécurisées avant l'insertion.
     *
     * @return bool Retourne `true` si l'insertion a réussi, `false` en cas d'échec.
     */
    public function creer()
    {
        $sql = "INSERT INTO lieux SET nom=:nom, description=:description, 
            adresse=:adresse, ville=:ville, code_postal=:code_postal, 
            latitude=:latitude, longitude=:longitude, telephone=:telephone, 
            site_web=:site_web, date_creation=:date_creation, 
            date_modification=:date_modification, id_type=:id_type";

        $query = $this->connexion->prepare($sql);

        // Nettoyage et sécurisation des données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->adresse = htmlspecialchars(strip_tags($this->adresse));
        $this->ville = htmlspecialchars(strip_tags($this->ville));
        $this->code_postal = htmlspecialchars(strip_tags($this->code_postal));
        $this->latitude = htmlspecialchars(strip_tags($this->latitude));
        $this->longitude = htmlspecialchars(strip_tags($this->longitude));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->site_web = htmlspecialchars(strip_tags($this->site_web));
        $this->date_creation = date('Y-m-d');
        $this->date_modification = date('Y-m-d');
        $this->id_type = htmlspecialchars(strip_tags($this->id_type));

        // Liaison des valeurs
        $query->bindParam(":nom", $this->nom);
        $query->bindParam(":description", $this->description);
        $query->bindParam(":adresse", $this->adresse);
        $query->bindParam(":ville", $this->ville);
        $query->bindParam(":code_postal", $this->code_postal);
        $query->bindParam(":latitude", $this->latitude);
        $query->bindParam(":longitude", $this->longitude);
        $query->bindParam(":telephone", $this->telephone);
        $query->bindParam(":site_web", $this->site_web);
        $query->bindParam(":date_creation", $this->date_creation);
        $query->bindParam(":date_modification", $this->date_modification);
        $query->bindParam(":id_type", $this->id_type);

        // Exécution de la requête
        if ($query->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Supprimer un lieu de la base de données en fonction de son ID.
     *
     * Prépare et exécute une requête SQL de suppression pour retirer un lieu spécifique
     * en utilisant l'ID stocké dans la propriété `$this->id`.
     * L'ID est nettoyé et sécurisé avant l'exécution de la requête.
     *
     * @return bool Retourne `true` si la suppression a réussi (au moins une ligne a été affectée),
     * `false` en cas d'échec ou si aucun lieu avec cet ID n'a été trouvé.
     */
    public function supprimer()
    {
        $sql = "DELETE FROM lieux WHERE id=:id";

        $query = $this->connexion->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $query->bindParam(":id", $this->id);

        if ($query->execute()) {
            // Vérifie si une ligne a été affectée
            if ($query->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }
}
