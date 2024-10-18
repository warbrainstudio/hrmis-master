<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title><?php echo $page_title ?></title>
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

  <!-- App styles -->
  <link rel="stylesheet" href="<?php echo base_url('themes/material_admin/css/app.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('themes/_public/css/material-effect.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('themes/_public/css/public.main.css') ?>">

  <style type="text/css">
    .login__block {
      margin: 1rem;
    }

    .login-flex {
      display: flex;
    }

    .login-left {
      flex: 0.7;
    }

    .login-left img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: left;
    }

    .login-right {
      flex: 1;
    }
  </style>
</head>

<body data-ma-theme="<?php echo $app->theme_color ?>">
  <!-- Loading Indicator -->
  <div class="spinner" style="position: fixed; flex-direction: column; justify-content: center; align-items: center;">
    <h3 style="color: var(--white);">Please wait</h3>
    <div class="lds-hourglass"></div>
  </div>
  <!-- END ## Loading Indicator -->

  <form id="form-login" autocomplete="off">
    <!-- CSRF -->
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

    <div class="login">
      <div class="login__block active" style="max-width: <?= (!$app->is_mobile) ? '800px' : '400px' ?>; padding: 0;">
        <div class="login-flex">
          <?php if (!$app->is_mobile) : ?>
            <div class="login-left">
              <img src="<?= base_url('themes/_public/img/office.png') ?>" />
            </div>
          <?php endif ?>
          <div class="login-right">
            <div class="login__block__header mt-0" style="background-color: #6fb7c6; border-radius: 0;">
              <img src="<?php echo base_url('themes/_public/img/logo/logo.png') ?>" />
              <span style="font-weight: 500;">HRMIS</span>
            </div>

            <div class="login__block__body">
              <div class="form-group form-group--float form-group--centered">
                <input type="text" name="username" class="form-control login-username" style="cursor: text;" readonly onfocus="this.removeAttribute('readonly');" />
                <label>Username</label>
                <i class="form-group__bar"></i>
              </div>

              <div class="form-group form-group--float form-group--centered">
                <input type="password" name="password" class="form-control login-password" style="cursor: text;" readonly onfocus="this.removeAttribute('readonly');" />
                <label>Password</label>
                <i class="form-group__bar"></i>
              </div>

              <button class="btn btn-primary page-action-login" style="background-color: #6fb7c6;">
                Masuk
                <i class="zmdi zmdi-long-arrow-right"></i>
              </button>
            </div>

            <div style="border-top: 1px solid #eceff1; padding: 20px 10px; color: #999;" class="bg-light mt-4">
              &copy; <?= isset($app->company_name) ? $app->company_name : '(not-set)' ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- Javascript -->
  <script src="<?php echo base_url('themes/material_admin/vendors/jquery/jquery.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/material_admin/vendors/popper.js/popper.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/material_admin/vendors/bootstrap/js/bootstrap.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/material_admin/vendors/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

  <!-- App functions and actions -->
  <script src="<?php echo base_url('themes/material_admin/js/app.min.js') ?>"></script>
  <script src="<?php echo base_url('themes/_public/js/material-effect.js') ?>"></script>

  <script type="text/javascript">
    function notify(nMessage, nType) {
      $.notify({
        message: nMessage
      }, {
        type: nType,
        z_index: 9999,
        delay: 2500,
        timer: 500,
        placement: {
          from: "top",
          align: "center"
        },
        template: '<div data-notify="container" class="alert alert-dismissible alert-{0} alert--notify" role="alert">' +
          '<span data-notify="message">{2}</span>' +
          '<button type="button" aria-hidden="true" data-notify="dismiss" class="alert--notify__close">Tutup</button>' +
          '</div>'
      });
    };

    // Handle CSRF
    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
      if (originalOptions.data instanceof FormData) {
        originalOptions.data.append("<?= $this->security->get_csrf_token_name(); ?>", "<?= $this->security->get_csrf_hash(); ?>");
      };
    });
  </script>

  <?php echo (isset($main_js)) ? $main_js : '' ?>
</body>

</html>