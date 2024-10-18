<?php include_once('header.php') ?>
<?php include_once('sidebar.php') ?>

<div id="layoutSidenav_content">
    <main>
        <header class="page-header page-header-dark bg-img-cover pb-10" style="background-image: url('<?= base_url('themes/_public/img/header-bg.png') ?>')">
            <div class="container-fluid px-4">
                <div class="page-header-content <?= ($app->is_mobile) ? 'pt-0' : 'pt-4' ?>">
                    <?php if (!$app->is_mobile) : ?>
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mt-4">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon avatar">
                                        <img class="avatar-img img-fluid" src="<?= base_url('themes/_public/img/logo/logo.png') ?>" alt="Logo KAH">
                                    </div>
                                    <?= isset($app->company_name) ? $app->company_name : '(not-set)' ?>
                                </h1>
                                <div class="page-header-subtitle" style="color: rgba(255, 255, 255, 0.7);">
                                    <?= isset($app->company_slogan) ? $app->company_slogan : '' ?>
                                    <?= isset($this->session->userdata('user')['sub_unit']) ? ' &#8728; '. $this->session->userdata('user')['sub_unit'] : '' ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div id="app-main-content" class="container-fluid px-4 mt-n10">
            {content}
        </div>
    </main>
    <?php include_once('footerCredit.php') ?>
</div>
</div>

<?php include_once('footer.php') ?>