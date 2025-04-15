<?php

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            if (!$rule($value)) {
                $errors[] = $field;
            }
        }
        return $errors;
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
}
