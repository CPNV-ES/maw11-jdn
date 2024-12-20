<?php

/**
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 18.12.2024
 * @description This page is for the home view
 */

ob_start();
$cssPath = '/css/home-page.css';
?>

<header>
    <section class="container">
        <img src="images/logo.png" alt="logo" />
        <h1>Exercise<br>Looper</h1>
    </section>
</header>

<main class="container">
    <section class="row">
        <div class="column">
            <a class="button answering" href="/exercises/answering">Take an exercise</a>
        </div>

        <div class="column">
            <a class="button managing" href="/exercises/new">Create an exercise</a>
        </div>

        <div class="column">
            <a class="button results" href="/exercises">Manage an exercise</a>
        </div>
    </section>
</main>

<?php

$slot = ob_get_clean();
require_once VIEW_DIR . '/layouts/app-layout.php';
