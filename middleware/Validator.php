<?php

/**
 * Classe statique pour la validation de données.
 *
 * Cette classe fournit un ensemble de méthodes statiques pour valider des données
 * en fonction de règles définies. Elle permet de vérifier si des champs de données
 * respectent certains critères (obligatoire, format d'e-mail, longueur maximale, etc.)
 * et de retourner un tableau d'erreurs en cas de non-conformité.
 */
class Validator
{
    /**
     * Valide un tableau de données en fonction d'un ensemble de règles.
     *
     * Cette méthode prend un tableau de données à valider et un tableau de règles
     * de validation. Pour chaque champ défini dans les règles, elle récupère la
     * valeur correspondante dans le tableau de données et applique la fonction de
     * validation spécifiée dans la règle. Si la validation échoue, un message
     * d'erreur est ajouté au tableau des erreurs, qui est retourné à la fin.
     *
     * Les règles de validation peuvent être définies de deux manières :
     * - Une fonction de validation anonyme (callable) directement assignée à la clé du champ.
     * - Un tableau associatif contenant les clés 'validator' (la fonction de validation)
     * et 'message' (le message d'erreur personnalisé).
     *
     * @param array $data Le tableau associatif des données à valider. Les clés de ce
     * tableau correspondent aux noms des champs à valider.
     * @param array $rules Un tableau associatif définissant les règles de validation
     * pour chaque champ. Les clés de ce tableau correspondent aux noms
     * des champs à valider, et les valeurs sont soit une fonction de
     * validation (callable), soit un tableau associatif avec les clés
     * 'validator' et 'message'.
     * @return array Un tableau associatif contenant les erreurs de validation. Les clés
     * de ce tableau sont les noms des champs qui n'ont pas passé la
     * validation, et les valeurs sont les messages d'erreur correspondants.
     * Retourne un tableau vide si toutes les données sont valides.
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            // Récupérer la valeur du champ à valider depuis le tableau de données.
            // Si le champ n'existe pas, $value sera null.
            $value = $data[$field] ?? null;

            // Déstructuration de la règle pour extraire la fonction de validation et le message d'erreur.
            // Si la règle est une simple fonction, $validationFn prend cette fonction.
            // Si la règle est un tableau, on utilise les clés 'validator' et 'message'.
            $validationFn = $rule['validator'] ?? $rule;
            $errorMessage = $rule['message'] ?? "Le champ $field n'est pas valide";

            // Appliquer la fonction de validation à la valeur du champ.
            // Si la validation retourne false, une erreur est ajoutée au tableau $errors.
            if (!$validationFn($value)) {
                $errors[$field] = $errorMessage;
            }
        }
        return $errors;
    }

    /**
     * Fonction utilitaire pour créer une règle de validation avec un message d'erreur personnalisé.
     *
     * Cette méthode permet de définir une règle de validation en associant une fonction
     * de validation (callable) à un message d'erreur spécifique. Elle retourne un tableau
     * formaté de manière à être utilisé dans le tableau de règles de la méthode `validate`.
     *
     * @param callable $validator La fonction de validation à appliquer. Cette fonction doit
     * prendre la valeur du champ à valider en argument et retourner true si
     * la validation réussit, false sinon.
     * @param string $message Le message d'erreur personnalisé à retourner si la validation échoue.
     * @return array Un tableau associatif contenant la fonction de validation ('validator')
     * et le message d'erreur ('message').
     */
    public static function withMessage(callable $validator, string $message): array
    {
        return [
            'validator' => $validator,
            'message' => $message
        ];
    }

    /**
     * Règles de validation réutilisables.
     *
     * Ces méthodes statiques retournent des fonctions de validation anonymes (callables)
     * qui peuvent être utilisées comme règles de validation courantes. Elles couvrent
     * des cas d'utilisation fréquents tels que la vérification de chaînes de caractères
     * obligatoires, les formats d'e-mail, les mots de passe, etc. Chaque méthode
     * retourne une fonction qui prend la valeur à valider en argument et retourne
     * true si la validation réussit, false sinon.
     */

    /**
     * Vérifie si la valeur est une chaîne de caractères non vide.
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est
     * une chaîne de caractères non vide, false sinon.
     */
    public static function requiredString(): callable
    {
        return function ($val) {
            return !empty($val) && is_string($val);
        };
    }

    /**
     * Vérifie si la valeur est une chaîne de caractères non vide et ne dépasse pas une longueur maximale.
     *
     * @param int $maxLength La longueur maximale autorisée pour la chaîne de caractères (par défaut, PHP_INT_MAX).
     * @return callable Une fonction de validation qui retourne true si la valeur est une chaîne
     * de caractères non vide et dont la longueur est inférieure ou égale à $maxLength, false sinon.
     */
    public static function requiredStringMax($maxLength = PHP_INT_MAX): callable
    {
        return function ($val) use ($maxLength) {
            return !empty($val) && is_string($val) && strlen($val) <= $maxLength;
        };
    }

    /**
     * Vérifie si la valeur est une adresse e-mail valide et ne dépasse pas 100 caractères.
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est une
     * adresse e-mail valide (selon `filter_var`) et ne dépasse pas 100 caractères, false sinon.
     */
    public static function email(): callable
    {
        return function ($val) {
            return !empty($val) && filter_var($val, FILTER_VALIDATE_EMAIL) && strlen($val) <= 100;
        };
    }

    /**
     * Vérifie si la valeur est un mot de passe valide (au moins 8 caractères, contenant au moins une majuscule, une minuscule et un chiffre).
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est un mot de passe
     * valide selon les critères spécifiés, false sinon.
     */
    public static function password(): callable
    {
        return function ($val) {
            return !empty($val) && is_string($val) && strlen($val) >= 8 &&
                preg_match('/[A-Z]/', $val) && // Au moins une lettre majuscule
                preg_match('/[a-z]/', $val) && // Au moins une lettre minuscule
                preg_match('/[0-9]/', $val);   // Au moins un chiffre
        };
    }

