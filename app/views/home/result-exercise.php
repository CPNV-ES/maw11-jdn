<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description This page is for the result-exercise view
 */

ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/result-pages.css';
$headTitle = "Exercise: <a class='title-exercise' href='/exercises/{$exercise['id_exercises']}/results'><strong>{$exercise['title']}</strong></a>";

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
                        <a href="results/<?= $field['id_fields']; ?>"><?= $field['label'] ?></a>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($answers as $createAt => $fieldValues) : ?>
                <tr>
                    <!-- Affiche la date de création une seule fois -->
                    <td><a href="fulfillments/<?= $createdAtWhidId[$createAt]; ?>"><?= htmlspecialchars($createAt); ?></a></td>

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
