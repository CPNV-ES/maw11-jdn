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
    $nom_exercise = $record['title'];
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
    <header class="heading managing">
        <section class="container">
            <a href="/">
                <img class="header-img" src="/images/logoLooper.png" />
            </a>
            <span class="exercise-label">Exercise: <b><?php echo $nom_exercise ?> </b></span>
        </section>
    </header>
    <main class="container">
        <h1>Your take</h1>

        <?php if ($_SESSION['state'] == 'edit') { ?>
            <label>Bookmark this page, it's yours. You'll be able to come back later to finish.</label>
        <?php } else { ?>
            <label>If you'd like to come back, simply submit it with blanks</label>
        <?php } ?>
        <form action="/exercises/<?= $id_exercise ?>/fulfillments/edit" accept-charset="UTF-8" method="post">
            <?php foreach ($recordsQuestions as $record) { ?>
                <h3><?= $record['label'] ?></h3>
                <?php if ($record['id_field_type'] == 1) {
                ?>
                    <input type="text" name="<?= $record['label'] ?>" id="<?= $record['label'] ?>">
                <?php  } else { ?>
                    <textarea id="<?= $record['label'] ?>" name="<?= $record['label'] ?>" rows="4" cols="50">
            </textarea>
                <?php } ?>
            <?php } ?>
            <input type="submit" class="action" name="commit" value="Save" data-disable-with="Save">
        </form>
    </main>
</body>


</html>