<!DOCTYPE html>
<html lang="fr">

<head>
    <!--
    Authors: Nathan Chauveau, David Dieperink, Julien Schneider
    Version: 18.12.2024
    Description: This is the HTML document structure for the ExerciseLooper application.
    It includes meta tags for character encoding and viewport configuration.
    The title of the page is set to "ExerciseLooper". Additionally, it links to a custom CSS file, a dynamic CSS file using PHP for custom styling ($cssPath), and an external FontAwesome stylesheet for icon support.
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExerciseLooper</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="<?= $cssPath ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?= $slot ?>
</body>

</html>