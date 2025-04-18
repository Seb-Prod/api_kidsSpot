# Documentation de la Classe Commentaires

## Description
La classe `Commentaires` gère les commentaires des utilisateurs sur des lieux dans une application PHP. Elle permet la création, la lecture, la mise à jour et la suppression de commentaires, ainsi que diverses fonctionnalités de vérification et de gestion des notes.

## Structure

### Propriétés
| Propriété | Type | Description |
|-----------|------|-------------|
| `$connexion` | PDO | Connexion à la base de données (privée) |
| `$id` | int | Identifiant unique du commentaire |
| `$id_lieu` | int | Identifiant du lieu concerné par le commentaire |
| `$commentaire` | string | Contenu textuel du commentaire |
| `$note` | numeric | Note attribuée au lieu |
| `$id_user` | int | Identifiant de l'utilisateur auteur du commentaire |
| `$date_ajout` | datetime | Date de création du commentaire |

## Méthodes

### Constructeur
```php
__construct($db)
```
**Description**: Initialise une nouvelle instance de la classe en établissant la connexion à la base de données.  
**Paramètres**:
- `$db` - Instance PDO de connexion à la base de données

### Créer un commentaire
```php
create()
```
**Description**: Ajoute un nouveau commentaire dans la base de données.  
**Retourne**: `boolean` - `true` si l'insertion a réussi, `false` en cas d'erreur.  
**Note**: La méthode utilise `htmlspecialchars` et `strip_tags` pour sécuriser les données.

### Lire un commentaire
```php
read($id)
```
**Description**: Récupère un commentaire spécifique avec des informations détaillées.  
**Paramètres**:
- `$id` - Identifiant du commentaire à récupérer
  
**Retourne**: Objet PDOStatement contenant les données du commentaire, ou `false` en cas d'échec.

### Lire tous les commentaires d'un lieu
```php
readAll($id)
```
**Description**: Récupère tous les commentaires associés à un lieu spécifique.  
**Paramètres**:
- `$id` - Identifiant du lieu  
  
**Retourne**: Objet PDOStatement contenant les commentaires, ou `false` en cas d'échec.

### Mettre à jour un commentaire
```php
update()
```
**Description**: Met à jour le contenu et la note d'un commentaire existant.  
**Retourne**: `boolean` - `true` si la mise à jour a réussi, `false` en cas d'erreur.  
**Prérequis**: Les propriétés `id`, `commentaire` et `note` doivent être définies.

### Supprimer un commentaire
```php
delete()
```
**Description**: Supprime un commentaire de la base de données.  
**Retourne**: `boolean` - `true` si la suppression a réussi, `false` en cas d'erreur.  
**Prérequis**: La propriété `id` doit être définie.

### Vérifier si un utilisateur a déjà commenté un lieu
```php
alreadyExists()
```
**Description**: Vérifie si un utilisateur a déjà publié un commentaire pour un lieu spécifique.  
**Retourne**: `boolean` - `true` si un commentaire existe déjà, `false` sinon.  
**Prérequis**: Les propriétés `id_lieu` et `id_user` doivent être définies.

### Vérifier si un commentaire existe
```php
exists()
```
**Description**: Vérifie l'existence d'un commentaire par son identifiant.  
**Retourne**: `boolean` - `true` si le commentaire existe, `false` sinon.  
**Prérequis**: La propriété `id` doit être définie.

### Récupérer l'identifiant de l'utilisateur d'un commentaire
```php
getUserIdByCommentId($id_commentaire)
```
**Description**: Récupère l'identifiant de l'utilisateur auteur d'un commentaire.  
**Paramètres**:
- `$id_commentaire` - Identifiant du commentaire

**Retourne**: Identifiant de l'utilisateur ou `null` si le commentaire n'existe pas.

### Calculer la moyenne des notes
```php
getMoyenneNotes($id_lieu)
```
**Description**: Calcule la note moyenne pour un lieu spécifique.  
**Paramètres**:
- `$id_lieu` - Identifiant du lieu

**Retourne**: Objet PDOStatement contenant la moyenne des notes, ou `false` en cas d'échec.

### Vérifier si un utilisateur est propriétaire d'un commentaire
```php
isOwnedBy($userId)
```
**Description**: Vérifie si un utilisateur est l'auteur d'un commentaire.  
**Paramètres**:
- `$userId` - Identifiant de l'utilisateur

**Retourne**: `boolean` - `true` si l'utilisateur est l'auteur, `false` sinon.

### Vérifier les permissions de modification ou suppression
```php
peutModifierOuSupprimer($user_id, $user_grade, $action = 'both')
```
**Description**: Vérifie si un utilisateur a le droit de modifier ou supprimer un commentaire.  
**Paramètres**:
- `$user_id` - Identifiant de l'utilisateur
- `$user_grade` - Niveau d'autorisation de l'utilisateur
- `$action` - Type d'action ('both' par défaut, ou 'delete')

**Retourne**: `boolean` - `true` si l'utilisateur a les droits nécessaires, `false` sinon.

## Exemple d'utilisation

```php
// Initialisation
$db = new PDO('mysql:host=localhost;dbname=ma_base', 'utilisateur', 'mot_de_passe');
$commentaires = new Commentaires($db);

// Création d'un commentaire
$commentaires->id_lieu = 5;
$commentaires->commentaire = "Très bel endroit, je recommande !";
$commentaires->note = 4.5;
$commentaires->id_user = 12;
if($commentaires->create()) {
    echo "Commentaire ajouté avec succès";
}

// Lecture des commentaires d'un lieu
$result = $commentaires->readAll(5);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo $row['pseudo_user'] . " : " . $row['commentaire'] . " (" . $row['note'] . "/5)";
}

// Suppression d'un commentaire
$commentaires->id = 42;
if($commentaires->delete()) {
    echo "Commentaire supprimé avec succès";
}
```

## Sécurité
La classe implémente plusieurs mesures de sécurité:
- Protection contre les injections SQL via PDO et les requêtes préparées
- Nettoyage des données avec `htmlspecialchars` et `strip_tags`
- Gestion des autorisations pour la modification et suppression des commentaires
- Journalisation des erreurs PDO

## Notes importantes
- La classe nécessite une connexion PDO valide pour fonctionner
- Les méthodes de vérification des droits supposent l'existence d'un système de grades utilisateurs
- Les dates sont automatiquement générées par le système
