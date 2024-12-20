<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description This page is for the take-exercise view
 */

ob_start();

$cssPath = '/css/take-exercise.css';

require_once VIEW_DIR . '/layouts/header.php';
?>

<main class="container">
    <ul class="answering-list">
        <?php foreach ($exercises as $exercise) : if ($exercise['id_status'] != 2) continue; ?>
            <li class="row">
                <div class="column card">
                    <div class="title"><?= $exercise['title'] ?></div>
                    <a class="button" href="/exercises/<?= $exercise['id_exercises'] ?>/fulfillments/new">Take it</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php

$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
