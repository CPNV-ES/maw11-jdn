<!DOCTYPE html>
<html lang="en">
<meta charset="UTf-8" />

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/result-exercise.css" />
</head>

<body>
    <header class="heading managing">
        <section class="container">
            <a href="/">
                <img class="header-img" src="/images/logo.png" />
            </a>
            <span class="exercise-title">Exercise: <b><?= $exercise['title'] ?></b></span>
        </section>
    </header>
    <table class="container">
        <thead>
            <tr>
                <th>Take
                </th>
                <?php foreach ($fields as $field) { ?>
                    <th>
                        <?= $field['label'] ?>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filterAnswers as $createAt => $fieldValues) : ?>
                <tr>
                    <!-- Affiche la date de création une seule fois -->
                    <td><?= htmlspecialchars($createAt); ?></td>
                    
                    <!-- Afficher les réponses pour chaque champ de l'exercice -->
                    <?php foreach ($fields as $field) : ?>
                        <td>
                            <?= isset($fieldValues[$field['id_fields']])
                                ? htmlspecialchars($fieldValues[$field['id_fields']])
                                : 'caca'; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>