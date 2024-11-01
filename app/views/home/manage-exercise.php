<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/manage-exercice.css">
</head>

<body>
    <header class="heading results">
        <div class="container">
            <a href="/">
                <img class="header-img" src="images/looper-logo.png" alt="">
            </a>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <div class="column">
                <h1>Building</h1>
                <table class="records">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($exercises as $exercise) {
                            if ($exercise['id_status'] == 1) {
                        ?>
                                <tr>
                                    <td>
                                        <?= $exercise['title'] ?>
                                    </td>
                                    <td>
                                        <a title="Manage fields" href="/exercises/<?= $exercise['id_exercises'] ?>/fields">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/<?= $exercise['id_exercises'] ?>/delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <?php
                                        //Check if the exercise contains one or more fields to enable the exercise to be passed in response mode.
                                        if (count(ExerciseController::getAllFieldById($exercise['id_exercises'])) >= 1) { ?>
                                            <a href="/exercises/<?= $exercise['id_exercises'] ?>/update/answering">
                                                <i class="fa fa-comment"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="column">
                <h1>Answering</h1>
                <table class="records">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($exercises as $exercise) {
                            if ($exercise['id_status'] == 2) {
                        ?>
                                <tr>
                                    <td>
                                        <?= $exercise['title'] ?>
                                    </td>
                                    <td>
                                        <a title="Show results" href="/exercises/<?= $exercise['id_exercises'] ?>/results">
                                            <i class="fa fa-chart-bar"></i>
                                        </a>
                                        <a title="Close" rel="nofollow" data-method="put" href="/exercises/<?= $exercise['id_exercises'] ?>/update/closed">
                                            <i class="fa fa-minus-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="column">
                <h1>Closed</h1>
                <table class="records">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($exercises as $exercise) {
                            if ($exercise['id_status'] == 3) {
                        ?>
                                <tr>
                                    <td>
                                        <?= $exercise['title'] ?>
                                    </td>
                                    <td>
                                        <a title="Show results" href="/exercises/<?= $exercise['id_exercises'] ?>/results">
                                            <i class="fa fa-chart-bar"></i>
                                        </a>
                                        <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/<?= $exercise['id_exercises'] ?>/delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>