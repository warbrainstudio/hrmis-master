<script type="text/javascript">
  $(document).ready(function() {

    var _form = "form-pasal";

    // Handle ajax stop
    $(document).ajaxStop(function() {
      $(document).find(".body-loading").fadeOut("fast", function() {
        $(this).hide();
        document.body.style.overflow = "auto";
      });
    });

    // Handle data submit General
    $("#" + _form + " .page-action-save").on("click", function(e) {
      e.preventDefault();
      tinyMCE.triggerSave();

      var form = $("#" + _form)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('pasal/ajax_save/') ?>",
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
              window.location.href = "<?= base_url('pasal') ?>";
            });
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

  });
</script>