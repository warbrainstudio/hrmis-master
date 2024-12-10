<script type="text/javascript">
  var _key = "<?= $key ?>";
  var _id = "";
  var _searchFilter = "<?= $searchFilter ?>";
  var _searchFilterPeriode = "<?= $searchFilterPeriode ?>";
  var _section = "kalenderabsen";
  var _table = "table-absen-periode";
  var _modal = "modal-form-kalenderabsen";
  var _form = "form-kalenderabsen";
  const calendar = ".calendar";
  const days = document.querySelectorAll(calendar + ' .day');
  let isFetching = false; 
  const storedDate = localStorage.getItem('selectedDate');
  
  $(document).ready(function() {

    if (storedDate) {
        fetchData(storedDate);
        localStorage.removeItem('selectedDate');
    }

    if ($("#" + _table)[0]) {
      var daily = "<?= $isDaily ?>";
      var tanggal = "";
      if(daily=='true'){
        tanggal = null;
      }else{
        tanggal = "tanggal_absen";
      }
      var table = $("#" + _table).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "<?php echo base_url('kalenderabsen/ajax_get_all/') ?>",
          type: "get",
            data: {
              searchFilter: _searchFilter,
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
                if(daily==true){
                    return "-";
                }else{
                    //var day = moment(data).format('ddd');
                    //var dayDate = moment(data).format('DD');
                    //var getDay = getTranslateNameDay(day);
                    return moment(data).format('DD');
                }
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
                  var tanggal = moment(row.tanggal_absen).format('DD-MM-YYYY');
                  var DateMasuk = moment(data).format('DD-MM-YYYY');
                  var DatePulang = moment(row.pulang).format('DD-MM-YYYY');
                  if(row.pulang){
                    if(DateMasuk!=DatePulang){
                      if(DateMasuk==tanggal){
                        return `<span class="badge badge-${verifiedColor}">${moment(data).format('HH:mm:ss')}`;
                      }else{
                        let verifiedColor = 'dark';
                        return `<span class="badge badge-${verifiedColor}" title="hari masuk berbeda. ${DateMasuk}">${moment(data).format('HH:mm:ss')}`;
                      }
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
                var mesin = row.nama_mesin_masuk ? `${row.nama_mesin_masuk}</span>` : row.mesin_masuk;
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
                  var tanggal = moment(row.tanggal_absen).format('DD-MM-YYYY');
                  var DateMasuk = moment(row.masuk).format('DD-MM-YYYY');
                  var DatePulang = moment(data).format('DD-MM-YYYY');
                  if(row.pulang){
                    if(DateMasuk!=DatePulang){
                      if(DatePulang==tanggal){
                        return `<span class="badge badge-${verifiedColor}">${moment(data).format('HH:mm:ss')}`;
                      }else{
                        let verifiedColor = 'dark';
                        return `<span class="badge badge-${verifiedColor}" title="hari pulang berbeda. ${DatePulang}">${moment(data).format('HH:mm:ss')}`;
                      }
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
                var mesin = row.nama_mesin_pulang ? `${row.nama_mesin_pulang}</span>` : row.mesin_pulang;
                return verifikasi+" / "+mesin;
              }
            },
            {
              data: "jam_kerja",
              render: function(data, type, row, meta) {
                if (data === null) {
                  if(row.masuk!==null && row.pulang===null){
                    var masuk = moment(row.jam_masuk, 'HH:mm:ss');
                    var compareTime = moment('19:00:00', 'HH:mm:ss');
                    if (masuk.isAfter(compareTime)) {
                      return `<span class="badge badge-dark" title="absensi pulang bisa di cek di hari selanjutnya"><i class="zmdi zmdi-check-circle"></i> Shift Malam</span>`;
                    }else{
                      return `<span class="badge badge-danger" title="Data tidak lengkap"><i class="zmdi zmdi-alert-circle"></i> Notice</span>`;
                    }
                  }else{
                    return `<span class="badge badge-danger" title="Data tidak lengkap"><i class="zmdi zmdi-alert-circle"></i> Notice</span>`;
                  }
                } else {
                  var jam = parseFloat(data);
                  if (!isNaN(jam) && jam >= 0) {
                    if(jam < 6.9){
                      return `<span class="badge badge-warning" title="Jam kerja kurang"><i class="zmdi zmdi-minus-circle"></i> ${jam.toFixed(1)} Jam</span>`;
                    }else{
                      return `<span class="badge badge-info"><i class="zmdi zmdi-check-all"></i> ${jam.toFixed(1)} Jam</span>`;
                    }
                  } else {
                    return `<span class="badge badge-warning" title="Data ambigu"><i class="zmdi zmdi-info-outline"></i> Notice</span>`;
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
                if(row.jadwal_id===null){
                  if(row.masuk){
                      if(masuk && pulang){
                        if(data===null){
                          return `<a href="javascript:;" title="Sistem tidak bisa menentukan shift. Tentukan shift manual ?" class="btn btn-sm btn-warning btn-table-action action-edit-jadwal" data-toggle="modal" data-target="#${_modal}"><i class="zmdi zmdi-help"></i></a>&nbsp;`;
                        }else{
                          row.jadwal_id = row.id_jadwal;
                          return `<a title="dari ${jam_masuk} s/d ${jam_pulang}">${data}</a>`;
                        }
                      }else{
                        return "-";
                      }
                  }else{
                    return "-";
                  }
                }else{
                  return `<a title="dari ${jam_masuk} s/d ${jam_pulang}">${row.nama_jadwal}</a>`;
                }
              }
            },
            {
              data: null,
              render: function(data, type, row, meta) {
                var del = `<a href="javascript:;" class="btn btn-sm btn-danger btn-table-action action-delete"><i class="zmdi zmdi-delete"></i> Hapus</a>`;
                var edit = `<a href="javascript:;" class="btn btn-sm btn-light btn-table-action action-edit" data-toggle="modal" data-target="#${_modal}"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;`;       
                var masuk = moment(row.jam_masuk, 'HH:mm:ss');
                var compareTime = moment('19:00:00', 'HH:mm:ss');
                if (masuk.isAfter(compareTime) && row.pulang === null) {
                  return `<div class="action" style="display: flex; flex-direction: row;">${del}</div>`;
                }else{
                  return `<div class="action" style="display: flex; flex-direction: row;">${edit} ${del}</div>`;
                }
              }
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
          targets: [0, 1, 2, 3, 4]
        }, {
          className: 'mobile',
          targets: [0, 1, 2, 3, 4]
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

    $("#" + _table).on("click", "a.action-edit-jadwal", function(e) {
      e.preventDefault();
      resetForm();

      var temp = table.row($(this).closest('tr')).data();
      var masuk = document.querySelector("."+_section+"-masuk");
      var verifikasi_masuk = document.querySelector("."+_section+"-row_verifikasi_masuk");
      var mesin_masuk = document.querySelector("."+_section+"-row_mesin_masuk");
      var pulang = document.querySelector("."+_section+"-pulang");
      var verifikasi_pulang = document.querySelector("."+_section+"-row_verifikasi_pulang");
      var mesin_pulang = document.querySelector("."+_section+"-row_mesin_pulang");
      var change = document.querySelector("."+_section+"-action-change");
      var save = document.querySelector("."+_section+"-action-save");

      _key = temp.id;

      save.style.display = "none";
      change.style.display  = "block";
      verifikasi_masuk.style.display  = "none";
      mesin_masuk.style.display  = "none";
      verifikasi_pulang.style.display  = "none";
      mesin_pulang.style.display  = "none";

      $.each(temp, function(key, item) {
        $(`#${_form} .${_section}-${key}`).val(item).trigger("input").trigger("change");
      });

      if(temp.masuk==null){
        masuk.disabled = false;
        let pulangDate = new Date(temp.pulang);
        pulangDate.setHours(pulangDate.getHours() + 7);
        let pulangFormatted = pulangDate.toISOString().slice(0, 19);
        $(`#${_form} .${_section}-masuk`).val(pulangFormatted).trigger("input").trigger("change");
      }else{
        masuk.disabled = true;
        let masukDate = new Date(temp.masuk);
        masukDate.setHours(masukDate.getHours() + 7);
        let masukFormatted = masukDate.toISOString().slice(0, 19);
        $(`#${_form} .${_section}-masuk`).val(masukFormatted).trigger("input").trigger("change");
      }

      if(temp.pulang==null){
        pulang.disabled = false;
        let masukDate = new Date(temp.masuk);
        masukDate.setHours(masukDate.getHours() + 7);
        let masukFormatted = masukDate.toISOString().slice(0, 19);
        $(`#${_form} .${_section}-pulang`).val(masukFormatted).trigger("input").trigger("change");
      }else{
        pulang.disabled = true;
        let pulangDate = new Date(temp.pulang);
        pulangDate.setHours(pulangDate.getHours() + 7);
        let pulangFormatted = pulangDate.toISOString().slice(0, 19);
        $(`#${_form} .${_section}-pulang`).val(pulangFormatted).trigger("input").trigger("change");
      }

    });

    $("#" + _table).on("click", "a.action-edit", function(e) {
      e.preventDefault();
      resetForm();

      var temp = table.row($(this).closest('tr')).data();
      var masuk = document.querySelector("."+_section+"-masuk");
      var verifikasi_masuk = document.querySelector("."+_section+"-row_verifikasi_masuk");
      var mesin_masuk = document.querySelector("."+_section+"-row_mesin_masuk");
      var pulang = document.querySelector("."+_section+"-pulang");
      var verifikasi_pulang = document.querySelector("."+_section+"-row_verifikasi_pulang");
      var mesin_pulang = document.querySelector("."+_section+"-row_mesin_pulang");
      var change = document.querySelector("."+_section+"-action-change");
      var save = document.querySelector("."+_section+"-action-save");

      _key = temp.id;

      masuk.disabled = false;
      pulang.disabled = false;
      save.style.display = "block";
      change.style.display  = "none";
      verifikasi_masuk.style.display  = "block";
      mesin_masuk.style.display  = "block";
      verifikasi_pulang.style.display  = "block";
      mesin_pulang.style.display  = "block";

      $.each(temp, function(key, item) {
        $(`#${_form} .${_section}-${key}`).val(item).trigger("input").trigger("change");
      });
      
      if(temp.masuk==null){
        pulang.disabled = true;
        let pulangDate = new Date(temp.pulang);
        pulangDate.setHours(pulangDate.getHours() + 7);
        let pulangFormatted = pulangDate.toISOString().slice(0, 19);
        $(`#${_form} .${_section}-masuk`).val(pulangFormatted).trigger("input").trigger("change");
      }else{
        let masukDate = new Date(temp.masuk);
        masukDate.setHours(masukDate.getHours() + 7);
        let masukFormatted = masukDate.toISOString().slice(0, 19);
        $(`#${_form} .${_section}-masuk`).val(masukFormatted).trigger("input").trigger("change");
      }

      if(temp.pulang==null){
        masuk.disabled = true;
        let masukDate = new Date(temp.masuk);
        masukDate.setHours(masukDate.getHours() + 7);
        let masukFormatted = masukDate.toISOString().slice(0, 19);
        $(`#${_form} .${_section}-pulang`).val(masukFormatted).trigger("input").trigger("change");
      }else{
        let pulangDate = new Date(temp.pulang);
        pulangDate.setHours(pulangDate.getHours() + 7);
        let pulangFormatted = pulangDate.toISOString().slice(0, 19);
        $(`#${_form} .${_section}-pulang`).val(pulangFormatted).trigger("input").trigger("change");
      }

    });


    $("#" + _modal + " ." + _section + "-action-save").on("click", function(e) {
      e.preventDefault();
      $.ajax({
        type: "post",
        url: "<?php echo base_url('kalenderabsen/ajax_save/') ?>" + _id,
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

    $("#" + _modal + " ." + _section + "-action-change").on("click", function(e) {
      e.preventDefault();
      $.ajax({
        type: "post",
        url: "<?php echo base_url('kalenderabsen/ajax_change_jadwal/') ?>" + _key,
        data: $("#" + _form).serialize(),
        success: function(response) {
          var response = JSON.parse(response);
          if (response.status === true) {
            resetForm();
            $("#" + _modal).modal("hide");
            $("#" + _table).DataTable().ajax.reload(null, false);
            notify(response.data, "success");
          } else {
            notify(response.error, "danger");
          };
        }
      });
    });

    $("#" + _table).on("click", "a.action-delete", function(e) {
      e.preventDefault();
      var temp = table.row($(this).closest('tr')).data();

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

    resetForm = () => {
      _id = "";
      $(`#${_form}`).trigger("reset");
    };

    days.forEach(day => {
        day.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#e0e0e0'; 
        });
        day.addEventListener('mouseout', function() {
            this.style.backgroundColor = ''; 
        });
    });

    $("#" + _section).on("click", "button." + _section + "-export", function(e) {
      swal({
        title: "Download Data",
        text: "Unduh data absen tanggal "+_key+" ?",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
        closeOnConfirm: false
      }).then((result) => {
          if (result.value) {
            var downloadUrl = "<?= base_url('kalenderabsen/xlsx_harian?ref=cxsmi&date=') ?>" + _key;
            window.location.href = downloadUrl;
          }
      });
    });

    $(`${calendar} .header_month .month_name .month_content`).on("click", function(e) {
        const currentUrl = window.location.pathname;
        const regex = /(\d{4})\/(\d{2})$/;
        const now = new Date();
        let date = '';
        const match = currentUrl.match(regex);
        if (match) {
            const year = match[1];
            const month = match[2];
            date = `${year}-${String(month).padStart(2, '0')}`;
        } else {
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            date = `${year}-${month}`;
        }

        var detailUrl = "<?php echo base_url('kalenderabsen/detail?date=') ?>" + date;
        window.location.href = detailUrl;
    });

    $(`${calendar} .day .no_content_fill_day`).on("click", function(e) {
        e.preventDefault();
        const currentUrl = window.location.pathname;
        const regex = /(\d{4})\/(\d{2})$/;
        const match = currentUrl.match(regex);
        const now = new Date();
        let year = "";
        let month = "";

        if (match) {
            year = match[1];
            month = match[2];
        } else {
            year = now.getFullYear();
            month = String(now.getMonth() + 1).padStart(2, '0');
        }

        const day = $(this).text().padStart(2, '0');
        const clickedDate = new Date(year, month - 1, day);
        const limit = new Date('2023-07-03');

        if (clickedDate < now && clickedDate > limit) {
            const dateShow = `${day}-${String(month).padStart(2, '0')}-${year}`;
            const tanggal = `${year}-${String(month).padStart(2, '0')}-${day}`;
            if (isFetching) {
                swal({
                    title: "Proses sedang berjalan",
                    text: "Silakan tunggu hingga proses selesai.",
                    icon: "info",
                    showCancelButton: false,
                    closeOnConfirm: true
                });
                return;
            } else {
              swal({
                  title: "Tarik Data",
                  text: `Data tanggal ${dateShow} belum ada. Ingin tarik data?`,
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonText: "Ya",
                  cancelButtonText: "Tidak",
                  closeOnConfirm: false
              }).then((result) => {
                if (result.value) {
                  localStorage.setItem('selectedDate', tanggal);
                  isFetching = true; 
                }
              });
            }
        }
        
    });


    $(`${calendar} .today .no_content_fill_today`).on("click", function(e) {
        e.preventDefault();
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = $(this).find('strong').text().padStart(2, '0');
        const tanggal = `${year}-${month}-${day}`;
           
        if (isFetching) {
              swal({
                  title: "Proses sedang berjalan",
                  text: "Silakan tunggu hingga proses selesai.",
                  icon: "info",
                  showCancelButton: false,
                  closeOnConfirm: true
              });
              return;
        }else{
            swal({
                title: "Tarik Data",
                text: `Data hari ini belum ada. Ingin tarik data?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
                closeOnConfirm: false
            }).then((result) => {
              if (result.value) {
                localStorage.setItem('selectedDate', tanggal);
                isFetching = true;
              }
            });
        }
    });

    $("#" + _section).on("click", "button." + _section + "-action-add", function(e) {
      e.preventDefault();
      const currentUrl = window.location.href;
      const url = new URL(currentUrl);
      const tanggal = url.searchParams.get("date");

      if (isFetching) {
          swal({
            title: "Proses sedang berjalan",
            text: "Silakan tunggu hingga proses selesai.",
            icon: "info",
            showCancelButton: false,
            closeOnConfirm: true
          });
        return;
      }else{
        swal({
          title: "Update Data absen?",
          text: `data absen akan diperbaharui`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Ya",
          cancelButtonText: "Tidak",
          closeOnConfirm: false
        }).then((result) => {
            if (result.value) {
              localStorage.setItem('selectedDate', tanggal);
              isFetching = true;
            }
        });
      }
    });

    function fetchData(tanggal) {
        const startTime = performance.now();

        $.ajax({
            type: "get",
            url: "<?php echo site_url('kalenderabsen/ajax_fetch_data_api'); ?>",
            data: { tanggal: tanggal },
            dataType: "json",
            success: function(parsedResponse) {
                const endTime = performance.now();
                const duration = ((endTime - startTime) / 1000).toFixed(1);

                if (parsedResponse.status === true) {
                  isFetching = false;
                } else {
                    swal({
                        title: "Error",
                        text: parsedResponse.message,
                        icon: "error",
                        button: "OK",
                    });
                    isFetching = false;
                }
            }
        });
    }

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
    $("#" + _table).DataTable().ajax.url("<?php echo base_url('kalenderabsen/ajax_get_all') ?>" + params);
    $("#" + _table).DataTable().clear().draw();
  };

  function handleCxFilter_xlsx() {
    var params = handleCxFilter_getParams();
    if(_searchFilterPeriode){
      params += (params ? '&' : '') + 'searchFilterPeriode=' + encodeURIComponent(_searchFilterPeriode);
    }else{
      params += (params ? '&' : '') + 'searchFilterPeriode=' + encodeURIComponent(_searchFilter);
    }
    var url = "<?php echo base_url('kalenderabsen/xlsx') ?>" + params;
    window.location.href = url;
  };
</script>