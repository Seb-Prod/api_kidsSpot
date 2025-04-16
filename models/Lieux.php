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
    private $connexion;
    public $id;
    public $nom;
    public $description;
    public $horaires;
    public $adresse;
    public $ville;
    public $code_postal;
    public $latitude;
    public $longitude;
    public $telephone;
    public $site_web;
    public $date_creation;
    public $date_modification;
    public $id_type;
    public $id_user;

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
     * Créer un nouveau lieu dans la base de données avec ses équipements et tranches d'âge associés.
     */
    public function create($equipements = [], $tranches_age = [])
    {
        try {
            // Démarrer une transaction pour garantir l'intégrité des données
            $this->connexion->beginTransaction();

            $sql = "INSERT INTO lieux SET nom=:nom, description=:description, 
            horaires=:horaires, adresse=:adresse, ville=:ville, code_postal=:code_postal, 
            latitude=:latitude, longitude=:longitude, telephone=:telephone, 
            site_web=:site_web, date_creation=:date_creation, 
            date_modification=:date_modification, id_type=:id_type, id_user=:id_user";

            $query = $this->connexion->prepare($sql);

            // Nettoyage et sécurisation des données
            $this->nom = htmlspecialchars(strip_tags($this->nom));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->horaires = htmlspecialchars(strip_tags($this->horaires));
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
            $this->id_user = htmlspecialchars(strip_tags($this->id_user));

            // Liaison des valeurs
            $query->bindParam(":nom", $this->nom);
            $query->bindParam(":description", $this->description);
            $query->bindParam(":horaires", $this->horaires);
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
            $query->bindParam(":id_user", $this->id_user);

            // Exécution de la requête d'insertion du lieu
            if ($query->execute()) {
                // Récupérer l'ID du lieu nouvellement créé
                $lieu_id = $this->connexion->lastInsertId();

                // Ajouter les équipements
                if (!empty($equipements)) {
                    foreach ($equipements as $equipement_id) {
                        $equipement_id = htmlspecialchars(strip_tags($equipement_id));

                        $sql_equipement = "INSERT INTO lieux_equipement (id_lieux, id_equipement) VALUES (:id_lieux, :id_equipement)";
                        $query_equipement = $this->connexion->prepare($sql_equipement);
                        $query_equipement->bindParam(":id_lieux", $lieu_id);
                        $query_equipement->bindParam(":id_equipement", $equipement_id);
                        $query_equipement->execute();
                    }
                }

                // Ajouter les tranches d'âge
                if (!empty($tranches_age)) {
                    foreach ($tranches_age as $age_id) {
                        $age_id = htmlspecialchars(strip_tags($age_id));

                        $sql_age = "INSERT INTO lieux_age (id_lieu, id_age) VALUES (:id_lieu, :id_age)";
                        $query_age = $this->connexion->prepare($sql_age);
                        $query_age->bindParam(":id_lieu", $lieu_id);
                        $query_age->bindParam(":id_age", $age_id);
                        $query_age->execute();
                    }
                }

                // Valider la transaction
                $this->connexion->commit();

                // Retourner l'ID du lieu créé
                return $lieu_id;
            }

            // En cas d'échec de l'insertion du lieu
            $this->connexion->rollBack();
            return false;
        } catch (PDOException $e) {
            // En cas d'erreur, annuler toutes les opérations
            $this->connexion->rollBack();
            return false;
        }
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

    public function exist()
    {
        $sql = "SELECT COUNT(*) FROM lieux WHERE id = :id";

        $query = $this->connexion->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $query->bindParam(":id", $this->id);

        try {
            $query->execute();
            $count = $query->fetchColumn();
            return $count > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
