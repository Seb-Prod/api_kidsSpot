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

    public $equipements = [];
    public $tranches_age = [];

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
    public function getPlaceById($id)
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
    public function getPlacesAround($latitude, $longitude)
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
     * Créer un nouveau lieu dans la base de données avec ses équipements et tranches d'âge associés.
     */
    public function create($equipements = [], $tranches_age = [], $date_debut = null, $date_fin = null)
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
            $this->nom = strip_tags($this->nom);
            $this->description = strip_tags($this->description);
            $this->horaires = strip_tags($this->horaires);
            $this->adresse = strip_tags($this->adresse);
            $this->ville = strip_tags($this->ville);
            $this->code_postal = htmlspecialchars(strip_tags($this->code_postal));
            $this->latitude = htmlspecialchars(strip_tags($this->latitude));
            $this->longitude = htmlspecialchars(strip_tags($this->longitude));
            $this->telephone = !empty($this->telephone) ? htmlspecialchars(strip_tags($this->telephone)) : null;
            $this->site_web = !empty($this->site_web) ? htmlspecialchars(strip_tags($this->site_web)) : null;
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

                // Ajout date début et fin d'événement
                if ($date_debut && $date_fin) {
                    $date_debut = htmlspecialchars(strip_tags($date_debut));
                    $date_fin = htmlspecialchars(strip_tags($date_fin));

                    $sql_dates = "INSERT INTO evenements (id_lieux, date_debut, date_fin) VALUES (:id_lieu, :date_debut, :date_fin)";
                    $query_dates = $this->connexion->prepare($sql_dates);
                    $query_dates->bindParam(":id_lieu", $lieu_id);
                    $query_dates->bindParam(":date_debut", $date_debut);
                    $query_dates->bindParam(":date_fin", $date_fin);
                    $query_dates->execute();
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
    public function delete()
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

    /**
     * Mettre à jour un lieu dans la base de données avec ses équipements et tranches d'âge associés.
     *
     * @param array $equipements Un tableau d'IDs d'équipements à associer au lieu.
     * @param array $tranches_age Un tableau d'IDs de tranches d'âge à associer au lieu.
     * @param string|null $date_debut La date de début de l'événement (si applicable).
     * @param string|null $date_fin La date de fin de l'événement (si applicable).
     * @return bool Retourne `true` en cas de succès de la mise à jour, `false` en cas d'échec.
     */
    public function update($equipements = [], $tranches_age = [], $date_debut = null, $date_fin = null)
    {
        try {
            // Démarrer une transaction pour garantir l'intégrité des données
            $this->connexion->beginTransaction();

            // Mise à jour des informations du lieu
            $sql_lieu = "UPDATE lieux SET nom=:nom, description=:description,
                            horaires=:horaires, adresse=:adresse, ville=:ville, code_postal=:code_postal,
                            latitude=:latitude, longitude=:longitude, telephone=:telephone,
                            site_web=:site_web, date_modification=:date_modification, id_type=:id_type
                            WHERE id=:id";

            $query_lieu = $this->connexion->prepare($sql_lieu);

            // Nettoyage et sécurisation des données
            $this->nom = strip_tags($this->nom);
            $this->description = strip_tags($this->description);
            $this->horaires = strip_tags($this->horaires);
            $this->adresse = strip_tags($this->adresse);
            $this->ville = strip_tags($this->ville);
            $this->code_postal = strip_tags($this->code_postal);
            $this->latitude = strip_tags($this->latitude);
            $this->longitude = strip_tags($this->longitude);
            $this->telephone = !empty($this->telephone) ? strip_tags($this->telephone) : null;
            $this->site_web = !empty($this->site_web) ? strip_tags($this->site_web) : null;
            $this->date_modification = date('Y-m-d H:i:s');
            $this->id_type = strip_tags($this->id_type);
            $id = strip_tags($this->id);

            // Liaison des valeurs pour la table lieux
            $query_lieu->bindParam(":nom", $this->nom);
            $query_lieu->bindParam(":description", $this->description);
            $query_lieu->bindParam(":horaires", $this->horaires);
            $query_lieu->bindParam(":adresse", $this->adresse);
            $query_lieu->bindParam(":ville", $this->ville);
            $query_lieu->bindParam(":code_postal", $this->code_postal);
            $query_lieu->bindParam(":latitude", $this->latitude);
            $query_lieu->bindParam(":longitude", $this->longitude);
            $query_lieu->bindParam(":telephone", $this->telephone);
            $query_lieu->bindParam(":site_web", $this->site_web);
            $query_lieu->bindParam(":date_modification", $this->date_modification);
            $query_lieu->bindParam(":id_type", $this->id_type);
            $query_lieu->bindParam(":id", $id);

            // Exécution de la requête de mise à jour du lieu
            if ($query_lieu->execute()) {
                // --- Gestion des équipements ---
                // Dissocier tous les équipements existants pour ce lieu
                $sql_delete_equipements = "DELETE FROM lieux_equipement WHERE id_lieux = :id_lieux";
                $query_delete_equipements = $this->connexion->prepare($sql_delete_equipements);
                $query_delete_equipements->bindParam(":id_lieux", $id);
                $query_delete_equipements->execute();

                // Lier les nouveaux équipements
                foreach ($equipements as $id_equipement) {
                    $id_equipement_SQL = htmlspecialchars(strip_tags($id_equipement));
                    $sql_insert_equipement = "INSERT INTO lieux_equipement (id_lieux, id_equipement) VALUES (:id_lieu, :id_equipement)";
                    $query_insert_equipement = $this->connexion->prepare($sql_insert_equipement);
                    $query_insert_equipement->bindParam(":id_lieu", $id);
                    $query_insert_equipement->bindParam(":id_equipement", $id_equipement_SQL);
                    $query_insert_equipement->execute();
                }

                // --- Gestion des tranches d'âge ---
                // Dissocier toutes les tranches d'âge existantes pour ce lieu
                $sql_delete_ages = "DELETE FROM lieux_age WHERE id_lieu = :id_lieu";
                $query_delete_ages = $this->connexion->prepare($sql_delete_ages);
                $query_delete_ages->bindParam(":id_lieu", $id);
                $query_delete_ages->execute();

                // Lier les nouvelles tranches d'âge
                foreach ($tranches_age as $id_age) {
                    $id_age_SQL = htmlspecialchars(strip_tags($id_age));
                    $sql_insert_age = "INSERT INTO lieux_age (id_lieu, id_age) VALUES (:id_lieu, :id_age)";
                    $query_insert_age = $this->connexion->prepare($sql_insert_age);
                    $query_insert_age->bindParam(":id_lieu", $id);
                    $query_insert_age->bindParam(":id_age", $id_age_SQL);
                    $query_insert_age->execute();
                }


                // Ajouter la date de l'événement si fournie
                if ($date_debut && $date_fin) {
                    $sql_delete_evenement = "DELETE FROM evenements WHERE id_lieux = :id_lieu";
                    $query_delete_evenement = $this->connexion->prepare($sql_delete_evenement);
                    $query_delete_evenement->bindParam(":id_lieu", $id);
                    $query_delete_evenement->execute();

                    $date_debut_cleaned = htmlspecialchars(strip_tags($date_debut));
                    $date_fin_cleaned = htmlspecialchars(strip_tags($date_fin));

                    $sql_dates = "INSERT INTO evenements (id_lieux, date_debut, date_fin) VALUES (:id_lieu, :date_debut, :date_fin)";
                    $query_dates = $this->connexion->prepare($sql_dates);
                    $query_dates->bindParam(":id_lieu", $id);
                    $query_dates->bindParam(":date_debut", $date_debut_cleaned);
                    $query_dates->bindParam(":date_fin", $date_fin_cleaned);
                    $query_dates->execute();
                }


                $this->connexion->commit();
                return true;
            } else {
                // En cas d'échec de la mise à jour du lieu principal
                $this->connexion->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            // En cas d'erreur, annuler toutes les opérations
            $this->connexion->rollBack();
            return false;
        }
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

    private function lierElements($lieu_id, $table_liaison, $colonne_element, $elements = [])
    {
        if (!empty($elements)) {
            foreach ($elements as $element_id) {
                $element_id = htmlspecialchars(strip_tags($element_id));
                $sql_liaison = "INSERT INTO " . $table_liaison . " (id_lieux, " . $colonne_element . ") VALUES (:id_lieux, :" . $colonne_element . ")";
                $query_liaison = $this->connexion->prepare($sql_liaison);
                $query_liaison->bindParam(":id_lieux", $lieu_id);
                $query_liaison->bindParam(":" . $colonne_element, $element_id);
                $query_liaison->execute();
            }
        }
    }
}
