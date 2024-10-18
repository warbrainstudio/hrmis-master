<script type="text/javascript">
  $(document).ready(function() {

    var _form = "form-suratnomor";

    // Handle ajax start
    $(document).ajaxStart(function() {
      $(document).find(".body-loading").fadeIn("fast", function() {
        $(this).show();
        document.body.style.overflow = "hidden";
      });
    });

    // Handle ajax stop
    $(document).ajaxStop(function() {
      $(document).find(".body-loading").fadeOut("fast", function() {
        $(this).hide();
        document.body.style.overflow = "auto";
      });
    });

    // Handle data submit Application
    $("#" + _form + " .page-action-save").on("click", function(e) {
      e.preventDefault();

      var form = $("#" + _form)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('setting/settingnomorsurat/ajax_save/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            notify(response.data, "success");
            // window.location.href = "<?php echo base_url('setting/nomorsurat') ?>";
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

  });
</script>