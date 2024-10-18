<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('themes/_public/') ?>img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="i<?php echo base_url('themes/_public/') ?>mg/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('themes/_public/') ?>img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('themes/_public/') ?>img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('themes/_public/') ?>img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo base_url('themes/_public/') ?>img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url('themes/_public/') ?>img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/animate.css/animate.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/jquery-scrollbar/jquery.scrollbar.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/fullcalendar/fullcalendar.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/flatpickr/flatpickr.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/nouislider/nouislider.min.css') ?>">
    <!-- <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/datatables/dataTables.bootstrap.min.css') ?>" /> -->
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/datatables/rowGroup.dataTables.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/datatables/responsive.bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/vendors/lightgallery/css/lightgallery.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('themes/_public/vendors/responsive-tabs/css/responsive-tabs.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('themes/_public/vendors/fancybox/jquery.fancybox.min.css') ?>" />

    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/css/app.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/_public/css/material-effect.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/_public/css/public.main.css') ?>">
</head>

<body data-ma-theme="<?php echo $app->theme_color ?>">
    <main class="main">
        <div class="page-loader">
            <div class="page-loader__spinner">
                <svg viewBox="25 25 50 50">
                    <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                </svg>
            </div>
        </div>

        <div class="body-spinner" style="position: fixed;">
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; background: rgba(0,0,0,.5); padding: 20px; border-radius: 8px;">
                <div class="lds-hourglass"></div>
                <div class="body-spinner-content text-center">
                    <!-- Inject content in here -->
                </div>
            </div>
        </div>

        <header class="header">
            <div class="navigation-trigger hidden-xl-up" data-ma-action="aside-open" data-ma-target=".sidebar">
                <div class="navigation-trigger__inner">
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                </div>
            </div>

            <div class="header__logo">
                <h1>
                    <a href="<?php echo base_url() ?>" class="ripple-effect">
                        <?php echo (!$app->is_mobile) ? $app->app_name  : 'IEMED' ?>
                    </a>
                </h1>
            </div>

            <form class="search" id="form-app-search" autocomplete="off">
                <!-- CSRF -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                <div class="search__inner">
                    <?php $currentKey = ($this->router->fetch_class() == 'search' && isset($_GET['q'])) ? $_GET['q'] : '' ?>
                    <input type="text" class="search__text app-search-keyword" placeholder="Cari..." value="<?php echo $currentKey ?>">
                    <i class="zmdi zmdi-search search__helper" data-ma-action="search-close"></i>
                </div>
            </form>

            <ul class="top-nav">
                <li class="hidden-xl-up"><a href="" class="ripple-effect" data-ma-action="search-open"><i class="zmdi zmdi-search"></i></a></li>

                <li class="dropdown top-nav__notifications">
                    <a href="" data-toggle="dropdown" id="app-notification-flag" class="ripple-effect">
                        <i class="zmdi zmdi-notifications"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu--block">
                        <div class="listview listview--hover">
                            <div class="listview__header">
                                Notifications
                            </div>
                            <div class="listview__scroll scrollbar-inner">
                                <div id="app-notification-data"></div>
                            </div>
                            <a href="<?php echo base_url('notification') ?>" class="view-more">View All</a>
                            <div class="p-1"></div>
                        </div>
                    </div>
                </li>

                <?php if (!$app->is_mobile) : ?>
                    <li class="dropdown hidden-xs-down">
                        <div class="widget-time">
                            <div class="time">
                                <span class="time__hours">00</span>
                                <span class="time__min">00</span>
                                <span class="time__sec">00</span>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if ($app->is_mobile) : ?>
                    <li class="dropdown">
                        <a href="" class="ripple-effect"><i class="zmdi zmdi-refresh"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </header>

        <?php include_once('sidebar.php') ?>