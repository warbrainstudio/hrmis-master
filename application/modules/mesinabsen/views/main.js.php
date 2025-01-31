<script type="text/javascript">
  $(document).ready(function() {

    var _key = "";
    var _section = "mesin";
    var _table = "table-mesin";
    var _modal = "modal-form-mesin";
    var _form = "form-mesin";
    var ipv4_address = $("#" + _modal + " ." + _section + "-ipadress");
    
    ipv4_address.inputmask({
        alias: "ip",
        greedy: false
    });

    /*Swal.fire({
      title: 'Checking Fingerprint Machine...',
      text: 'Sedang mengecek mesin absensi. mohon tunggu sebentar',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
          Swal.showLoading();
      }
    });

    $.ajax({
      type: "get",
      url: "<//?php echo base_url('mesinabsen/ajax_check_all/') ?>",
      success: function(response) {
        Swal.close();
      },
      error: function() {
        Swal.close(); 
      }
    });*/

    $.ajax({
      type: "get",
      url: "<?php echo base_url('mesinabsen/ajax_check_all/') ?>"
    });


    // Initialize DataTables: Index
    if ($("#" + _table)[0]) {
      var table_mesin = $("#" + _table).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "<?php echo base_url('mesinabsen/ajax_get_all/') ?>",
          type: "get"
        },
        columns: [{
            data: null,
            render: function(data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          },
          {
            data: "nama_mesin",
          },
          {
            data: "ipadress",
            render: function(data, type, row) {
              var statusColor = (row.status_ip === 'Connect') ? 'info' : 'danger';
              var status = (row.status_ip === 'Connect') ? 
                `<span class="badge badge-${statusColor}"><i class="zmdi zmdi-check-circle"></i> ${data}</span>` : 
                `<span class="badge badge-${statusColor}" title="IP Address tidak terhubung atau sedang offline"><i class="zmdi zmdi-close-circle"></i> ${data}</span>`;
              return status;
            }
          },
          {
            data: "commkey",
            render: function(data, type, row) {
              var statusColor = (row.status_commkey === 'Connect') ? 'info' : 'danger';
              var status = (row.status_commkey === 'Connect') ? 
                `<span class="badge badge-${statusColor}"><i class="zmdi zmdi-check-circle"></i> ${data}</span>` : 
                `<span class="badge badge-${statusColor}" title="Comm Key salah. Cek mesin absen"><i class="zmdi zmdi-close-circle"></i> ${data}</span>`;
              return status;
            }
          },
          {
            data: "lokasi"
          },
          {
            data: "status",
              render: function(data, type, row) {
                var statusColor = (data === 'Connect') ? 'info' : 'danger';
                var status = (data === 'Connect') ? 
                  `<span class="badge badge-${statusColor}"><i class="zmdi zmdi-check-circle"></i> ${data}</span>` : 
                  `<span class="badge badge-${statusColor}" title="Mesin tidak bisa terhubung. Ubah data mesin"><i class="zmdi zmdi-close-circle"></i> ${data}</span>`;
                return status;
              }
          },
          {
            data: null,
            className: "center",
            defaultContent: '<div class="action">' +
              '<a href="javascript:;" class="btn btn-sm btn-warning btn-table-action action-check-connect"><i class="zmdi zmdi-refresh"></i> Check</a>' +
              '<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit" data-toggle="modal" data-target="#' + _modal + '"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;' +
              '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
              '</div>'
          }
        ],
        order: [[1, 'asc']],
        autoWidth: !1,
        responsive: {
          details: {
            renderer: function(api, rowIdx, columns) {
              var hideColumn = [];
              var data = $.map(columns, function(col, i) {
                return ($.inArray(col.columnIndex, hideColumn)) ?
                  '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                  '<td class="dt-details-td">' + col.title + ':' + '</td> ' +
                  '<td class="dt-details-td">' + col.data + '</td>' +
                  '</tr>' :
                  '';
              }).join('');

              return data ? $('<table/>').append(data) : false;
            },
            type: "inline",
            target: 'tr',
          }
        },
        columnDefs: [{
          className: 'desktop',
          targets: [0, 1, 2, 3, 4, 5, 6]
        }, {
          className: 'tablet',
          targets: [0, 1, 2, 5]
        }, {
          className: 'mobile',
          targets: [0, 1]
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
            '<div class="dataTables_buttons hidden-sm-down actions">' +
            '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" />' +
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
    $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
      e.preventDefault();
      resetForm();
    });

    $("#" + _table).on("click", "a.action-check-connect", function(e) {
        e.preventDefault();
        var temp = table_mesin.row($(this).closest('tr')).data();
        var ip = temp.ipadress;

        Swal.fire({
            title: 'Checking Fingerprint Machine...',
            text: 'Mohon tunggu sebentar saat proses pengecekan mesin absen',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "get",
            url: "<?php echo base_url('mesinabsen/ajax_check/') ?>"+ip,
            success: function(response) {
                var response = JSON.parse(response);
                Swal.close();

                if (response.status === true) {
                    notify(response.data, "success");
                    $("#" + _table).DataTable().ajax.reload(null, false);
                } else {
                    notify(response.data, "danger");
                    $("#" + _table).DataTable().ajax.reload(null, false);
                }
            },
            error: function() {
                Swal.close(); 
                notify('An error occurred while checking the connection.', 'danger');
            }
        });
    });

    // Handle data edit
    $("#" + _table).on("click", "a.action-edit", function(e) {
      e.preventDefault();
      resetForm();
      var temp = table_mesin.row($(this).closest('tr')).data();

      // Set key for update params, important!
      _key = temp.id;

      $.each(temp, function(key, item) {
        $(`#${_form} .${_section}-${key}`).val(item).trigger("input").trigger("change");
      });
    });

    // Handle data submit
    $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
      e.preventDefault();
      $.ajax({
        type: "post",
        url: "<?php echo base_url('mesinabsen/ajax_save/') ?>" + _key,
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
      var temp = table_mesin.row($(this).closest('tr')).data();

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
            url: "<?php echo base_url('mesinabsen/ajax_delete/') ?>" + temp.id,
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

    // Handle form reset
    resetForm = () => {
      _key = "";
      $(`#${_form}`).trigger("reset");
    };
  });
</script>