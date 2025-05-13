<?php
require 'middleware/ValidatorTest.php';
require 'config/JWTTest.php';

$verbose = false;

// Analyse des arguments de la ligne de commande
foreach ($argv as $arg) {
    if ($arg === '--silent') {
        $verbose = false;
    } elseif ($arg === '--verbose') {
        $verbose = true;
    }
}

$validatorTest = new ValidatorRequiredStringMaxTest($verbose);
$validatorTest->runTests();

$jwtTest = new JWTTest($verbose);
$jwtTest->runTests();

$totalOk = $validatorTest->ok + $jwtTest->ok;
$totalKo = $validatorTest->ko + $jwtTest->ko;

echo "\nRésultat global : ✅ $totalOk tests réussis, ❌ $totalKo échecs\n";
