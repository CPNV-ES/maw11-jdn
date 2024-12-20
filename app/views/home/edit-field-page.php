<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description This page is for the edit-field view
 */

ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/edit-field-page.css';
$headTitle = "Exercise: <a class='title-exercise' href='/exercises/{$exercise['id_exercises']}/fields'><strong>{$exercise['title']}</strong></a>";

require_once VIEW_DIR . '/layouts/header.php';
?>
<div class="container">
    <h1>Editing Field</h1>

    <form action="/exercises/<?= $exercise['id_exercises'] ?>/fields/<?= $field['id_fields'] ?>/update" method="post" accept-charset="UTF-8">
        <div>
            <label for="field_label">Label</label>
            <input
                required
                type="text"
                name="field_label"
                id="field_label"
                value="<?= $field['label'] ?>" />
        </div>
        <div>
            <label for="field_type">Value kind</labe>
                <select name="field_type" id="field_type" required>
                    <option value="1" <?= $field['id_fields_type'] == 1 ? 'selected' : '' ?>>Single line text</option>
                    <option value="2" <?= $field['id_fields_type'] == 2 ? 'selected' : '' ?>>List of single lines</option>
                    <option value="3" <?= $field['id_fields_type'] == 3 ? 'selected' : '' ?>>Multi-line text</option>
                </select>
        </div>
        <div>
            <input
                class="button"
                type="submit"
                name="commit"
                value="Edit field"
                data-disable-with="Update Exercise" />
        </div>
    </form>
</div>
<?php
$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
