<?php
ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/result-exercise.css';
$headTitle = "Exercise: {$exercise['title']}";

require_once VIEW_DIR . '/layouts/header.php';

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
<div class="container">
    <table class="table-style">
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

</div>
<?php

$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
