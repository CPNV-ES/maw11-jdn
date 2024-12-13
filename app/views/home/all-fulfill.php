<?php
ob_start();

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
                <?php for ($i = 0; $i < 3; $i++) { ?>
                    <th>
                    </th>
                <?php }; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($answers as $createAt => $fieldValues) : ?>
                <tr>
                    <td><?= htmlspecialchars($createAt); ?></td>
                    <td>
                        <a href="<?= $createdAtWhidId[$createAt]; ?>">Show</a>
                    </td>
                    <?php foreach ($fulfillments as $fulfillment):
                        if ($fulfillment['id_exercises'] == $exercise['id_exercises'] && $fulfillment['created_at'] == $createAt):  ?>
                            <td>
                                <a href="<?= $fulfillment['id_fulfillments'] ?>/edit">Edit</a>
                            </td>

                            <td>
                                <a href="<?= $fulfillment['id_fulfillments'] ?>/destroy">Destroy</a>
                            </td>
                    <?php endif;
                    endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php

$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
