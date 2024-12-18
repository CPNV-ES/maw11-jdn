<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description This page is for the response-exercise view
 */

ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/result-pages.css';
$headTitle = "Exercise: <a class='title-exercise' href='/exercises/{$exercise['id_exercises']}/results'><strong>{$exercise['title']}</strong></a>";

require_once VIEW_DIR . '/layouts/header.php';
?>

<div class="container">
    <h2><?= htmlspecialchars($fulfillment[0]['created_at']) ?></h2>
    <?php foreach ($fields as $field) { ?>
        <h3><?= htmlspecialchars($field['label']) ?></h3>
    <?php foreach ($answers as $answer) {
            if ($answer['id_fields'] == $field['id_fields']) {
                echo htmlspecialchars($answer['value']);
            }
        }
    } ?>
</div>

<?php
$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
