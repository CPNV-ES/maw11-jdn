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
            <tr>
                <?php foreach ($fields as $field) {
                    $answers = ExerciseController::getAnswers($field['id_fields']);?>
                    <td>
                        
                    
                    <?php foreach ($answers as $answer) {?>
                    

                        <?= $answer['create_at'] ?>

                    
                    
                <?php 
                    }?>
                    </td>
                    <td>caca</td>
                <?php }?>
            </tr>
        </tbody>
    </table>
</body>

</html>