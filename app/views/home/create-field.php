<!DOCTYPE html>
<html lang="en">
<meta charset="UTf-8" />

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/create-field.css" />
</head>

<body>
    <header class="heading managing">
        <section class="container">
            <a href="/">
                <img class="header-img" src="/images/logo.png" />
            </a>
            <span class="exercise-title">Exercise: <?= $exercise['title'] ?></span>
        </section>
    </header>

    <main class="container">
        <title>ExerciseLooper</title>
        <div class="row">
            <div class="ListField">

                <h1>Fields</h1>
                <table class="FieldList">
                    <tr>
                        <th>Label</th>
                        <th>Value kind</th>
                        <th></th>
                    </tr>
                    <?php foreach ($fields as $field): ?>
                        <tr>
                            <td><?= $field['label'] ?></td>
                            <td><?php if ($field['id_fields_type'] == 0): ?>
                                    <p>Single line text</p>
                                <?php elseif ($field['id_fields_type'] == 1): ?>
                                    <p>List of single lines</p>
                                <?php else: ?>
                                    <p>Multi-line text</p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a title="Edit" href="/exercises/<?= $exercise['id_exercises'] ?>/fields/<?= $field['id_fields'] ?>/edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/<?= $exercise['id_exercises'] ?>/fields/<?= $field['id_fields'] ?>/destroy">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <a data-confirm="Are you sure? You won't be able to further edit this exercise" class="button" rel="nofollow" data-method="put" href="/exercises/<?= $exercise['id_exercises'] ?>update/answering"
                    onclick="location.href=this.href+'?id_status=2'">
                    <i class="fa-solid fa-comment"></i>
                    Complete and be ready for answers
                </a>

            </div>
            <div class="ListField">

                <h1>New Field</h1>
                <form action="/exercises/<?= $exercise['id_exercises'] ?>/fields" method="post" accept-charset="UTF-8">
                    <div class="field">
                        <label for="field_label">Label</label>
                        <input
                            required
                            type="text"
                            name="field_label"
                            id="field_label" />
                    </div>
                    <div class="field">
                        <label for="field_type">Value kind</label>
                        <select name="field_type" id="field_type" required>
                            <option value="0">Single line text</option>
                            <option value="1">List of single lines</option>
                            <option value="2">Multi-line text</option>

                        </select>
                    </div>
                    <div>
                        <input
                            class="button"
                            type="submit"
                            name="commit"
                            value="Create field"
                            data-disable-with="Create Exercise" />
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>