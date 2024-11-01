<!DOCTYPE html>
<html lang="en">
<meta charset="UTf-8" />

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/create-exercise.css" />
</head>

<body>
    <header class="heading managing">
        <section class="container">
            <a href="/">
                <img class="header-img" src="/images/logo.png" />
            </a>
            <span class="exercise-title">New exercise</span>
        </section>
    </header>

    <main class="container">
        <title>ExerciseLooper</title>

        <h1>New Exercise</h1>
        <form action="/exercises" method="post" accept-charset="UTF-8">
            <div class="field">
                <label for="exercise_title">Title</label>
                <input
                    type="text"
                    name="exercises_title"
                    id="exercise_title" />
            </div>
            <div>
                <input
                    class="button"
                    type="submit"
                    name="commit"
                    value="Create Exercise"
                    data-disable-with="Create Exercise" />
            </div>
        </form>
    </main>
</body>

</html>