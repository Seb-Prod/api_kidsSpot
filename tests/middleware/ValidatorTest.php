<?php

require_once __DIR__ . '/../../middleware/Validator.php';
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
class ValidatorRequiredStringMaxTest
{
    public function runTests()
    {
        $this->testValidStrings();
        $this->testEmptyValues();
        //$this->testNonStringValues();
        $this->testStringsTooLong();
        $this->testUtf8Strings();
        $this->testIntegrationWithValidate();

        echo "\n✅ Tous les tests sont terminés !\n";
    }

    public function testValidStrings()
    {
        echo "\n=== ✅ Test : chaînes valides ===\n";

        $validator = Validator::requiredStringMax(10);
        $cases = [
            'Test' => true,
            'Bonjour' => true,
            '123456789' => true,
            '1234567890' => true,
        ];

        foreach ($cases as $input => $expected) {
            $result = $validator($input);
            $this->printResult($input, $result, $expected);
        }
    }

    public function testEmptyValues()
    {
        echo "\n=== ✅ Test : valeurs vides ===\n";

        $validator = Validator::requiredStringMax(10);
        $cases = [
            '' => false,
            null => false,
        ];

        foreach ($cases as $input => $expected) {
            $inputDesc = var_export($input, true);
            $result = $validator($input);
            $this->printResult($inputDesc, $result, $expected);
        }
    }

    public function testNonStringValues()
    {
        echo "\n=== ✅ Test : valeurs non-string ===\n";

        $validator = Validator::requiredStringMax(10);
        $cases = [
            123 => false,
            1.23 => false,
            [] => false,
            (object)[] => false,
            true => false,
        ];

        foreach ($cases as $input => $expected) {
            $type = gettype($input);
            try {
                $result = $validator($input);
                $this->printResult("Type $type", $result, $expected);
            } catch (TypeError $e) {
                echo "Type $type: SUCCÈS (exception attendue)\n";
            }
        }
    }

    public function testStringsTooLong()
    {
        echo "\n=== ✅ Test : chaînes trop longues ===\n";

        $validator = Validator::requiredStringMax(10);
        $cases = [
            'Cette chaîne est trop longue' => false,
            '12345678901' => false,
        ];

        foreach ($cases as $input => $expected) {
            $length = mb_strlen($input, 'UTF-8');
            $result = $validator($input);
            $this->printResult("($length caractères) '$input'", $result, $expected);
        }
    }

    public function testUtf8Strings()
    {
        echo "\n=== ✅ Test : chaînes UTF-8 ===\n";

        $validator = Validator::requiredStringMax(9); // max 9 caractères
        $cases = [
            'éléphant' => true,         // 8 caractères
            'éééééééééé' => false,      // 10 caractères accentués
            'café' => true,             // 4 caractères
            '漢字カタカナ' => true,       // 5 caractères japonais
        ];

        foreach ($cases as $input => $expected) {
            $length = mb_strlen($input, 'UTF-8');
            $result = $validator($input);
            $this->printResult("UTF-8 ($length) '$input'", $result, $expected);
        }
    }

    public function testIntegrationWithValidate()
    {
        echo "\n=== ✅ Test : intégration avec Validator::validate() ===\n";

        $validData = [
            'nom' => 'Martin',
            'description' => 'Courte desc',
        ];

        $invalidData = [
            'nom' => '',
            'description' => 'Cette description est beaucoup trop longue pour le test et va donc échouer',
        ];

        $rules = [
            'nom' => Validator::withMessage(
                Validator::requiredStringMax(20),
                'Le nom est requis et ne doit pas dépasser 20 caractères'
            ),
            'description' => Validator::withMessage(
                Validator::requiredStringMax(30),
                'La description est requise et ne doit pas dépasser 30 caractères'
            ),
        ];

        $errorsValid = Validator::validate($validData, $rules);
        echo "→ Données valides : " . (empty($errorsValid) ? "✅ SUCCÈS" : "❌ ÉCHEC") . "\n";

        $errorsInvalid = Validator::validate($invalidData, $rules);
        echo "→ Données invalides : " . (count($errorsInvalid) === 2 ? "✅ SUCCÈS (2 erreurs)" : "❌ ÉCHEC") . "\n";
        print_r($errorsInvalid);
    }

    private function printResult(string $label, bool $actual, bool $expected)
    {
        if ($actual === $expected) {
            echo "$label: ✅ SUCCÈS\n";
        } else {
            echo "$label: ❌ ÉCHEC (attendu: " . var_export($expected, true) . ", obtenu: " . var_export($actual, true) . ")\n";
        }
    }
}

$test = new ValidatorRequiredStringMaxTest();
$test->runTests();