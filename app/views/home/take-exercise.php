<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/take-exercise.css">
</head>

<body>
    <header>
        <section class="container">
            <a href="/">
                <img src="../images/logo.png">
            </a>
        </section>
    </header>
    <main class="container">
        <ul class="answering-list">
            <?php foreach ($exercises as $exercise) : if ($exercise['id_status'] != 2) continue; ?>
                <li class="row">
                    <div class="column card">
                        <div class="title"><?= $exercise['title'] ?></div>
                        <a class="button" href="/exercises/<?= $exercise['id_exercises'] ?>/fulfillments/new">Take it</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>

</html>