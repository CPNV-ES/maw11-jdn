<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description This page is for the create-exercise view
 */

ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/create-exercise.css';
$headTitle = "New exercise";

require_once VIEW_DIR . '/layouts/header.php'
?>

<main class="container">
    <title>ExerciseLooper</title>

    <h1>New Exercise</h1>
    <form action="/exercises" method="post" accept-charset="UTF-8">
        <div class="field">
            <label for="exercise_title">Title</label>
            <input
                type="text"
                name="exercises_title"
                id="exercise_title" />
        </div>
        <div>
            <input
                class="button"
                type="submit"
                name="commit"
                value="Create Exercise"
                data-disable-with="Create Exercise" />
        </div>
    </form>
</main>

<?php

$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
