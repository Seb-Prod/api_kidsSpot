# Documentation de la fonction d'envoi d'emails

## Aperçu

La fonction `envoyerEmail` est un utilitaire permettant d'envoyer des emails via SMTP en utilisant la bibliothèque PHPMailer. Elle simplifie l'utilisation de PHPMailer en encapsulant la configuration et la gestion des erreurs.

## Dépendances

Cette fonction nécessite:
- La bibliothèque PHPMailer
- Un fichier de configuration contenant les paramètres SMTP

## Signature

```php
function envoyerEmail($destinataire, $sujet, $contenuHTML, $contenuTexte = ''): bool
```

## Paramètres

| Paramètre | Type | Description | Obligatoire |
|-----------|------|-------------|------------|
| `$destinataire` | string | Adresse email du destinataire | Oui |
| `$sujet` | string | Sujet de l'email | Oui |
| `$contenuHTML` | string | Contenu HTML de l'email | Oui |
| `$contenuTexte` | string | Version texte du contenu (facultatif) | Non |

## Valeur de retour

- `true` si l'email a été envoyé avec succès
- `false` si une erreur s'est produite lors de l'envoi

## Configuration requise

La fonction s'attend à trouver un fichier de configuration à l'emplacement `__DIR__ . '/../config/config.php'` avec une structure similaire à:

```php
return [
    'mail' => [
        'smtp_host' => 'smtp.example.com',
        'smtp_auth' => true,
        'smtp_username' => 'user@example.com',
        'smtp_password' => 'password',
        'smtp_port' => 587,
        'smtp_secure' => 'tls', // Peut être 'tls', 'ssl' ou vide
        'from_email' => 'noreply@example.com',
        'from_name' => 'Mon Application'
    ]
]
```

## Gestion des erreurs

En cas d'erreur lors de l'envoi, la fonction:
1. Enregistre les détails de l'erreur dans le journal d'erreurs du serveur via `error_log()`
2. Retourne `false` pour indiquer l'échec

## Modes de sécurisation SMTP

La fonction gère trois modes de sécurisation SMTP:
- `'tls'`: Utilise STARTTLS (démarrage sécurisé de la connexion)
- `'ssl'`: Utilise SMTPS (connexion SSL/TLS complète)
- Par défaut: Utilise STARTTLS si aucune configuration n'est spécifiée

## Exemple d'utilisation

```php
// Envoi d'un email de confirmation d'inscription
$destinataire = "utilisateur@example.com";
$sujet = "Confirmation d'inscription";
$contenuHTML = "<h1>Bienvenue !</h1><p>Votre inscription a été confirmée avec succès.</p>";

if (envoyerEmail($destinataire, $sujet, $contenuHTML)) {
    echo "Email de confirmation envoyé avec succès";
} else {
    echo "Erreur lors de l'envoi de l'email de confirmation";
}
```

## Version texte alternatif

Si aucun contenu texte alternatif n'est fourni, la fonction génère automatiquement une version texte à partir du contenu HTML en utilisant `strip_tags()`.

## Remarques importantes

- Les identifiants SMTP sont stockés dans le fichier de configuration et ne sont pas inclus dans le code
- Les erreurs d'envoi sont enregistrées silencieusement (l'utilisateur ne voit pas les détails techniques)
- La fonction utilise PHPMailer en mode exception (`new PHPMailer(true)`)
- Les bibliothèques PHPMailer sont incluses manuellement avec des chemins relatifs
