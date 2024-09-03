<?php

require_once '../app/core/Model.php';

try {
    $model = new Model();
    echo "<p>Connection successfull !</p>";
} catch (Exception $e) {
    // Si la connexion échoue, afficher un message d'erreur
    echo "<p>Connection error : " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <!-- Le message de connexion sera affiché ici -->
    <p>Test</p>
</body>

</html>