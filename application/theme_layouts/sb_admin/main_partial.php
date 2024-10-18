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

<body class="bg-white">
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

    <div id="app-main-content-partial">
        {content}
    </div>

    <!-- Public -->
    <script type="text/javascript">
        var _baseUrl = "<?= base_url() ?>";
    </script>

    <!-- Vendors -->
    <script src="<?php echo base_url('themes/sb_admin/vendors/jquery/jquery.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/popper.js/popper.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!-- <script src="<?php echo base_url('themes/sb_admin/vendors/bootstrap-5/js/bootstrap.bundle.js') ?>"></script> -->
    <script src="<?php echo base_url('themes/sb_admin/vendors/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/jquery-scrollLock/jquery-scrollLock.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/flot/jquery.flot.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/flot/jquery.flot.resize.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/flot.curvedlines/curvedLines.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/jqvmap/jquery.vmap.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/jqvmap/maps/jquery.vmap.world.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/easy-pie-chart/jquery.easypiechart.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/salvattore/salvattore.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/sparkline/jquery.sparkline.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/moment/moment.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/fullcalendar/fullcalendar.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/tinymce/tinymce.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/autosize/autosize.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/jquery-text-counter/textcounter.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/flatpickr/flatpickr.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/jquery-mask-plugin/jquery.mask.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/select2/js/select2.full.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/nouislider/nouislider.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/Chart.js/Chart.min.js') ?>"></script>

    <!-- Vendors: Data tables -->
    <script src="<?php echo base_url('themes/sb_admin/vendors/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/datatables/dataTables.rowGroup.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/datatables-buttons/dataTables.buttons.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/datatables-buttons/buttons.print.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/jszip/jszip.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/datatables-buttons/buttons.html5.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/datatables/dataTables.rowReorder.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/datatables/dataTables.responsive.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/datatables/dataTables.fixedColumns.min.js') ?>"></script>
    <script src="<?php echo base_url('themes/sb_admin/vendors/lightgallery/js/lightgallery-all.min.js') ?>"></script>

    <!-- PrintThis -->
    <script src="<?php echo base_url('themes/_public/js/printThis.js') ?>"></script>

    <!-- html2canvas -->
    <script src="<?php echo base_url('themes/_public/js/html2canvas.js') ?>"></script>

    <!-- Responsive Tabs -->
    <script src="<?php echo base_url('themes/_public/vendors/responsive-tabs/js/responsive-tabs.js') ?>"></script>

    <!-- Fanyxboc -->
    <script src="<?php echo base_url('themes/_public/vendors/fancybox/jquery.fancybox.min.js') ?>"></script>

    <!-- FileDownload -->
    <script src="<?php echo base_url('themes/_public/js/fileDownload.js') ?>"></script>

    <!-- MD5 -->
    <script src="<?php echo base_url('themes/_public/js/jquery.md5.js') ?>"></script>

    <!-- JQuery InputMask -->
    <script src="<?php echo base_url('themes/_public/vendors/inputmask/jquery.inputmask.bundle.min.js') ?>"></script>

    <!-- CDN -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script> -->

    <!-- App functions and actions -->
    <script src="<?php echo base_url('themes/sb_admin/') ?>js/scripts.js"></script>
    <script src="<?php echo base_url('themes/_public/js/public.main.js') ?>"></script>
    <script src="<?php echo base_url('themes/_public/js/material-effect.js') ?>"></script>

    <?php echo (isset($main_js)) ?  $main_js : '' ?>

    <script type="text/javascript">
        function getTimeAgo(time) {
            return moment(time).fromNow();
        };

        // Handle CSRF serialize
        var csfrData = {};
        csfrData["<?= $this->security->get_csrf_token_name() ?>"] = "<?= $this->security->get_csrf_hash() ?>";
        $.ajaxSetup({
            data: csfrData
        });

        // Handle CSRF form-data
        $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
            if (originalOptions.data instanceof FormData) {
                originalOptions.data.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
            };
        });

        // Load partial view with ajax
        $(document).on("click", ".x-load-partial2", function(e) {
            e.preventDefault();
            var partialUrl = $(this).attr("href");
            var iframe = $(this).attr("iframe-id");
            iframe = (iframe != null && iframe != "") ? parent.document.getElementById(iframe) : null;

            $.ajax({
                url: partialUrl,
                type: "get",
                dataType: "html",
                beforeSend: function() {
                    showBodyLoading();
                },
                success: function(response) {
                    $("#app-main-content-partial").empty();
                    $("#app-main-content-partial").html(response);
                    hideBodyLoading();
                },
                error: function() {
                    hideBodyLoading();
                },
                complete: function() {
                    hideBodyLoading();

                    if (iframe != null) {
                        resizeIframe(iframe);
                    };
                },
            });
        });

        // Load modal partial view with ajax
        $(document).on("click", ".x-load-modal-partial2", function(e) {
            var partialUrl = $(this).attr("href");
            var modalId = $(this).attr("modal-id");

            parent.xLoadModalPartial(partialUrl, modalId);
            return false;
        });
    </script>
</body>

</html>