<script type="text/javascript">
  $(document).ready(function() {

    var _form_general = "form-setting-general";
    var _form_application = "form-setting-application";
    var _form_smtp = "form-setting-smtp";
    var _form_account = "form-setting-account";
    var _form_dashboard = "form-setting-dashboard_image";

    // Handle ajax stop
    $(document).ajaxStop(function() {
      $(document).find(".body-loading").fadeOut("fast", function() {
        $(this).hide();
        document.body.style.overflow = "auto";
      });
    });

    // Call dashboard_preview
    dashboard_preview();

    // Handle data submit General
    $("#" + _form_general + " .page-action-save-general").on("click", function(e) {
      e.preventDefault();
      tinyMCE.triggerSave();

      var form = $("#" + _form_general)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('setting/ajax_save_general/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            swal({
              title: "Success",
              text: response.data,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: '#39bbb0',
              confirmButtonText: "OK",
              closeOnConfirm: false
            }).then((result) => {
              window.location.href = "<?= base_url('setting/general') ?>";
            });
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

    // Handle data submit Application
    $("#" + _form_application + " .page-action-save-application").on("click", function(e) {
      e.preventDefault();
      tinyMCE.triggerSave();

      var form = $("#" + _form_application)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('setting/ajax_save_application/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            notify(response.data, "success");
            window.location.href = "<?php echo base_url('setting/application') ?>";
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

    // Handle test SMTP
    $("#" + _form_smtp + " .page-action-test-smtp").on("click", function(e) {
      e.preventDefault();

      $.ajax({
        type: "get",
        url: "<?php echo base_url('setting/ajax_test_smtp/') ?>",
        dataType: "json",
        success: function(response) {
          if (response.status === true) {
            notify(response.data, "success");
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

    // Handle data submit SMTP
    $("#" + _form_smtp + " .page-action-save-smtp").on("click", function(e) {
      e.preventDefault();
      tinyMCE.triggerSave();

      var form = $("#" + _form_smtp)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('setting/ajax_save_smtp/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            notify(response.data, "success");
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

    // Handle data submit Account
    $("#" + _form_account + " .page-action-save-account").on("click", function(e) {
      e.preventDefault();
      tinyMCE.triggerSave();

      var form = $("#" + _form_account)[0];
      var data = new FormData(form);

      // Show loading indicator
      $(document).find(".body-loading").fadeIn("fast", function() {
        $(this).show();
        document.body.style.overflow = "hidden";
      });

      $.ajax({
        type: "post",
        url: "<?php echo base_url('setting/ajax_save_account/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            swal({
              title: "Success",
              text: response.data,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: '#39bbb0',
              confirmButtonText: "OK",
              closeOnConfirm: false
            }).then((result) => {
              window.location.href = "";
            });
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

    // Handle data submit Dashboard Image
    $("#" + _form_dashboard + " .page-action-save-dashboard_image").on("click", function(e) {
      e.preventDefault();
      var form = $("#" + _form_dashboard)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('setting/ajax_save_dashboard/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            notify(response.data, "success");
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

    // Handle Dashboard Image changed
    $("#" + _form_dashboard + " .setting-dashboard_image").on("keyup", function() {
      dashboard_preview();
    });
    $("#" + _form_dashboard + " .setting-dashboard_image").on("change", function() {
      dashboard_preview();
    });

    // Handle Dashboard Image upload
    $(".setting-dashboard_image_source").change(function() {
      dashboard_get_preview(this);
    });

    // Handle Dashboard Image preview
    function dashboard_preview() {
      var _dashboard_image_width = $(".setting-dashboard_image_width").val();
      var _dashboard_image_max_height = $(".setting-dashboard_image_max_height").val();
      var _dashboard_image_object_fit = $(".setting-dashboard_image_object_fit").val();
      var _dashboard_image_object_position = $(".setting-dashboard_image_object_position").val();
      var _dashboard_image_box_shadow = $(".setting-dashboard_image_box_shadow").val();
      _dashboard_image_box_shadow = (_dashboard_image_box_shadow == "1") ? "0 1px 2px rgba(0, 0, 0, 0.1)" : "none";

      $(".setting-preview-dashboard_image").css("width", _dashboard_image_width + "%");
      $(".setting-preview-dashboard_image").css("max-height", _dashboard_image_max_height + "px");
      $(".setting-preview-dashboard_image").css("object-fit", _dashboard_image_object_fit);
      $(".setting-preview-dashboard_image").css("object-position", _dashboard_image_object_position);
      $(".setting-preview-dashboard_image").css("box-shadow", _dashboard_image_box_shadow);
    };

    // Handle Dashboard Image choose file preview
    function dashboard_get_preview(input) {
      $('.setting-preview-dashboard_image').html("");

      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          if (e.target.result != "") {
            $('.setting-preview-dashboard_image').attr("src", e.target.result);
          };
        };
        reader.readAsDataURL(input.files[0]);
      };
    };

    // Handle upload
    $(document.body).on("change", ".user-profile_photo", function() {
      readUploadInlineURL(this);
    });

  });
</script>