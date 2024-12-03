<?php
ob_start();

$backgroundClass = 'managing';
$cssPath = '/css/result-pages.css';
$headTitle = "Exercise: <a class='title-exercise' href='/exercises/{$exercise['id_exercises']}/results'><strong>{$exercise['title']}</strong></a>";

require_once VIEW_DIR . '/layouts/header.php';
?>
<div class="container">
    <h2><?= htmlspecialchars($field['label']); ?></h2>
    <table class="table-style">
        <thead>
            <tr>
                <th>Take</th>
                <th>Content</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fulfillments as $fulfillment) {?>
            <tr>
                <td><a href="/exercises/<?= $fulfillment['id_exercises'];?>/fulfillments/<?= $fulfillment['id_fulfillments'];?>"><?= htmlspecialchars($fulfillment['created_at']); ?></a></td>
                <td><?= htmlspecialchars($answers[$fulfillment['created_at']]) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';