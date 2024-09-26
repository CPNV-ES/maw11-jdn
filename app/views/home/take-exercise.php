<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/take-exercise.css">
</head>

<?php

$records = [
    [
        'id' => 1,
        'title' => 'Record 1 Title',
    ],
    [
        'id' => 2,
        'title' => 'Record 2 Title',
    ],
    [
        'id' => 3,
        'title' => 'Record 3 Title',
    ],
    [
        'id' => 4,
        'title' => 'Record 4 Title',
    ]
];

?>

<body>
    <header>
        <section class="container">
            <a href="/">
                <img src="../images/logo.png">
            </a>
        </section>
    </header>
    <main class="container">
        <title>ExerciseLooper</title>
        <ul class="ansering-list">
            <!--TODO : foreach record -->
            <?php
            foreach ($records as $record) {
            ?>
                <li class="row">
                    <div class="column card">
                        <div class="title"><?= $record['title'] ?> </div>
                        <a class="button" href="/exercises/*id*/fulfillments/new">Take it</a>
                    </div>
                </li>
            <?php
            }
            ?>
        </ul>
    </main>
</body>

</html>