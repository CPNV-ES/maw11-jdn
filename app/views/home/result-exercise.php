<!DOCTYPE html>
<html lang="en">
<meta charset="UTf-8" />

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/result-exercise.css" />
</head>
<?php
$recordsQuestions = [
    [
        'id' => 1,
        'label' => 'Question1',
        'id_exercise' => 1,

    ],
    [
        'id' => 2,
        'label' => 'Question2',
        'id_exercise' => 1,

    ],
];

$recordsExerciseAnswers = [
    [

        'id' => 1,
        'date' => '09/10/24 8:30',
        'id_exercise' => 1,
    ],
    [

        'id' => 2,
        'date' => '09/10/24 8:40',
        'id_exercise' => 1,
    ]
];
$recordsQuestionAnswers = [
    [

        'id' => 1,
        'id_answers' => 1,
        'id_question' => 1,
        'value' => '234',
        'id_field_type' => 2,
    ],
    [

        'id' => 1,
        'id_answers' => 1,
        'id_question' => 2,
        'value' => '',
        'id_field_type' => 1,
    ]
];

$recordsExercise = [
    [
        'id' => 1,
        'title' => 'Record 1 Title',

    ],
];

foreach ($recordsExercise as $exercise) {
    $nom_exercise = $exercise['title'];
}

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
    <table class="container">
        <thead>
            <tr>
                <th>Take
                </th>
                <?php foreach ($recordsQuestions as $recordsQuestion) { ?>
                    <th>
                        <?= $recordsQuestion['label'] ?>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recordsExerciseAnswers as $recordsExerciseAnswer) { ?>
                <tr>
                    <td>
                        <?= $recordsExerciseAnswer['date'] ?>
                    </td>
                    <?php foreach ($recordsQuestionAnswers as $recordsQuestionAnswer) { ?>
                        <td>
                            <?php if ($recordsQuestionAnswer['value'] == null) { ?>
                                <i class="fa fa-x XIcon"></i>
                                <?php } else {
                                if ($recordsQuestionAnswer['id_field_type'] == 1) { ?>
                                    <i class="fa-solid fa-check VIcon"></i>
                                <?php } else { ?>
                                    <i class="fa-solid fa-check-double VIcon"></i>
                                <?php } ?>
                            <?php } ?>
                        </td>




                    <?php } ?>
                </tr>

            <?php } ?>
        </tbody>
    </table>
</body>

</html>