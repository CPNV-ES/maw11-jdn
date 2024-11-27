<header class="heading <?= isset($backgroundClass) ?>">
    <section class="container">
        <a href="/">
            <img class="header-img" src="/images/logo.png" alt="Logo" />
        </a>
        <?php if (isset($headTitle)) : ?>
            <div class="exercise-title">
                <?= $headTitle ?>
            </div>
        <?php endif; ?> </b></span>
    </section>
</header>