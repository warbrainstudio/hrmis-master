<script type="text/javascript">
  $(document).ready(function() {

    var _key = "";
    var _section = "absen";
    var _table = "table-absen";
    var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
    var _is_load_partial = "<?= (isset($is_load_partial)) ? $is_load_partial : '0' ?>";
    var _is_first_load = (_key != null && _key != "") ? true : false;
    var _pegawai_index = 0;
    const sectionSelector = "#" + _section;
    var exportButton = document.querySelector(sectionSelector+' .exportbutton');
    exportButton.style.display = 'none';
    var currentDate = "";
    const dateInputDay = document.querySelector('.absen-tanggal');
    const dateInputMonth = document.querySelector('.absen-bulan');
    const dateInputYear = document.querySelector('.absen-tahun');

    if (_is_load_partial !== '0') {
      load_PegawaiItem();
    };

    $(sectionSelector).on('focus', '.absen-tanggal', function() {
        flatpickr(this, {
            dateFormat: "Y-m-d",
            allowInput: true,
            minDate: "2023-07-04",
            maxDate: new Date(),
            onChange: function(selectedDates, dateStr) {
                console.log("Selected Day: ", dateStr);
            },
        });
    });

    // Initialize Flatpickr for month selection
    $(sectionSelector).on('focus', '.absen-bulan', function() {
        flatpickr(this, {
            dateFormat: "Y-m",
            allowInput: true,
            minDate: "2023-07-04",
            maxDate: new Date(),
            onChange: function(selectedDates, dateStr) {
                console.log("Selected Month: ", dateStr);
            },
        });
    });

    // Initialize Flatpickr for year selection
    $(sectionSelector).on('focus', '.absen-tahun', function() {
        flatpickr(this, {
            dateFormat: "Y",
            allowInput: true,
            minDate: "2023-07-04",
            maxDate: new Date(),
            onChange: function(selectedDates, dateStr) {
                console.log("Selected Year: ", dateStr);
            },
        });
    });

    // Initialize DataTables: Index
    if (_is_load_partial === '0' && $("#" + _table)[0]) {
      if ($.fn.DataTable.isDataTable(`#${_table}`) === false) {
        var table_attendancelog = $("#" + _table).DataTable({
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
              data: "absen_id"
            },
            {
              data: "tanggal_absen",
              render: function(data, type, row, meta) {
                return moment(data).format('DD-MM-YYYY HH:mm:ss');
              }
            },
            {
              data: "nama_status",
              render: function(data, type, row, meta) {
                var statusColor = (data == 'Masuk') ? 'warning' : 'primary';
                return `<span class="badge badge-${statusColor}">${data}</span>`;
              }
            },
            {
              data: "verifikasi",
              render: function(data, type, row, meta) {
                var verifiedColor = (data == 'Finger') ? 'success' : 'danger';
                return `<span class="badge badge-${verifiedColor}">${data}</span>`;
              }
            },
            {
              data: "ipmesin"
            },
          ],
          order: [[2, 'desc']],
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
            targets: [0, 1, 2, 3, 4, 5]
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

    $("#" + _section).on("click", "button." + _section + "-exportdata", function(e) {
      var downloadUrl = "<?php echo site_url('absen/excel?ref=cxsmi&date=') ?>" + currentDate;
      window.location.href = downloadUrl;
      currentDate = "";
      exportButton.style.display = 'none';
      dateInputDay.value = "";
      dateInputMonth.value = "";
      dateInputYear.value = "";
      //location.reload();
    });

    $("#" + _section).on("click", "button." + _section + "-getdata-today", function(e) {
        e.preventDefault();
        const dateValue = dateInputDay.value;
        if (dateValue) {
            currentDate = moment(dateValue, 'YYYY-MM-DD').format('YYYY-MM-DD');
            table_attendancelog.search(currentDate).draw();
            table_attendancelog.on('draw', function() {
                exportButton.style.display = 'block';
                table_attendancelog.off('draw');
            });
            //$("#" + _table).DataTable().order([5, 'asc']).draw();
            dateInputDay.value = "";
            dateInputMonth.value = "";
        }
    });


    $("#" + _section).on("click", "button." + _section + "-getdata-month", function(e) {
      e.preventDefault();
        const dateValue = dateInputMonth.value;
        if (dateValue) { 
          currentDate = moment(dateValue, 'YYYY-MM-DD').format('YYYY-MM');
          table_attendancelog.search(currentDate).draw();
          table_attendancelog.on('draw', function() {
              exportButton.style.display = 'block';
              table_attendancelog.off('draw');
          });
          //$("#" + _table).DataTable().order([5, 'asc']).draw();
          dateInputDay.value = "";
          dateInputMonth.value = "";
        } 
    });

    $("#" + _section).on("click", "button." + _section + "-getdata-year", function(e) {
      e.preventDefault();
        const dateValue = dateInputYear.value;
        if (dateValue) {
          exportButton.style.display = 'block';
          currentDate = moment(dateValue, 'YYYY-MM-DD').format('YYYY');
          table_attendancelog.search(currentDate).draw();
        } 
    });
  });

</script>