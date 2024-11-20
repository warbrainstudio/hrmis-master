<?php $uniqueId = md5(date('YmdHis')) ?>

<script type="text/javascript">
  var _action_route = "<?= isset($action_route) ? $action_route : '' ?>";
  var _key = "<?= $key ?>";
  var _section = "employee";
  var _table_master = "table-employee";
  var _table_histori_absensi = "table-histori-absensi";
  var _table_histori_skspk = "table-histori-skspk"
  var _table_histori_kontrak = "table-histori-contract"
  var _table_histori_diklat = "table-histori-diklat"
  var _table_histori_pembinaan = "table-histori-pembinaan"
  var _table_histori_demosimutasi = "table-histori-demosimutasi"
  var _form = "form-employee";
  var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
  var _is_first_load = (_key != null && _key != "") ? true : false;

  $(document).ready(function() {
    initTable_historiAbsensi();
    initTable_historiSkSpk();
    initTable_historiKontrak();
    initTable_historiDiklat();
    initTable_historiPembinaan();
    initTable_historiDemosiMutasi();
    load_autoFilledText();

    // Initialize DataTables
    if ($(`#${_table_master}`)[0] && $.fn.DataTable.isDataTable(`#${_table_master}`) === false) {
      var table_master = $(`#${_table_master}`).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "<?php echo base_url('employee/ajax_get_all/') ?>",
          type: "get"
        },
        columns: [{
            data: null,
            render: function(data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          },
          {
            data: "nrp"
          },
          {
            data: "nama_lengkap"
          },
          {
            data: "nama_unit"
          },
          {
            data: "nama_jabatan"
          },
          {
            data: "status_active",
            render: function(data, type, row, meta) {
              var status = (data == 1) ? 'Aktif' : 'Tidak Aktif';
              var statusColor = (data == 1) ? 'success' : 'danger';
              return `<span class="badge badge-${statusColor}">${status}</span>`;
            }
          },
          {
            data: "created_date",
            render: function(data, type, row, meta) {
              if (data != null) {
                return moment(data).format('Y-MM-DD H:mm:ss');
              };
              return "-";
            }
          },
          {
            data: null,
            render: function(data, type, row, meta) {
              return `
                  <div class="action" style="display: flex; flex-direction: row;">
                      <a href="<?= base_url('employee/detail?ref=') ?>${row.id}" class="btn btn-sm btn-success x-load-partial" title="Rincian"><i class="zmdi zmdi-eye"></i></a>&nbsp;
                      <a href="<?= base_url('employee/input?ref=') ?>${row.id}" class="btn btn-sm btn-light x-load-partial" title="Ubah"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;
                      <a href="<?= base_url('employee/delete?ref=') ?>${row.id}" class="btn btn-sm btn-danger action-delete" title="Hapus"><i class="zmdi zmdi-delete"></i> Hapus</a>
                  </div>
              `;
            }
          }
        ],
        order: [
          [6, 'desc']
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
          targets: [0, 1, 2, 3, 4, 5, 6, 7]
        }, {
          className: 'tablet',
          targets: [0, 1, 2, 5]
        }, {
          className: 'mobile',
          targets: [0, 2]
        }, {
          responsivePriority: 1,
          targets: 0
        }, {
          responsivePriority: 1,
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
        oSearch: {
          sSearch: _p_search
        },
        initComplete: function(a, b) {
          $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
            '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
            '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
            '</div>'
          );
        },
        drawCallback: function() {
          handleCxFilter_setXlsx(_table_master);
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
          table_master.ajax.reload(null, false);
        };
      });
    };

    // Handle submit
    $(document).on("click", ".employee-action-save", function(e) {
      e.preventDefault();
      tinyMCE.triggerSave();

      swal({
        title: "Anda akan menyimpan data, lanjutkan?",
        text: "Sebelum disimpan, pastikan data sudah benar.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
        closeOnConfirm: false
      }).then((result) => {
        if (result.value) {
          var formData = new FormData($("#" + _form)[0]);

          $.ajax({
            type: "post",
            url: "<?php echo base_url('employee/ajax_save/') ?>" + _key,
            data: formData,
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
                  window.location.href = "<?= base_url('employee') ?>";
                });
              } else {
                notify(response.data, "danger");
              };
            }
          });
        };
      });
    });

    // Handle data delete
    $(`#${_table_master}`).on("click", "a.action-delete", function(e) {
      e.preventDefault();
      if (table_master) {
        var temp = table_master.row($(this).closest('tr')).data();
        swal({
          title: "Anda akan menghapus data, lanjutkan?",
          text: "Data pada SK / Perijinan dan Kontrak Kerja akan ikut terhapus. Setelah dihapus, data tidak dapat dikembalikan lagi!",
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
              url: "<?php echo base_url('employee/ajax_delete/') ?>" + temp.id,
              dataType: "json",
              success: function(response) {
                if (response.status) {
                  table_master.ajax.reload(null, false);
                  notify(response.data, "success");
                } else {
                  notify(response.data, "danger");
                };
              }
            });
          };
        });
      };
    });

    // Handle upload
    $(document.body).on("change", ".employee-foto", function() {
      readUploadInlineURL(this);
    });

    // Handle auto-filled-text
    $(document.body).on("change keyup", ".auto-filled-text", function() {
      var isCombo = $(this).hasClass("select2") ? true : false;
      var value = (isCombo === false) ? $(this).val() : ($(this).select2("data")[0]) ? $(this).select2("data")[0].text : "";
      var elName = $(this).attr("name");
      value = (value.trim() != "") ? value : "&nbsp;";

      if ($(`.auto-filled-text-${elName}`)) {
        $(`.auto-filled-text-${elName}`).html(value);
      };
    });

    // Handle unit change
    $("#" + _form + " .employee-unit_id").on("change", function() {
      load_refSubUnit($(this).val());
    });

    // Handle load: Keluarga
    $(document).on("click", "#nav-tab-family", function() {
      $("#iframe-employee_family").attr("src", "<?= base_url('employeefamily?ref=' . @$pegawai_id . '&action_route=') ?>" + _action_route);
    });

    // Handle download sertifikat diklat: Eksternal
    $(document).on("click", ".action-diklat-download_sertifikat-<?= $uniqueId ?>", function(e) {
      e.preventDefault();
      var url = $(this).attr("href");

      $("#modal-view-embed").css("z-index", 1600);
      $("#modal-view-embed").modal("show");
      $("#modal-view-embed-title").html("Sertifikat Diklat");
      $("#modal-view-embed-content").html("");

      if (PDFObject.supportsPDFs) {
        PDFObject.embed(url, "#modal-view-embed-content");
      } else {
        $("#modal-view-embed-content").html("Inline PDFs are not supported by this browser, try using the latest version of Chrome / Firefox.");
      };

      $("#modal-view-embed").on("hidden.bs.modal", function(e) {
        $("#modal-view-embed").css("z-index", "");
      });
    });

    // Handle generate sertifikat diklat: Internal
    $(document).on("click", ".action-diklat-generate_sertifikat-<?= $uniqueId ?>", function(e) {
      e.preventDefault();
      var url = $(this).attr("href");

      notify("Sedang melakukan generate, silahkan tunggu sampai loading selesai...", "info");
      $.ajax({
        type: "get",
        url: url,
        dataType: "json",
        success: function(response) {
          if (response.status) {
            notify(response.data, "success");
            setTimeout(function() {
              $("#modal-view-embed").css("z-index", 1600);
              $("#modal-view-embed").modal("show");
              $("#modal-view-embed-title").html("Sertifikat Diklat");
              $("#modal-view-embed-content").html("");

              if (PDFObject.supportsPDFs) {
                PDFObject.embed(response.file_to_stream, "#modal-view-embed-content");
              } else {
                $("#modal-view-embed-content").html("Inline PDFs are not supported by this browser, try using the latest version of Chrome / Firefox.");
              };
            }, 1000);
          } else {
            notify(response.data, "danger");
          };
        },
        beforeSend: function() {
          showBodyLoading();
        },
        error: function() {
          hideBodyLoading();
        },
        complete: function() {
          hideBodyLoading();
        }
      });
    });

    function initTable_historiAbsensi(){
      if ($(`#${_table_histori_absensi}`)[0] && $.fn.DataTable.isDataTable(`#${_table_histori_absensi}`) === false) {
        var table_histori_absensi = $("#" + _table_histori_absensi).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('kalenderabsen/ajax_get_all/') ?>",
            type: "get",
              data: {
                searchFilter: "<?= "AND absen_id='$absen_id'" ?>",
              },
          },
          columns: [{
              data: null,
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            {
              data: "tanggal_absen",
              render: function(data, type, row, meta) {
                return moment(data).format('DD-MM-YYYY');
              }
            },
            {
              data: "masuk",
              render: function(data, type, row, meta) {
                if (!data) {
                  return "-";
                } else {
                  let verifiedColor = 'success';
                  return `<span class="badge badge-${verifiedColor}">${moment(data).format('HH:mm:ss')}`;
                }
              }
            },
            {
              data: "verifikasi_masuk",
              render: function(data, type, row, meta) {
                let verifiedColor = 'secondary'; // Default color
                let verified = '';
                if (data === '1') {
                  verifiedColor = 'primary';
                  verified = 'Finger';
                } else if (data === '0') {
                  verifiedColor = 'secondary';
                  verified = 'Input';
                } else {
                  return "-"; // Directly return if neither
                }
                var verifikasi = `<span class="badge badge-${verifiedColor}" title="${row.lokasi_masuk}">${verified}`;
                var mesin = row.nama_mesin_masuk ? `${row.nama_mesin_masuk}</span>` : "-";
                return verifikasi+" / "+mesin;
              }
            },
            {
              data: "pulang",
              render: function(data, type, row, meta) {
                if (!data) {
                  return "-"; // Handles null and empty string
                } else {
                  let verifiedColor = 'success';
                  var DateMasuk = moment(row.masuk).format('DD-MM-YYYY');
                  var DatePulang = moment(data).format('DD-MM-YYYY');
                  if(row.masuk){
                    if(DateMasuk!=DatePulang){
                      let verifiedColor = 'dark';
                      return `<span class="badge badge-${verifiedColor}" title="hari pulang berbeda. ${DatePulang}">${moment(data).format('HH:mm:ss')}`;
                    }else{
                      return `<span class="badge badge-${verifiedColor}">${moment(data).format('HH:mm:ss')}`;
                    }
                  }else{
                    return `<span class="badge badge-${verifiedColor}">${moment(data).format('HH:mm:ss')}`;
                  }
                }
              }
            },
            {
              data: "verifikasi_pulang",
              render: function(data, type, row, meta) {
                let verifiedColor = 'secondary';
                let verified = '';
                if (data === '1') {
                  verifiedColor = 'primary';
                  verified = 'Finger';
                } else if (data === '0') {
                  verifiedColor = 'secondary';
                  verified = 'Input';
                } else {
                  return "-"; 
                }
                var verifikasi = `<span class="badge badge-${verifiedColor}" title="${row.lokasi_pulang}">${verified}`;
                var mesin = row.nama_mesin_pulang ? `${row.nama_mesin_pulang}</span>` : "-";
                return verifikasi+" / "+mesin;
              }
            },
            {
              data: "jam_kerja",
              render: function(data, type, row, meta) {
                if (data === null) {
                  const date = new Date(row.tanggal_absen);
                  date.setHours(0, 0, 0, 0);
                  const today = new Date();
                  today.setHours(0, 0, 0, 0);
                  if (date.getTime() === today.getTime()) {
                    if(!row.masuk && row.pulang){
                      return `<span class="badge badge-warning" title="Data ambigu"><i class="zmdi zmdi-info-outline"></i></span>`;
                    }else{
                      return `<span class="badge badge-info" title="belum pulang"><i class="zmdi zmdi-time"></i></span>`;
                    }
                  } else {
                    return `<span class="badge badge-danger" title="Data tidak lengkap"><i class="zmdi zmdi-alert-circle"></i></span>`;
                  }
                } else {
                  var jam = parseFloat(data);
                  if (!isNaN(jam) && jam >= 0) {
                    return jam.toFixed(1) + " Jam";
                  } else {
                    return `<span class="badge badge-warning" title="Data ambigu"><i class="zmdi zmdi-info-outline"> Notice</i></span>`;
                  }
                }
              }
            },
            {
              data: "jenis_shift"
            }
              /*,
            {
              data: null,
              className: "center",
              defaultContent: '<div class="action">' +
                '<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete-histori-absensi"><i class="zmdi zmdi-delete"></i> Hapus</a>' +
                '</div>'
            }*/
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
            targets: [0, 1, 2, 3, 4, 5, 6, 7]
          }, {
            className: 'tablet',
            targets: [0, 1, 2, 3]
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
            $("#" + _table_histori_absensi).DataTable().ajax.reload(null, false);
          };
        });
      };
    }

    // Init dataTable: Histori SK / Perijinan
    function initTable_historiSkSpk() {
      if ($(`#${_table_histori_skspk}`)[0] && $.fn.DataTable.isDataTable(`#${_table_histori_skspk}`) === false) {
        var table_historiSkSpk = $(`#${_table_histori_skspk}`).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('skspk/ajax_get_all/') ?>",
            type: "get",
            data: {
              filter: "<?= "AND pegawai_id='$pegawai_id'" ?>",
            },
          },
          columns: [{
              data: null,
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            {
              data: "kategori"
            },
            {
              data: "no_sk_spk",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return `<a href="<?= base_url('skspk/detail?ref=') ?>${row.id}" title="Rincian" modal-id="modal-view-skspk" class="x-load-modal-partial"><i class="zmdi zmdi-eye"></i> ${data}</a>`;
                };
                return "-";
              }
            },
            {
              data: "nama_sk_spk"
            },
            {
              data: "nama_jabatan"
            },
            {
              data: "tanggal_berlaku",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
            {
              data: "status_active",
              render: function(data, type, row, meta) {
                var status = (data == 1) ? 'Aktif' : 'Tidak Aktif';
                var statusColor = (data == 1) ? 'success' : 'danger';
                return `<span class="badge badge-${statusColor}">${status}</span>`;
              }
            },
          ],
          order: [
            [5, 'desc']
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
            targets: [0, 1, 3, 4]
          }, {
            className: 'mobile',
            targets: [0, 2]
          }, {
            responsivePriority: 1,
            targets: 0
          }, {
            responsivePriority: 1,
            targets: -1
          }],
          pageLength: 10,
          language: {
            searchPlaceholder: "Cari...",
            sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
          },
          sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
          initComplete: function(a, b) {
            $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
              '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
              '<span class="actions__item zmdi zmdi-refresh dt__action-reload-' + _table_histori_skspk + '" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
              '</div>'
            );
          },
          oSearch: {
            sSearch: _p_search
          }
        });

        $(".dataTables_filter input[type=search]").focus(function() {
          $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
        });

        $(".dataTables_filter input[type=search]").blur(function() {
          $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
        });

        $(document).on("click", `.dt__action-reload-${_table_histori_skspk}`, function(e) {
          e.preventDefault();
          table_historiSkSpk.ajax.reload(null, false);
        });
      };
    };

    // Init dataTable: Histori Kontrak
    function initTable_historiKontrak() {
      if ($(`#${_table_histori_kontrak}`)[0] && $.fn.DataTable.isDataTable(`#${_table_histori_kontrak}`) === false) {
        var table_historiKontrak = $(`#${_table_histori_kontrak}`).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('contract/ajax_get_all/') ?>",
            type: "get",
            data: {
              filter: "<?= "AND pegawai_id='$pegawai_id'" ?>",
            },
          },
          columns: [{
              data: null,
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            {
              data: "no_kontrak",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return `<a href="<?= base_url('contract/detail?ref=') ?>${row.id}" title="Rincian" modal-id="modal-view-contract" class="x-load-modal-partial"><i class="zmdi zmdi-eye"></i> ${data}</a>`;
                };
                return "-";
              }
            },
            {
              data: "nama_jenis_pegawai"
            },
            {
              data: "nama_status_kontrak"
            },
            {
              data: "nama_jabatan"
            },
            {
              data: "soc",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
            {
              data: "eoc",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
            {
              data: "status_active",
              render: function(data, type, row, meta) {
                var status = (data == 1) ? 'Aktif' : 'Tidak Aktif';
                var statusColor = (data == 1) ? 'success' : 'danger';
                return `<span class="badge badge-${statusColor}">${status}</span>`;
              }
            },
          ],
          order: [
            [5, 'desc']
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
            targets: [0, 1, 5, 6]
          }, {
            className: 'mobile',
            targets: [0, 1]
          }, {
            responsivePriority: 1,
            targets: 0
          }, {
            responsivePriority: 1,
            targets: -1
          }],
          pageLength: 10,
          language: {
            searchPlaceholder: "Cari...",
            sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
          },
          sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
          initComplete: function(a, b) {
            $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
              '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
              '<span class="actions__item zmdi zmdi-refresh dt__action-reload-' + _table_histori_kontrak + '" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
              '</div>'
            );
          },
          oSearch: {
            sSearch: _p_search
          }
        });

        $(".dataTables_filter input[type=search]").focus(function() {
          $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
        });

        $(".dataTables_filter input[type=search]").blur(function() {
          $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
        });

        $(document).on("click", `.dt__action-reload-${_table_histori_kontrak}`, function(e) {
          e.preventDefault();
          table_historiKontrak.ajax.reload(null, false);
        });
      };
    };

    // Init dataTable: Histori Diklat
    function initTable_historiDiklat() {
      if ($(`#${_table_histori_diklat}`)[0] && $.fn.DataTable.isDataTable(`#${_table_histori_diklat}`) === false) {
        var table_historiDiklat = $(`#${_table_histori_diklat}`).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('diklat/ajax_get_by_employee/') ?>",
            type: "get",
            data: {
              filter: "<?= "AND pegawai_id='$pegawai_id'" ?>",
            },
          },
          columns: [{
              data: null,
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            {
              data: "nama_pelatihan"
            },
            {
              data: "tempat_pelatihan"
            },
            {
              data: "tanggal_mulai",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
            {
              data: "tanggal_selesai",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
            {
              data: null,
              render: function(data, type, row, meta) {
                if (row.tipe === "Eksternal" && row.sertifikat_file_name != null && row.sertifikat_file_name != "") {
                  return `
                    <div class="action" style="display: flex; flex-direction: row;">
                      <a href="<?= base_url() ?>${row.sertifikat_file_name}" class="btn btn-sm btn-warning action-diklat-download_sertifikat-<?= $uniqueId ?>" title="Unduh Sertifikat">
                        <i class="zmdi zmdi-download"></i> Sertifikat
                      </a>
                    </div>
                  `;
                } else {
                  return `
                    <div class="action" style="display: flex; flex-direction: row;">
                      <a href="<?php echo base_url('diklat/ajax_generate') ?>?ref=${row.diklat_peserta_id}&output=pdf" class="btn btn-sm btn-warning action-diklat-generate_sertifikat-<?= $uniqueId ?>" title="Unduh Sertifikat">
                        <i class="zmdi zmdi-download"></i> Sertifikat
                      </a>
                    </div>
                  `;
                };
              }
            },
          ],
          order: [
            [3, 'desc']
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
            targets: [0, 1, 2, 3, 4, 5]
          }, {
            className: 'tablet',
            targets: [0, 1, 2, 5]
          }, {
            className: 'mobile',
            targets: [0, 1]
          }, {
            responsivePriority: 1,
            targets: 0
          }, {
            responsivePriority: 1,
            targets: -1
          }],
          pageLength: 10,
          language: {
            searchPlaceholder: "Cari...",
            sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
          },
          sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
          initComplete: function(a, b) {
            $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
              '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
              '<span class="actions__item zmdi zmdi-refresh dt__action-reload-' + _table_histori_diklat + '" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
              '</div>'
            );
          },
          oSearch: {
            sSearch: _p_search
          }
        });

        $(".dataTables_filter input[type=search]").focus(function() {
          $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
        });

        $(".dataTables_filter input[type=search]").blur(function() {
          $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
        });

        $(document).on("click", `.dt__action-reload-${_table_histori_diklat}`, function(e) {
          e.preventDefault();
          table_historiDiklat.ajax.reload(null, false);
        });
      };
    };

    // Init dataTable: Histori SK / Perijinan
    function initTable_historiPembinaan() {
      if ($(`#${_table_histori_pembinaan}`)[0] && $.fn.DataTable.isDataTable(`#${_table_histori_pembinaan}`) === false) {
        var table_historiPembinaan = $(`#${_table_histori_pembinaan}`).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('pembinaan/ajax_get_all/') ?>",
            type: "get",
            data: {
              filter: "<?= "AND pegawai_id='$pegawai_id'" ?>",
            },
          },
          columns: [{
              data: null,
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            {
              data: "kategori"
            },
            {
              data: "no_pembinaan",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return `<a href="<?= base_url('pembinaan/detail?ref=') ?>${row.id}" title="Rincian" modal-id="modal-view-pembinaan" class="x-load-modal-partial"><i class="zmdi zmdi-eye"></i> ${data}</a>`;
                };
                return "-";
              }
            },
            {
              data: "perihal"
            },
            {
              data: "start_date",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
            {
              data: "end_date",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
          ],
          order: [
            [4, 'desc']
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
            targets: [0, 1, 2, 3, 4, 5]
          }, {
            className: 'tablet',
            targets: [0, 1, 2, 4]
          }, {
            className: 'mobile',
            targets: [0, 2]
          }, {
            responsivePriority: 1,
            targets: 0
          }, {
            responsivePriority: 1,
            targets: -1
          }],
          pageLength: 10,
          language: {
            searchPlaceholder: "Cari...",
            sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
          },
          sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
          initComplete: function(a, b) {
            $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
              '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
              '<span class="actions__item zmdi zmdi-refresh dt__action-reload-' + _table_histori_pembinaan + '" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
              '</div>'
            );
          },
          oSearch: {
            sSearch: _p_search
          }
        });

        $(".dataTables_filter input[type=search]").focus(function() {
          $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
        });

        $(".dataTables_filter input[type=search]").blur(function() {
          $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
        });

        $(document).on("click", `.dt__action-reload-${_table_histori_pembinaan}`, function(e) {
          e.preventDefault();
          table_historiPembinaan.ajax.reload(null, false);
        });
      };
    };

    // Init dataTable: Histori Demosi / Mutasi
    function initTable_historiDemosiMutasi() {
      if ($(`#${_table_histori_demosimutasi}`)[0] && $.fn.DataTable.isDataTable(`#${_table_histori_demosimutasi}`) === false) {
        var table_historiDemosiMutasi = $(`#${_table_histori_demosimutasi}`).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('demosimutasi/ajax_get_all/') ?>",
            type: "get",
            data: {
              filter: "<?= "AND pegawai_id='$pegawai_id'" ?>",
            },
          },
          columns: [{
              data: null,
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            {
              data: "kategori",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return `<a href="<?= base_url('demosimutasi/detail?ref=') ?>${row.id}" title="Rincian" modal-id="modal-view-demosimutasi" class="x-load-modal-partial"><i class="zmdi zmdi-eye"></i> ${data}</a>`;
                };
                return "-";
              }
            },
            {
              data: "no_sk",
            },
            {
              data: "tanggal_sk",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
            {
              data: "no_skppj",
            },
            {
              data: "doj",
              render: function(data, type, row, meta) {
                if (data != null) {
                  return moment(data).format('Y-MM-DD');
                };
                return "-";
              }
            },
          ],
          order: [
            [5, 'desc']
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
            targets: [0, 1, 2, 3, 4, 5]
          }, {
            className: 'tablet',
            targets: [0, 1, 2, 4]
          }, {
            className: 'mobile',
            targets: [0, 2]
          }, {
            responsivePriority: 1,
            targets: 0
          }, {
            responsivePriority: 1,
            targets: -1
          }],
          pageLength: 10,
          language: {
            searchPlaceholder: "Cari...",
            sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
          },
          sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
          initComplete: function(a, b) {
            $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
              '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
              '<span class="actions__item zmdi zmdi-refresh dt__action-reload-' + _table_histori_demosimutasi + '" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
              '</div>'
            );
          },
          oSearch: {
            sSearch: _p_search
          }
        });

        $(".dataTables_filter input[type=search]").focus(function() {
          $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
        });

        $(".dataTables_filter input[type=search]").blur(function() {
          $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
        });

        $(document).on("click", `.dt__action-reload-${_table_histori_demosimutasi}`, function(e) {
          e.preventDefault();
          table_historiDemosiMutasi.ajax.reload(null, false);
        });
      };
    };

    // Handle auto-filled-text for first load
    function load_autoFilledText() {
      setTimeout(() => {
        var autoFilled = $(".auto-filled-text");
        if (autoFilled.length > 0 && _key != "") {
          $(".auto-filled-text").trigger("input").trigger("keyup").trigger("change");
        };
      }, 300);
    };

    // Handle fetch sub unit
    async function load_refSubUnit(unitId) {
      var cmpSubUnit = $(`#${_form} .employee-sub_unit_id`);
      var defaultValue = (_is_first_load === true) ? cmpSubUnit.val() : null;

      // Fetch new option
      await $.ajax({
        url: "<?= base_url('ref/ajax_get_list_sub_unit/') ?>",
        type: "get",
        data: {
          "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>",
          unit_id: unitId,
          default_value: defaultValue,
        },
        dataType: "json",
        success: function(response) {
          var value = (defaultValue != null) ? defaultValue : null;
          cmpSubUnit.html(response);
          cmpSubUnit.val(value).trigger("change");
        }
      });
    };
  });

  function handleCxFilter_submit() {
    var params = handleCxFilter_getParams();
    $("#" + _table_master).DataTable().ajax.url("<?php echo base_url('employee/ajax_get_all') ?>" + params);
    $("#" + _table_master).DataTable().clear().draw();
  };

  function handleCxFilter_xlsx() {
    var params = handleCxFilter_getParams();
    var url = "<?php echo base_url('employee/xlsx') ?>" + params;
    window.location.href = url;
  };
</script>