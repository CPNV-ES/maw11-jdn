<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description This page is for the fulfill-exercise view
 */

ob_start();

$cssPath = '/css/fulfill-exercise.css';
$headTitle = "Exercise: {$exercise['title']}";

require_once VIEW_DIR . '/layouts/header.php';

$message = ($_SESSION['state'] == 'edit')
    ? "Bookmark this page, it's yours. You'll be able to come back later to finish."
    : "If you'd like to come back, simply submit it with blanks.";
?>

<main class="container">
    <h1>Your take</h1>
    <label><?= $message ?></label>
    <form action="
            <?php if ($_SESSION['state'] == "new"): ?>
            /exercises/<?= $exercise['id_exercises'] ?>/fulfillments/
             <?php else: ?>
            /exercises/<?= $exercise['id_exercises'] ?>/fulfillments/<?= $exerciseAnswer['id_fulfillments'] ?>/edit
             <?php endif; ?>
            " method="post" accept-charset="UTF-8">
        <?php if ($_SESSION['state'] == "new"): ?>
            <input type="hidden" type="date" name="created_at" value="<?= date("Y-m-d H:i:s") ?>">
        <?php else: ?>
            <input type="hidden" type="date" name="updated_at" value="<?= date("Y-m-d H:i:s") ?>">
        <?php endif;
        $positionAnswer = 0;
        foreach ($fields as $field): ?>

            <h3><?= htmlspecialchars($field['label']) ?></h3>
            <?php if ($field['id_fields_type'] == 1): ?>
                <input type="hidden" name="idfield<?= htmlspecialchars($field['id_fields']) ?>[]" value="<?= $field['id_fields'] ?>">
                <input type="text" name="idfield<?= htmlspecialchars($field['id_fields']) ?>[]" id="<?= htmlspecialchars($field['id_fields']) ?>" value="<?= $answers[$positionAnswer]['value'] ?? '' ?>" />

            <?php else: ?>
                <input type="hidden" name="idfield<?= htmlspecialchars($field['id_fields']) ?>[]" value="<?= $field['id_fields'] ?>">
                <textarea id="<?= htmlspecialchars($field['id_fields']) ?>" name="idfield<?= htmlspecialchars($field['id_fields']) ?>[]" rows="4" cols="50"><?= $answers[$positionAnswer]['value'] ?? ''  ?></textarea>

        <?php endif;
            $positionAnswer = $positionAnswer + 1;
        endforeach; ?>

        <input type="submit" class="action" data-disable-with="Save">
    </form>
</main>
<?php

$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
