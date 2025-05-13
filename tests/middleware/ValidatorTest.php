<?php

require_once __DIR__ . '/../../middleware/Validator.php';

class ValidatorRequiredStringMaxTest
{
    public $ok = 0;
    public $ko = 0;

    // Nouveau : activer ou désactiver les sorties console
    private $verbose;

    public function __construct($verbose = true)
    {
        $this->verbose = $verbose;
    }

    public function runTests()
    {
        $this->log("\n=== Tests du Validator 'requiredStringMax' ===\n");

        $this->log("\n--- Test : chaînes valides ---\n");
        $this->test('Test', 10, true);
        $this->test('Bonjour', 10, true);
        $this->test('123456789', 10, true);
        $this->test('1234567890', 10, true);

        $this->log("\n--- Test : valeurs vides ---\n");
        $this->test('', 10, false);

        $this->log("\n--- Test : valeurs non-string ---\n");
        $this->test(123, 10, false);
        $this->test(true, 10, false);

        $this->log("\n--- Test : chaînes trop longues ---\n");
        $this->test('Texte trop long', 10, false);
        $this->test('12345678901', 10, false);
        $this->test('ééééééééééé', 10, false);

        $this->log("\n--- Test : chaînes UTF-8 ---\n");
        $this->test('éléphant', 10, true);
        $this->test('éééééééééé', 10, true);
        $this->test('éléphant', 10, true);
        $this->test('café', 10, true);
        $this->test('漢字カタカナ', 10, true);
    }

    public function test($val, $max, $attendu)
    {
        $validator = Validator::requiredStringMax($max);
        $result = $validator($val);
        $this->printResult($val, $result, $attendu);
    }

    private function printResult(string $label, bool $actual, bool $expected)
    {
        if ($actual === $expected) {
            $this->ok++;
            $this->log("$label : ✅ SUCCÈS\n");
        } else {
            $this->ko++;
            $this->log("$label : ❌ ÉCHEC (attendu: " . var_export($expected, true) . ", obtenu: " . var_export($actual, true) . ")\n");
        }
    }

    private function log($message)
    {
        if ($this->verbose) {
            echo $message;
        }
    }
}