    /**
     * Vérifie si la valeur est un numéro de téléphone optionnel (vide ou au format de 10 chiffres).
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est vide ou
     * une chaîne de caractères de 10 chiffres, false sinon.
     */
    public static function optionalPhone(): callable
    {
        return function ($val) {
            return empty($val) || (is_string($val) && preg_match('/^[0-9]{10}$/', $val));
        };
    }

    /**
     * Vérifie si la valeur est un entier positif.
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est un nombre
     * (entier ou flottant) strictement supérieur à zéro, false sinon.
     */
    public static function positiveInt(): callable
    {
        return function ($val) {
            return is_numeric($val) && $val > 0;
        };
    }

    /**
     * Vérifie si la valeur est un nombre compris dans une plage spécifiée (inclusivement).
     *
     * @param float|int $min La valeur minimale autorisée.
     * @param float|int $max La valeur maximale autorisée.
     * @return callable Une fonction de validation qui retourne true si la valeur est un nombre
     * compris entre $min et $max (inclus), false sinon.
     */
    public static function range(float|int $min, float|int $max): callable
    {
        return function ($val) use ($min, $max) {
            return is_numeric($val) && $val >= $min && $val <= $max;
        };
    }

    /**
     * Vérifie si la valeur est une latitude valide (nombre entre -90 et 90 inclus).
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est un nombre
     * compris entre -90 et 90 (inclus), false sinon.
     */
    public static function latitude(): callable
    {
        return function ($val) {
            return is_numeric($val) && $val >= -90 && $val <= 90;
        };
    }

    /**
     * Vérifie si la valeur est une longitude valide (nombre entre -180 et 180 inclus).
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est un nombre
     * compris entre -180 et 180 (inclus), false sinon.
     */
    public static function longitude(): callable
    {
        return function ($val) {
            return is_numeric($val) && $val >= -180 && $val <= 180;
        };
    }

    /**
     * Vérifie si la valeur est un code postal français valide (chaîne de 5 chiffres).
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est une
     * chaîne de caractères de 5 chiffres non vide, false sinon.
     */
    public static function codePostal(): callable
    {
        return function ($val) {
            return !empty($val) && is_string($val) && preg_match('/^[0-9]{5}$/', $val);
        };
    }

    /**
     * Vérifie si la valeur est un numéro de téléphone français valide (chaîne de 10 chiffres).
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est une
     * chaîne de caractères de 10 chiffres non vide, false sinon.
     */
    public static function telephone(): callable
    {
        return function ($val) {
            return !empty($val) && is_string($val) && preg_match('/^[0-9]{10}$/', $val);
        };
    }

    /**
     * Vérifie si la valeur est une URL valide (ou vide, pour les champs optionnels).
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est vide
     * ou une URL valide (selon `filter_var`), false sinon.
     */
    public static function url(): callable
    {
        return function ($val) {
            return empty($val) || (is_string($val) && filter_var($val, FILTER_VALIDATE_URL));
        };
    }

    /**
     * Vérifie si la valeur est un tableau non vide.
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est un tableau
     * contenant au moins un élément, false sinon.
     */
    public static function nonEmptyArray(): callable
    {
        return function ($val) {
            return is_array($val) && !empty($val);
        };
    }

    /**
     * Vérifie si la valeur est un tableau (vide ou non).
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est un tableau,
     * false sinon.
     */
    public static function array(): callable
    {
        return function ($val) {
            return is_array($val);
        };
    }

    /**
     * Vérifie si la valeur est un tableau et si tous ses éléments sont des entiers positifs.
     *
     * @return callable Une fonction de validation qui retourne true si la valeur est un tableau
     * et que tous ses éléments sont des nombres entiers strictement supérieurs à zéro, false sinon.
     */
    public static function arrayOfPositiveInts(): callable
    {
        return function ($val) {
            if (!is_array($val)) {
                return false;
            }

            foreach ($val as $item) {
                if (!is_numeric($item) || $item <= 0) {
                    return false;
                }
            }

            return true;
        };
    }

    /**
     * Vérifie si la valeur est un tableau d'entiers uniques dans une plage spécifiée.
     *
     * @param int $min La valeur minimale autorisée pour les entiers.
     * @param int $max La valeur maximale autorisée pour les entiers.
     * @return callable Une fonction de validation qui retourne true si la valeur est un tableau
     * d'entiers uniques, où chaque entier est compris entre $min et $max (inclus), false sinon.
     */
    public static function arrayOfUniqueIntsInRange($min, $max)
    {
        return function ($values) use ($min, $max) {
            if (!is_array($values)) return false;
            $unique = array_unique($values);

            if (count($unique) !== count($values)) return false;

            foreach ($unique as $value) {
                if (!is_int($value) || $value < $min || $value > $max) {
                    return false;
                }
            }

            return true;
        };
    }

    /**
     * Vérifie si la valeur est une date valide dans un format spécifié.
     *
     * @param string $format Le format de date attendu (par défaut, 'Y-m-d').
     * @return callable Une fonction de validation qui retourne true si la valeur est une chaîne
     * de caractères représentant une date valide dans le format spécifié, false sinon.
     */
    public static function date($format = 'Y-m-d'): callable
    {
        return function ($val) use ($format) {
            if (empty($val) || !is_string($val)) {
                return false;
            }

            $date = DateTime::createFromFormat($format, $val);
            return $date && $date->format($format) === $val;
        };
    }
}

?>