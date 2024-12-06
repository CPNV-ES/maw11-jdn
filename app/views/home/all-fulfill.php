<?php
ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/all-fulfill.css';
$headTitle = "Exercise : <strong>{$exercise['title']}</strong>";

require_once VIEW_DIR . '/layouts/header.php'
?>
<div class="container">
    <h1>Fulfillment for <?= $exercise['title'] ?> </h1>
    <table class="table-style">
        <thead>
            <tr>
                <th>Taken at
                </th>
                <?php foreach ($fields as $field) { ?>
                    <th>

                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($answers as $createAt => $fieldValues) : ?>
                <tr>
                    <td><?= htmlspecialchars($createAt); ?></td>
                    <td>
                        <a href="fulfillments/<?= $createdAtWhidId[$createAt]; ?>">Show</a>
                    </td>
                    <?php foreach ($fulfillments as $fulfillment): ?>
                        <?php if ($fulfillment['id_exercises'] == $exercise['id_exercises'] && $fulfillment['created_at'] == $createAt):  ?>
                            <td>
                                <a href="exercises/<?= $exercise['id_exercises'] ?>/fulfillments/<?= $fulfillment['id_fulfillments'] ?>/edit">Edit</a>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td>
                        <a href="***">Destroy</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php

$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
