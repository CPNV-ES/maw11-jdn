<!--
Authors: Nathan Chauveau, David Dieperink, Julien Schneider
Version: 19.12.2024
Description: This section represents the header of the page. It includes a logo image that links to the homepage.
If the variable $headTitle is set, it will display a title inside the <div> element with the class "exercise-title".
The header also supports a dynamic background class based on the value of $backgroundClass.
-->

<header class="heading <?php if (isset($backgroundClass)) echo $backgroundClass ?>">
    <section class="container">
        <a href="/">
            <img class="header-img" src="/images/logo.png" alt="Logo" />
        </a>
        <?php if (isset($headTitle)) : ?>
            <div class="exercise-title">
                <?= $headTitle ?>
            </div>
        <?php endif; ?>
    </section>
</header>