<?php
ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/result-exercise.css';
$headTitle = "Exercise: {$exercise['title']}";

require_once VIEW_DIR . '/layouts/header.php';
?>

<div class="container">
    <h2><?= htmlspecialchars($fulfillment['created_at']) ?></h2>
    <?php foreach ($fields as $field) { ?>
        <h3><?= htmlspecialchars($field['label']) ?></h3>
        <?php foreach ($answers as $answer) { ?>
            <?php if ($answer['id_fields'] == $field['id_fields']) { ?>
                <?= htmlspecialchars($answer['value']) ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</div>

<?php
$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';