# Documentation du modèle Commentaires.php

## Vue d'ensemble

La classe `Commentaires` fournit une interface pour gérer les commentaires et les notes associés à des lieux dans la base de données. Elle permet de créer, lire, mettre à jour et supprimer des commentaires (opérations CRUD), ainsi que de récupérer des statistiques comme la moyenne des notes pour un lieu spécifique.

## Structure de la classe

### Propriétés

| Propriété    | Type    | Description                                         |
|--------------|---------|-----------------------------------------------------|
| `connexion`  | Privé   | Instance de connexion PDO à la base de données      |
| `id`         | Public  | Identifiant unique du commentaire                   |
| `id_lieu`    | Public  | Identifiant du lieu concerné par le commentaire     |
| `commentaire`| Public  | Contenu textuel du commentaire                      |
| `note`       | Public  | Note attribuée au lieu (valeur numérique)           |
| `id_user`    | Public  | Identifiant de l'utilisateur auteur du commentaire  |
| `date_ajout` | Public  | Date de création du commentaire                     |

## Méthodes

### Constructeur

```php
public function __construct($db)
```

**Description :** Initialise l'instance de la classe avec une connexion à la base de données.

**Paramètres :**
- `$db` : Instance de connexion PDO à la base de données.

### Créer un commentaire

```php
public function create()
```

**Description :** Ajoute un nouveau commentaire et une note pour un lieu dans la base de données.

**Retourne :** `true` si l'insertion a réussi, `false` en cas d'échec.

**Exemple d'utilisation :**
```php
$commentaire = new Commentaires($db);
$commentaire->id_lieu = 1;
$commentaire->commentaire = "Très bel endroit!";
$commentaire->note = 4.5;
$commentaire->id_user = 2;
if ($commentaire->create()) {
    // Commentaire ajouté avec succès
}
```

### Lire un commentaire

```php
public function read($id)
```

**Description :** Récupère les détails d'un commentaire spécifique par son ID.

**Paramètres :**
- `$id` : Identifiant du commentaire à récupérer.

**Retourne :** Un objet PDOStatement contenant les informations du commentaire ou `false` en cas d'erreur.

### Lire tous les commentaires d'un lieu

```php
public function readAll($id)
```

**Description :** Récupère tous les commentaires associés à un lieu spécifique.

**Paramètres :**
- `$id` : Identifiant du lieu dont on veut récupérer les commentaires.

**Retourne :** Un objet PDOStatement contenant tous les commentaires du lieu ou `false` en cas d'erreur.

### Mettre à jour un commentaire

```php
public function update()
```

**Description :** Modifie un commentaire existant dans la base de données.

**Retourne :** `true` si la mise à jour a réussi, `false` en cas d'échec.

**Exemple d'utilisation :**
```php
$commentaire = new Commentaires($db);
$commentaire->id = 5;
$commentaire->commentaire = "J'ai changé d'avis, c'est encore mieux que je pensais!";
$commentaire->note = 5;
$commentaire->id_user = 2;
if ($commentaire->update()) {
    // Commentaire mis à jour avec succès
}
```

### Supprimer un commentaire

```php
public function delete()
```

**Description :** Supprime un commentaire de la base de données.

**Retourne :** `true` si la suppression a réussi, `false` en cas d'échec.

**Exemple d'utilisation :**
```php
$commentaire = new Commentaires($db);
$commentaire->id = 5;
if ($commentaire->delete()) {
    // Commentaire supprimé avec succès
}
```

### Vérifier si un utilisateur a déjà commenté un lieu

```php
public function alreadyExists()
```

**Description :** Vérifie si un utilisateur a déjà laissé un commentaire pour un lieu spécifique.

**Retourne :** `true` si l'utilisateur a déjà commenté ce lieu, `false` sinon.

### Vérifier si un commentaire existe

```php
public function exists()
```

**Description :** Vérifie l'existence d'un commentaire par son ID.

**Retourne :** `true` si le commentaire existe, `false` sinon.

### Récupérer l'ID de l'auteur d'un commentaire

```php
public function getUserIdByCommentId($id_commentaire)
```

**Description :** Récupère l'identifiant de l'utilisateur qui a créé un commentaire spécifique.

**Paramètres :**
- `$id_commentaire` : Identifiant du commentaire.

**Retourne :** L'ID de l'utilisateur ou `null` si le commentaire n'existe pas.

### Vérifier les droits de suppression

```php
public function peutSupprimer($id_user_connecte, $grade_user_connecte)
```

**Description :** Vérifie si un utilisateur a le droit de supprimer un commentaire (s'il en est l'auteur ou s'il est administrateur).

**Paramètres :**
- `$id_user_connecte` : ID de l'utilisateur connecté.
- `$grade_user_connecte` : Grade/niveau d'autorisation de l'utilisateur connecté.

**Retourne :** `true` si l'utilisateur peut supprimer le commentaire, `false` sinon.

### Obtenir la moyenne des notes pour un lieu

```php
public function getMoyenneNotes($id_lieu)
```

**Description :** Calcule la note moyenne attribuée à un lieu spécifique.

**Paramètres :**
- `$id_lieu` : Identifiant du lieu.

**Retourne :** Un objet PDOStatement contenant la moyenne des notes ou `false` en cas d'erreur.

## Relations avec les autres tables

La classe `Commentaires` interagit avec les tables suivantes :
- `lieux` : Chaque commentaire est associé à un lieu spécifique via `id_lieu`.
- `users` : Chaque commentaire est associé à un utilisateur via `id_user`.

## Considérations de sécurité

La classe implémente plusieurs mesures de sécurité :
- Nettoyage des données avec `htmlspecialchars()` et `strip_tags()` avant les insertions et mises à jour.
- Utilisation de requêtes préparées avec PDO pour éviter les injections SQL.
- Vérification des droits d'accès pour la suppression des commentaires.
