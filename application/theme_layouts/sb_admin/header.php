<?php
$profile_photo = $this->session->userdata('user')['profile_photo'];
$profile_photo_temp = (!is_null($profile_photo) && !empty($profile_photo)) ? $profile_photo : 'themes/_public/img/avatar/male-1.png';
$currentKey = ($this->router->fetch_class() == 'search' && isset($_GET['q'])) ? $_GET['q'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Informasi Gudang Umum, <?= isset($app->company_name) ? $app->company_name : '(not-set)' ?>" />
    <meta name="developer" content="sanimalikibrahim@gmail.com" />

    <title>{title}</title>

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
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/animate.css/animate.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/jquery-scrollbar/jquery.scrollbar.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/fullcalendar/fullcalendar.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/flatpickr/flatpickr.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/nouislider/nouislider.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/datatables/rowGroup.dataTables.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/datatables/responsive.bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/lightgallery/css/lightgallery.min.css') ?>" />

    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo base_url('themes/_public/css/public.main.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/css/app.min.inject.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/css/styles.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('themes/_public/vendors/responsive-tabs/css/responsive-tabs.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('themes/_public/css/material-effect.css') ?>">
</head>

<body class="nav-fixed">
    <nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
        <!-- Sidenav Toggle Button-->
        <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
        <!-- Navbar Brand-->
        <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="<?= base_url() ?>">
            <?php echo ($app->is_mobile) ? $app->app_name  : $app->app_name ?>
        </a>
        <!-- Navbar Search Input-->
        <!-- * * Note: * * Visible only on and above the lg breakpoint-->
        <?php if (!$app->is_mobile) : ?>
            <div class="row">
                <div class="col-auto">
                    <form class="form-inline me-auto d-none d-lg-block me-3" id="form-app-search" autocomplete="off">
                        <div class="input-group input-group-joined input-group-solid">
                            <input class="form-control pe-0 app-search-keyword" type="search" placeholder="Cari" aria-label="Cari" value="<?php echo $currentKey ?>" />
                            <div class="input-group-text"><i data-feather="search"></i></div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <!-- Navbar Items-->
        <ul class="navbar-nav align-items-center ms-auto">
            <!-- Navbar Search Dropdown-->
            <!-- * * Note: * * Visible only below the lg breakpoint-->
            <li class="nav-item dropdown no-caret me-3 d-lg-none">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="searchDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="search"></i></a>
                <!-- Dropdown - Search-->
                <div class="dropdown-menu dropdown-menu-end p-3 shadow animated--fade-in-up" aria-labelledby="searchDropdown">
                    <form class="form-inline me-auto w-100" id="form-app-search" autocomplete="off">
                        <div class="input-group input-group-joined input-group-solid">
                            <input class="form-control pe-0 app-search-keyword" type="text" placeholder="Search for..." aria-label="Cari" aria-describedby="basic-addon2" value="<?php echo $currentKey ?>" />
                            <div class="input-group-text"><i data-feather="search"></i></div>
                        </div>
                    </form>
                </div>
            </li>
            <!-- Alerts Dropdown-->
            <li class="nav-item dropdown no-caret d-sm-block me-3 dropdown-notifications">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="app-notification-flag" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="app-notification-flag-icon" data-feather="bell"></i>
                    <span class="app-notification-flag-count badge badge-danger" style="display: none;">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="app-notification-flag">
                    <h6 class="dropdown-header dropdown-notifications-header">
                        <i class="me-2" data-feather="bell"></i>
                        Notifications
                    </h6>
                    <div id="app-notification-data"></div>
                    <a class="dropdown-item dropdown-notifications-footer" href="<?php echo base_url('notification') ?>">View All</a>
                </div>
            </li>
            <!-- User Dropdown-->
            <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="img-fluid" src="<?php echo base_url($profile_photo_temp) ?>" />
                </a>
                <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                    <h6 class="dropdown-header d-flex align-items-center">
                        <img class="dropdown-user-img" src="<?php echo base_url($profile_photo_temp) ?>" />
                        <div class="dropdown-user-details">
                            <div class="dropdown-user-details-name">
                                <?php echo $this->session->userdata('user')['nama_lengkap'] ?> <br>
                                <small><?php echo $this->session->userdata('user')['role'] ?></small>
                            </div>
                        </div>
                    </h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= base_url('setting/account') ?>">
                        <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                        Account
                    </a>
                    <a class="dropdown-item" href="<?= base_url('logout') ?>">
                        <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <!-- Loader -->
        <div class="body-loading">
            <div class="body-loading-content">
                <div class="card">
                    <div class="card-body">
                        <i class="zmdi zmdi-spinner zmdi-hc-spin"></i>
                        Memproses data...
                        <div class="mb-2"></div>
                        <span style="color: #9c9c9c; font-size: 0.8rem;">Jangan tutup aktivitas tab ini!</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ## Loader -->