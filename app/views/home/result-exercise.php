<?php
ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/result-exercise.css';
$headTitle = "Exercise: {$exercise['title']}";

require_once VIEW_DIR . '/layouts/header.php';


?>

<div class="container">
    <table class="table-style">
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
            <?php foreach ($answers as $createAt => $fieldValues) : ?>
                <tr>
                    <!-- Affiche la date de création une seule fois -->
                    <td><?= htmlspecialchars($createAt); ?></td>
                    
                    <!-- Afficher les réponses pour chaque champ de l'exercice -->
                    <?php foreach ($fields as $field) : ?>
                        <td>
                            <i class="<?= $fieldValues[$field['id_fields']] ?>"></i>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php

$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
