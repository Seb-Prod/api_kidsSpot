<?php

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            // Déstructuration de la règle
            $validationFn = $rule['validator'] ?? $rule;
            $errorMessage = $rule['message'] ?? "Le champ $field n'est pas valide";
            
            if (!$validationFn($value)) {
                $errors[$field] = $errorMessage;
            }
        }
        return $errors;
    }

    // Fonction utilitaire pour créer une règle avec message personnalisé
    public static function withMessage(callable $validator, string $message): array
    {
        return [
            'validator' => $validator,
            'message' => $message
        ];
    }

    // Règles réutilisables
    public static function requiredString(): callable
    {
        return function ($val) {
            return !empty($val) && is_string($val);
        };
    }

    public static function requiredStringMax($maxLength = PHP_INT_MAX): callable
    {
        return function ($val) use ($maxLength) {
            return !empty($val) && is_string($val) && strlen($val) <= $maxLength;
        };
    }

    public static function email(): callable
    {
        return function ($val) {
            return !empty($val) && filter_var($val, FILTER_VALIDATE_EMAIL) && strlen($val) <= 100;
        };
    }

    public static function password(): callable
    {
        return function ($val) {
            return !empty($val) && is_string($val) && strlen($val) >= 8 &&
                preg_match('/[A-Z]/', $val) &&
                preg_match('/[a-z]/', $val) &&
                preg_match('/[0-9]/', $val);
        };
    }

    public static function optionalPhone(): callable
    {
        return function ($val) {
            return empty($val) || (is_string($val) && preg_match('/^[0-9]{10}$/', $val));
        };
    }

    public static function positiveInt(): callable
    {
        return function ($val) {
            return is_numeric($val) && $val > 0;
        };
    }

    public static function range(float|int $min, float|int $max): callable
    {
        return function ($val) use ($min, $max) {
            return is_numeric($val) && $val >= $min && $val <= $max;
        };
    }

    /**
     * Vérifie si la valeur est une latitude valide (-90 à 90)
     */
    public static function latitude(): callable
    {
        return function ($val) {
            return is_numeric($val) && $val >= -90 && $val <= 90;
        };
    }

    /**
     * Vérifie si la valeur est une longitude valide (-180 à 180)
     */
    public static function longitude(): callable
    {
        return function ($val) {
            return is_numeric($val) && $val >= -180 && $val <= 180;
        };
    }

    /**
     * Vérifie si la valeur est un code postal français valide
     */
    public static function codePostal(): callable
    {
        return function ($val) {
            return !empty($val) && is_string($val) && preg_match('/^[0-9]{5}$/', $val);
        };
    }

    /**
     * Vérifie si la valeur est un numéro de téléphone français valide
     */
    public static function telephone(): callable
    {
        return function ($val) {
            return !empty($val) && is_string($val) && preg_match('/^[0-9]{10}$/', $val);
        };
    }

    /**
     * Vérifie si la valeur est une URL valide
     */
    public static function url(): callable
    {
        return function ($val) {
            return empty($val) || (is_string($val) && filter_var($val, FILTER_VALIDATE_URL));
        };
    }

    /**
     * Vérifie si la valeur est un tableau non vide
     */
    public static function nonEmptyArray(): callable
    {
        return function ($val) {
            return is_array($val) && !empty($val);
        };
    }

    /**
     * Vérifie si la valeur est un tableau (vide ou non)
     */
    public static function array(): callable
    {
        return function ($val) {
            return is_array($val);
        };
    }

    /**
     * Vérifie si tous les éléments du tableau sont des entiers positifs
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