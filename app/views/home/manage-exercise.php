<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/manage-exercice.css">
</head>

<?php

$records = [
    [
        'id' => 1,
        'title' => 'Record 1 Title',
    ],
    [
        'id' => 2,
        'title' => 'Record 2 Title',
    ],
    [
        'id' => 3,
        'title' => 'Record 3 Title',
    ],
    [
        'id' => 4,
        'title' => 'Record 4 Title',
    ]
];

?>

<body>
    <header class="heading results">
        <div class="container">
            <a href="/">
                <img src="images/looper-logo.png" alt="">
            </a>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <div class="column">
                <h1>Building</h1>
                <table class="records">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($records as $record) {
                        ?>
                            <!-- TODO : Retrieve records from database -->
                            <tr>
                                <td>
                                    <?= $record['title'] ?>
                                </td>
                                <td>
                                    <a title="Manage fields" href="/exercises/<?= $record['id'] ?>/fields">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/<?= $record['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="column">
                <h1>Answering</h1>
                <table class="records">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($records as $record) {
                        ?>
                            <!-- TODO : Retrieve records from database -->
                            <tr>
                                <td>
                                    <?= $record['title'] ?>
                                </td>
                                <td>
                                    <a title="Show results" href="/exercises/<?= $record['id'] ?>/results">
                                        <i class="fa fa-chart-bar"></i>
                                    </a>
                                    <a title="Close" rel="nofollow" data-method="put" href="/exercises/26?exercise%5Bstatus%5D=closed">
                                        <i class="fa fa-minus-circle"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="column">
                <h1>Closed</h1>
                <table class="records">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($records as $record) {
                        ?>
                            <!-- TODO : Retrieve records from database -->
                            <tr>
                                <td>
                                    <?= $record['title'] ?>
                                </td>
                                <td>
                                    <a title="Show results" href="/exercises/<?= $record['id'] ?>/results">
                                        <i class="fa fa-chart-bar"></i>
                                    </a>
                                    <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/<?= $record['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>