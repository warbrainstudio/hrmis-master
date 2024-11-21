<script type="text/javascript">
  var _key = "";
  var _section = "absen";
  var _table = "table-absen";
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
                //var month = moment(data).format('MM');
                return data;
              }
            },
            {
              data: "nama",
              render: function(data, type, row, meta) {
                if(data=='-'){
                  var link = "ID Absen : "+row.absen_id;
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
                  return `<span class="badge badge-${verifiedColor}">${moment(data).format('HH:mm:ss')}`;
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
                  const yesterday = new Date(today);
                  yesterday.setDate(today.getDate() - 1);
                  if (date.getTime() === today.getTime()) {
                    if(!row.masuk && row.pulang){
                      return `<span class="badge badge-warning" title="Data ambigu"><i class="zmdi zmdi-info-outline"> Notice</i></span>`;
                    }else{
                      return `<span class="badge badge-info" title="belum pulang"><i class="zmdi zmdi-time"></i></span>`;
                    }
                  } else {
                    if(date.getDate() === yesterday.getDate()){
                      if(!row.masuk && row.pulang){
                        return `<span class="badge badge-warning" title="Data ambigu"><i class="zmdi zmdi-info-outline"> Notice</i></span>`;
                      }else{
                        return `<span class="badge badge-info" title="belum pulang"><i class="zmdi zmdi-time"></i></span>`;
                      }
                    }else{
                      return `<span class="badge badge-danger" title="Data tidak lengkap"><i class="zmdi zmdi-alert-circle"> Notice</i></span>`;
                    }
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
            targets: [0, 1, 2, 3, 4, 5, 6]
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