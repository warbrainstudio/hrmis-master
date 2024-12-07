<script type="text/javascript">
  var _key = "";
  var _section = "absen";
  var _table = "table-absen";
  var _modal = "modal-form-absen";
  var _form = "form-absen";
  var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
  var _is_load_partial = "<?= (isset($is_load_partial)) ? $is_load_partial : '0' ?>";
  var _is_first_load = (_key != null && _key != "") ? true : false;

  $(document).ready(function() {

    if (_is_load_partial === '0' && $("#" + _table)[0]) {
      if ($.fn.DataTable.isDataTable(`#${_table}`) === false) {
        var table_absen = $("#" + _table).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('absen/ajax_get_all/') ?>",
            type: "get",
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
                var tanggal = moment(data).format('DD-MM-YYYY');
                //var month = moment(data).format('MM');
                //var day = moment(data).format('DD');
                //var getMonth = getMonthNameByNum(month);
                //return day+" "+getMonth;
                return tanggal;
              }
            },
            {
              data: "nama",
              render: function(data, type, row, meta) {
                if (data.includes('ID Absen : ')) {
                  var link = data;
                }else{
                  var link = `<a href="<?= base_url('employee/detail?ref=') ?>${row.id_pegawai}" class="x-load-partial">${row.nama}</a>&nbsp;`;
                }
                return link;
              }
            },
            {
              data: "nama_unit",
              render: function(data, type, row, meta) {
                if(!data){
                  return "-";
                }else{
                  //return `<a title="${data}">(Detail)</a>`;
                  return data;
                }
              }
            },
            {
              data: "nama_sub_unit",
              render: function(data, type, row, meta) {
                if(!data){
                  return "-";
                }else{
                  //return `<a title="${data}">(Detail)</a>`;
                  return data;
                }
              }
            },
            {
              data: "masuk",
              render: function(data, type, row, meta) {
                if (!data) {
                  return "-";
                } else {
                  let verifiedColor = 'success';
                  var DateMasuk = moment(data).format('DD-MM-YYYY');
                  var DatePulang = moment(row.pulang).format('DD-MM-YYYY');
                  if(row.pulang){
                    if(DateMasuk!=DatePulang){
                      let verifiedColor = 'dark';
                      return `<span class="badge badge-${verifiedColor}" title="hari masuk berbeda. ${DateMasuk}">${moment(data).format('HH:mm:ss')}`;
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
              data: "verifikasi_masuk",
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
                var verifikasi = `<span class="badge badge-${verifiedColor}" title="${row.lokasi_masuk}">${verified}`;
                var mesin = row.nama_mesin_masuk ? `${row.nama_mesin_masuk}</span>` : "-";
                return verifikasi+" / "+mesin;
              }
            },
            {
              data: "pulang",
              render: function(data, type, row, meta) {
                if (!data) {
                  return "-";
                }  else {
                  let verifiedColor = 'success';
                  return `<span class="badge badge-${verifiedColor}">${moment(data).format('HH:mm:ss')}`;
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
                  var span = `<span class="badge badge-danger" title="Data tidak lengkap"><i class="zmdi zmdi-alert-circle"> Notice</i></span>`;
                  //return `<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit" title="Ubah data?" data-toggle="modal" data-target="#${_modal}">${span}</a>&nbsp;`;
                  return span;
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
              data: "jadwal_nama",
              render: function(data, type, row, meta) {
                var masuk = moment(row.masuk).format('HH:mm:ss');
                var pulang = moment(row.pulang).format('HH:mm:ss');
                var jam_masuk = row.jadwal_masuk;
                var jam_pulang = row.jadwal_pulang;
                if(row.masuk){
                    if(jam_masuk && jam_pulang){
                      return `<a title="${jam_masuk}-${jam_pulang}">${data}</a>`;
                    }else{
                      return "-";
                    }
                }else{
                  return "-";
                }
              }
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
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
          }, {
            className: 'tablet',
            targets: [0, 1, 2, 3]
          }, {
            className: 'mobile',
            targets: [0, 2]
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
          oSearch: {
              sSearch: _p_search
          },
          initComplete: function(a, b) {
            $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
              '<div class="dataTables_buttons hidden-sm-down actions">' +
              '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" />' +
              '</div>'
            );
          },
            drawCallback: function() {
            handleCxFilter_setXlsx(_table);
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
    };

    $("#" + _table).on("click", "a.action-edit", function(e) {
      e.preventDefault();
      resetForm();

      var temp = table_absen.row($(this).closest('tr')).data();
      var masuk = document.querySelector("."+_section+"-masuk");
      var pulang = document.querySelector("."+_section+"-pulang");

      _key = temp.id;

      var tukar = document.querySelector("."+_section+"-action-change");
      if(temp.masuk!==null && temp.pulang!==null){
        tukar.style.display = 'none';
        //masuk.readOnly = false;
        //pulang.readOnly = false;
      }else{
        tukar.style.display = 'block';
        //masuk.readOnly = true;
        //pulang.readOnly = true;
      }

      $.each(temp, function(key, item) {
        $(`#${_form} .${_section}-${key}`).val(item).trigger("input").trigger("change");
      });
    });

    $("#" + _modal + " ." + _section + "-action-change").on("click", function(e) {
        var masuk = document.querySelector("."+_section+"-masuk");
        var verifikasi_masuk = document.querySelector("."+_section+"-verifikasi_masuk");
        var mesin_masuk = document.querySelector("."+_section+"-mesin_masuk");
        var pulang = document.querySelector("."+_section+"-pulang");
        var verifikasi_pulang = document.querySelector("."+_section+"-verifikasi_pulang");
        var mesin_pulang = document.querySelector("."+_section+"-mesin_pulang");
        if (masuk.value !== "" && pulang.value == "") {
            pulang.value = masuk.value;
            verifikasi_pulang.value = verifikasi_masuk.value;
            mesin_pulang.value = mesin_masuk.value;
            masuk.value = null;
            verifikasi_masuk.value = null;
            mesin_masuk.value = null;
        } else {
            masuk.value = pulang.value;
            verifikasi_masuk.value = verifikasi_pulang.value;
            mesin_masuk.value = mesin_pulang.value;
            pulang.value = null;
            verifikasi_pulang.value = null;
            mesin_pulang.value = null;
        }
    });


    $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
      e.preventDefault();
      $.ajax({
        type: "post",
        url: "<?php echo base_url('absen/ajax_save/') ?>" + _key,
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

    $("#" + _table).on("click", "a.action-delete", function(e) {
      e.preventDefault();
      var temp = table_absen.row($(this).closest('tr')).data();

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
            url: "<?php echo base_url('absen/ajax_delete/') ?>" + temp.id,
            dataType: "json",
            success: function(response) {
              if (response.status) {
                resetForm();
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

    $("#collapseCardCxFilter [name='cx_filter[unit_id]']").on("change", function() {
      if($(this).val()==='all'){
        location.reload();
      }else{
        load_refSubUnit($(this).val());
      }
    });

    async function load_refSubUnit(unitId) {
      var cmpSubUnit = $("#collapseCardCxFilter [name='cx_filter[sub_unit_id]']");
      var defaultValue = cmpSubUnit.val();

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
    $("#" + _table).DataTable().ajax.url("<?php echo base_url('absen/ajax_get_all') ?>" + params);
    $("#" + _table).DataTable().clear().draw();
  };

  function handleCxFilter_xlsx() {
    var params = handleCxFilter_getParams();
    var url = "<?php echo base_url('absen/xlsx') ?>" + params;
    window.location.href = url;
  }

</script>