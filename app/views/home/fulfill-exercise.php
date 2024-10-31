<!DOCTYPE html>
<html lang="fr">
<meta charset="UTf-8" />

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/fulfill-exercise.css" />
</head>
<?php
$recordsExercise = [
    [
        'id' => 1,
        'title' => 'Record 1 Title',

    ],
];

foreach ($recordsExercise as $record) {
    $title = $record['title'];
    $id_exercise = $record['id'];
}
$recordsQuestions = [
    [
        'id' => 1,
        'label' => 'Question1',
        'id_exercise' => 1,
        'id_field_type' => 2,

    ],
    [
        'id' => 2,
        'label' => 'Question2',
        'id_exercise' => 1,
        'id_field_type' => 1,
    ],

];
?>

<body>
    <header class="heading">
        <section class="container">
            <a href="/">
                <img class="header-img" src="/images/logoLooper.png" />
            </a>
            <span class="exercise-label">Exercise: <b><?= $title ?> </b></span>
        </section>
    </header>
    <main class="container">
        <h1>Your take</h1>

        <?php
        $message = ($_SESSION['state'] == 'edit')
            ? "Bookmark this page, it's yours. You'll be able to come back later to finish."
            : "If you'd like to come back, simply submit it with blanks.";
        ?>

        <label><?= $message ?></label>

        <form action="/exercises/<?= $id_exercise ?>/fulfillments/edit" method="post" accept-charset="UTF-8">
            <?php foreach ($recordsQuestions as $record): ?>
                <h3><?= htmlspecialchars($record['label']) ?></h3>
                <?php if ($record['id_field_type'] == 1): ?>
                    <input type="text" name="<?= htmlspecialchars($record['label']) ?>" id="<?= htmlspecialchars($record['label']) ?>" />
                <?php else: ?>
                    <textarea id="<?= htmlspecialchars($record['label']) ?>" name="<?= htmlspecialchars($record['label']) ?>" rows="4" cols="50"></textarea>
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="submit" class="action" name="commit" value="Save" data-disable-with="Save">
        </form>
    </main>
</body>


</html>