<!-- Vendor styles -->
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/animate.css/animate.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/jquery-scrollbar/jquery.scrollbar.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/fullcalendar/fullcalendar.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/flatpickr/flatpickr.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/nouislider/nouislider.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/vendors/lightgallery/css/lightgallery.min.css') ?>" />

<!-- App styles -->
<link rel="stylesheet" href="<?php echo base_url('themes/_public/css/public.main.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/css/app.min.inject.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/sb_admin/css/styles.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('themes/_public/vendors/responsive-tabs/css/responsive-tabs.css') ?>" />
<link rel="stylesheet" href="<?php echo base_url('themes/_public/css/material-effect.css') ?>">

{content}

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
<script src="<?php echo base_url('themes/sb_admin/vendors/jszip/jszip.min.js') ?>"></script>
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

<!-- App functions and actions -->
<script src="<?php echo base_url('themes/sb_admin/') ?>js/scripts.js"></script>
<script src="<?php echo base_url('themes/_public/js/public.main.partial.js') ?>"></script>
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
</script>