<script type="text/javascript">
  $(document).ready(function() {

    var _key = "";
    var _section = "user";
    var _table = "table-user";
    var _modal = "modal-form-user";
    var _form = "form-user";
    var _isLoadingUnit = true;

    // Initialize DataTables: Index
    if ($("#" + _table)[0]) {
      var table_user = $("#" + _table).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "<?php echo base_url('user/ajax_getall/') ?>",
          type: "get"
        },
        columns: [{
            data: null,
            render: function(data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          },
          {
            data: "email"
          },
          {
            data: "username"
          },
          {
            data: "nama_lengkap"
          },
          {
            data: "role"
          },
          {
            data: "created_date"
          },
          {
            data: null,
            className: "center",
            defaultContent: '<div class="action">' +
              '<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit" data-toggle="modal" data-target="#' + _modal + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
              '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
              '</div>'
          }
        ],
        autoWidth: !1,
        responsive: {
          details: {
            renderer: $.fn.dataTable.Responsive.renderer.tableAll({
              tableClass: "table dt-details"
            }),
            type: "inline",
            target: 'tr',
          }
        },
        columnDefs: [{
          className: 'desktop',
          targets: [0, 1, 2, 3, 4, 5, 6]
        }, {
          className: 'tablet',
          targets: [0, 2, 3, 4]
        }, {
          className: 'mobile',
          targets: [0, 3]
        }, {
          responsivePriority: 1,
          targets: 0
        }, {
          responsivePriority: 2,
          targets: -1
        }],
        pageLength: 15,
        language: {
          searchPlaceholder: "Cari...",
          sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
        },
        sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
        buttons: [{
          extend: "excelHtml5",
          title: "Export Result"
        }, {
          extend: "print",
          title: "Export Result"
        }],
        initComplete: function(a, b) {
          $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
            '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
            '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
            '</div>'
          );
        },
      });

      $(".dataTables_filter input[type=search]").focus(function() {
        $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
      });

      $(".dataTables_filter input[type=search]").blur(function() {
        $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
      });

      $("body").on("click", "[data-table-action]", function(a) {
        a.preventDefault();
        var b = $(this).data("table-action");
        if ("reload" === b) {
          $("#" + _table).DataTable().ajax.reload(null, false);
        };
      });
    };

    // Handle data add
    $("#" + _section).on("click", "button.user-action-add", function(e) {
      e.preventDefault();
      resetForm();

      // Initialize option role
      $("#" + _form + " .user-role option:not(:first)").prop("disabled", false);
    });

    // Handle data edit
    $("#" + _table).on("click", "a.action-edit", function(e) {
      e.preventDefault();
      var temp = table_user.row($(this).closest('tr')).data();

      // Set key for update params, important!
      _key = temp.id;

      $("#" + _form + " .user-role").val(temp.role).trigger("change");
      $("#" + _form + " .user-nama_lengkap").val(temp.nama_lengkap).trigger("input");
      $("#" + _form + " .user-email").val(temp.email).trigger("input");
      $("#" + _form + " .user-username").val(temp.username).trigger("input");
      $("#" + _form + " .user-password").val("").trigger("input");
      $("#" + _form + " .user-unit").val(temp.unit).trigger("change");

      getRefSubUnit(temp.unit).then(() => {
        $("#" + _form + " .user-sub_unit").val(temp.sub_unit).trigger("change");
      });

      $("#" + _form + " .user-role option:not(:first)").prop("disabled", false);
    });

    // Handle data submit
    $("#" + _modal + " .user-action-save").on("click", function(e) {
      e.preventDefault();
      $.ajax({
        type: "post",
        url: "<?php echo base_url('user/ajax_save/') ?>" + _key,
        data: $("#" + _form).serialize(),
        success: function(response) {
          var response = JSON.parse(response);
          if (response.status === true) {
            resetForm();
            $("#" + _modal).modal("hide");
            $("#" + _table).DataTable().ajax.reload(null, false);
            notify(response.data, "success");
          } else {
            notify(response.data, "danger");
          };
        }
      });
    });

    // Handle data delete
    $("#" + _table).on("click", "a.action-delete", function(e) {
      e.preventDefault();
      var temp = table_user.row($(this).closest('tr')).data();

      swal({
        title: "Anda akan menghapus data, lanjutkan?",
        text: "Setelah dihapus, data tidak dapat dikembalikan lagi!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
        closeOnConfirm: false
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "delete",
            url: "<?php echo base_url('user/ajax_delete/') ?>" + temp.id,
            dataType: "json",
            success: function(response) {
              if (response.status) {
                $("#" + _table).DataTable().ajax.reload(null, false);
                notify(response.data, "success");
              } else {
                notify(response.data, "danger");
              };
            }
          });
        };
      });
    });

    // Handle role change
    $("#" + _form + " .user-role").on("change", function() {
      var val = $(this).val();
    });

    // Handle unit change
    $("#" + _form + " .user-unit").on("change", function() {
      var unit = $(this).val();
      getRefSubUnit(unit);
    });

    // Handle fetch sub unit
    async function getRefSubUnit(unit) {
      _isLoadingUnit = true;
      await $.ajax({
        type: "POST",
        url: "<?= base_url('user/ajax_get_sub_unit/') ?>",
        data: {
          "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>",
          unit: unit
        },
        dataType: "json",
        success: function(response) {
          var subUnit = $(`#${_form} .user-sub_unit`);
          subUnit.html(response);
        }
      });
      _isLoadingUnit = false;
    };

    // Handle form reset
    resetForm = () => {
      _key = "";
      $("#" + _form).trigger("reset");
      $("#" + _form + " .user-role").trigger("change");
      $("#" + _form + " .user-unit").val($(".user-unit option:eq(0)").val()).trigger("change");
      $("#" + _form + " .user-sub_unit").val($(".user-sub_unit option:eq(0)").val()).trigger("change");
    };

  });
</script>