<!DOCTYPE html>
<html lang="fr">
<meta charset="UTf-8" />

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/fulfill-exercise.css" />
</head>

<body>
    <header class="heading">
        <section class="container">
            <a href="/">
                <img class="header-img" src="/images/logo.png" />
            </a>
            <span class="exercise-title">Exercise: <b><?= $exercise['title'] ?> </b></span>
        </section>
    </header>
    <main class="container">
        <h1>Your take</h1>

        <?php
        $message = ($_SESSION['state'] == 'edit')
            ? "Bookmark this page, it's yours. You'll be able to come back later to finish."
            : "If you'd like to come back, simply submit it with blanks.";
        ?>

        <label><?= $message ?></label>

        <form action="/exercises/<?= $exercise['id_exercises'] ?>/fulfillments" method="post" accept-charset="UTF-8">
            <?php if ($_SESSION['state'] == "new"): ?>
                <input type="hidden" name="created_at" value="<?= date("Y-m-d H:i:s e") ?>">
            <?php else: ?>
                <input type="hidden" name="updated_at" value="<?= date("Y-m-d H:i:s e") ?>">
            <?php endif; ?>
            <?php foreach ($fields as $field): ?>
                <h3><?= htmlspecialchars($field['label']) ?></h3>
                <?php if ($field['id_fields_type'] == 1): ?>
                    <input type="hidden" name="idfield<?= htmlspecialchars($field['id_fields']) ?>[]" value="<?= $field['id_fields'] ?>">
                    <input type="text" name="idfield<?= htmlspecialchars($field['id_fields']) ?>[]" id="<?= htmlspecialchars($field['id_fields']) ?>" value="<?= $field['value'] ?>" />

                <?php else: ?>
                    <input type="hidden" name="idfield<?= htmlspecialchars($field['id_fields']) ?>[]" value="<?= $field['id_fields'] ?>" value="<?= $field['value'] ?>">
                    <textarea id="<?= htmlspecialchars($field['id_fields']) ?>" name="idfield<?= htmlspecialchars($field['id_fields']) ?>[]" rows="4" cols="50"></textarea>

                <?php endif; ?>

            <?php endforeach; ?>
            <input type="submit" class="action" data-disable-with="Save">
        </form>
    </main>
</body>


</html>