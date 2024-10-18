<script type="text/javascript">
  $(document).ready(function() {

    var _key = "<?= $key ?>";
    var _uniqueId = "<?= md5(microtime()) ?>";
    var _section = "diklat";
    var _table_master = "table-diklat";
    var _modal_form = "modal-form-diklat";
    var _form = "form-diklat";
    var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
    var _is_load_partial = "<?= (isset($is_load_partial)) ? $is_load_partial : '0' ?>";
    var _is_first_load = (_key != null && _key != "") ? true : false;
    var _participant_index = 0;
    var _participant_idExist = [];

    if (_is_load_partial !== '0') {
      initSelect2_enter(".diklat-pegawai_search", "Cari dengan NRP / Nama Lengkap...", "<?= base_url('ref/ajax_search_pegawai') ?>", formatSelect2Result_pegawai);
      load_participantItem();
    };

    // Initialize DataTables
    if (_is_load_partial === '0' && $(`#${_table_master}`)[0]) {
      if ($.fn.DataTable.isDataTable(`#${_table_master}`) === false) {
        var table_master = $(`#${_table_master}`).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('diklat/ajax_get_all/') ?>",
            type: "get"
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
              data: "tanggal_mulai"
            },
            {
              data: "tanggal_selesai"
            },
            {
              data: "total_peserta",
              render: $.fn.dataTable.render.number(',', '.', 0, '', ' Orang')
            },
            {
              data: null,
              render: function(data, type, row, meta) {
                return `
                    <div class="action" style="display: flex; flex-direction: row;">
                        <a href="<?= base_url('diklat/input?ref=') ?>${row.id}" modal-id="modal-form-diklat" class="btn btn-sm btn-light x-load-modal-partial" title="Ubah"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;
                        <a href="<?= base_url('diklat/delete?ref=') ?>${row.id}" class="btn btn-sm btn-danger action-delete" title="Hapus"><i class="zmdi zmdi-delete"></i> Hapus</a>
                    </div>
                `;
              }
            }
          ],
          order: [
            [2, 'desc']
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
            targets: [0, 1, 2, 3]
          }, {
            className: 'mobile',
            targets: [0, 1, 4]
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
            table_master.ajax.reload(null, false);
          };
        });
      };
    };

    // Handle submit
    $(document).on("click", `#${_modal_form} .diklat-action-save`, function(e) {
      e.preventDefault();
      tinyMCE.triggerSave();
      if (table_master) {
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
            var formData = new FormData($(`#${_form}`)[0]);
            $.ajax({
              type: "post",
              url: "<?php echo base_url('diklat/ajax_save/') ?>",
              data: formData,
              dataType: "json",
              enctype: "multipart/form-data",
              processData: false,
              contentType: false,
              cache: false,
              success: function(response) {
                if (response.status === true) {
                  $(`#${_modal_form}`).modal("hide");
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

    // Handle data delete
    $(`#${_table_master}`).on("click", "a.action-delete", function(e) {
      e.preventDefault();
      if (table_master) {
        var temp = table_master.row($(this).closest('tr')).data();
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
              url: "<?php echo base_url('diklat/ajax_delete/') ?>" + temp.id,
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

    // Handle participant add row
    $(`#${_form} .diklat-participant-add`).on("click", function(e) {
      e.preventDefault();
      var pegawaiSearch = $(".diklat-pegawai_search");
      var value = pegawaiSearch.val();
      var selected = pegawaiSearch.find(":selected").data();
      var data = null;

      if (value !== null) {
        if ($.inArray(selected.data.id, _participant_idExist) !== -1) {
          notify(`Pegawai "${selected.data.nrp} | ${selected.data.nama_lengkap}" sudah ada.`, "warning");
        } else {
          data = {
            "pegawai_id": selected.data.id,
            "nrp": selected.data.nrp,
            "nama_lengkap": selected.data.nama_lengkap
          };
          _addParticipantItem(data);
        };
      } else {
        notify("Silahkan pilih pegawai terlebih dahulu!", "warning");
      };
    });

    // Handle participant delete row
    $(document).on("click", `#${_form} .diklat-participant-delete-${_uniqueId}`, function(e) {
      e.preventDefault();
      var index = $(this).attr("data-index");
      var pegawaiId = $(this).attr("data-pegawai_id");

      // Remove from temp id
      _participant_idExist = jQuery.grep(_participant_idExist, function(value) {
        return value != pegawaiId;
      });

      $(`.diklat-participant-item-${index}`).remove();
    });

    // Handle upload: template sertifikat
    $(document).on("change", ".diklat-template_sertifikat", function() {
      var preview = `.upload-simple-preview-template_sertifikat`;

      $(`.diklat-template_sertifikat-existing`).hide();
      $(preview).show();
      readUploadSimple(this, preview);
    });

    // Handle upload: sertifikat
    $(document).on("change", ".diklat-participant-sertifikat", function() {
      var key = $(this).attr("data-key");
      var preview = `.upload-simple-preview-${key}`;

      $(`.diklat-participant-sertifikat-existing-${key}`).hide();
      $(preview).show();
      readUploadSimple(this, preview);
    });

    // Handle set sertifikat visibility by Kategori
    $(document).on("change", `#${_modal_form} input[type=radio][name=tipe]`, function(e) {
      e.preventDefault();
      _setParticipantVisibility();
      _setTemplateSertifikatVisibility();
    });

    // Handle set sertifikat visibility by Tanggal Selesai
    $(document).on("change", `#${_modal_form} .diklat-tanggal_selesai`, function(e) {
      e.preventDefault();
      _setParticipantVisibility();
    });

    // Handle view sertifikat popup
    $(document).on("click", `#${_modal_form} .embed-pdf-file-${_uniqueId}`, function(e) {
      e.preventDefault();
      var url = $(this).attr("href");
      var fullUrl = `<?= base_url() ?>${url}`;

      $("#modal-view-embed").css("z-index", 1600);
      $("#modal-view-embed").modal("show");
      $("#modal-view-embed-title").html("Sertifikat Diklat");
      $("#modal-view-embed-content").html("");

      if (PDFObject.supportsPDFs) {
        PDFObject.embed(fullUrl, "#modal-view-embed-content");
      } else {
        $("#modal-view-embed-content").html("Inline PDFs are not supported by this browser, try using the latest version of Chrome / Firefox.");
      };

      $("#modal-view-embed").on("hidden.bs.modal", function(e) {
        $("#modal-view-embed").css("z-index", "");
      });
    });

    // Handle load existing participant
    async function load_participantItem() {
      if (_key != null && _key != "") {
        // Fetch new option
        await $.ajax({
          url: "<?= base_url('diklat/ajax_get_participant_item/') ?>",
          type: "get",
          data: {
            "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>",
            diklat_id: _key,
          },
          dataType: "json",
          success: function(response) {
            if (response.length > 0) {
              response.map((item, index) => {
                _addParticipantItem(item);
              });
            };
          }
        });
      };
    };

    // Handle DOM participant
    function _addParticipantItem(data = null, scrollToBottom = true) {
      _participant_index = _participant_index + 1;

      // Extract data params
      var dataId = (data.id) ? data.id : '';
      var pegawaiId = (data.pegawai_id) ? data.pegawai_id : '';
      var nrp = (data.nrp) ? data.nrp : '';
      var namaLengkap = (data.nama_lengkap) ? data.nama_lengkap : '';
      var sertifikat = (data.sertifikat_file_name) ? data.sertifikat_file_name : '#';
      var nomorSertifikat = (data.nomor_sertifikat) ? data.nomor_sertifikat : '';
      var sebagai = (data.sebagai) ? data.sebagai : '';

      // Append DOM to collection
      var isSertifikatExist = (sertifikat.trim() != '#') ? 'block' : 'none';
      var isSertifikatUpload = (sertifikat.trim() == '#') ? 'block' : 'none';
      var dom = `
        <div class="row diklat-participant-item-${_participant_index}">
            <div class="col-xs-12 col-sm-12">
                <div class="form-group mb-2">
                  <input type="hidden" name="participant[${_participant_index}][id]" class="diklat-participant-id-${_participant_index}" readonly />
                  <input type="hidden" name="participant[${_participant_index}][pegawai_id]" class="diklat-pegawai_id-${_participant_index}" readonly />
                  <div class="input-group">
                    <div class="form-control diklat-participant-group-container" style="height: 56.97px; border-left: 2px solid #00ac69;">
                      <!-- Pegawai -->
                      <span class="diklat-participant-nrp-${_participant_index}" style="font-weight: 500; display: inline-block; min-width: 160px; background: #f9f9f9; text-align: center; padding: 5px; border-radius: 0.35rem; border: 1px solid #c5ccd6; cursor: default;" title="NRP">-</span>
                      <span class="diklat-participant-nama_lengkap-${_participant_index}" style="display: inline-block; padding: 5px; cursor: default;" title="Nama Pegawai">-</span>
                      <!-- Entends -->
                      <div class="row mt-2 diklat-participant-extend-item" style="display: none;">
                        <div class="col-xs-12 col-sm-6">
                          <input type="text" name="participant[${_participant_index}][nomor_sertifikat]" class="form-control form-control-sm m-0 diklat-nomor_sertifikat-${_participant_index}" placeholder="Nomor Sertifikat" title="Nomor Sertifikat" maxlength="30" />
                        </div>
                        <div class="col-xs-12 col-sm-6">
                          <input type="text" name="participant[${_participant_index}][sebagai]" class="form-control form-control-sm m-0 diklat-sebagai-${_participant_index}" placeholder="Sebagai" title="Sebagai" maxlength="100" />
                        </div>
                      </div>
                      <!-- Sertifikat -->
                      <div class="diklat-participant-sertifikat-item diklat-participant-sertifikat-item-${_participant_index}" style="float: right; padding-top: 5px; display: none;">
                        <div style="display: flex;">
                          <!-- Existing -->
                          <a href="${sertifikat}" class="diklat-participant-sertifikat-existing-${_participant_index} embed-pdf-file-${_uniqueId}" target="_blank" title="Unduh Sertifikat" style="display: ${isSertifikatExist};">
                            <i class="zmdi zmdi-download"></i> Sertifikat
                          </a>
                          <!-- Upload -->
                          <span class="upload-simple-preview upload-simple-preview-${_participant_index}" style="display: ${isSertifikatUpload};"></span>
                          <input type="file" name="participant[${_participant_index}][sertifikat]" class="upload-simple diklat-participant-sertifikat diklat-participant-sertifikat-${_participant_index}" data-key="${_participant_index}" data-text="Unggah Sertifikat" style="margin-left: 10px;" accept="application/pdf" />
                        </div>
                      </div>
                    </div>
                    <div class="input-group-append">
                      <a href="javascript:;" class="bg-red diklat-participant-delete-${_uniqueId}" data-index="${_participant_index}" data-pegawai_id="${pegawaiId}" title="Hapus">
                        <span class="input-group-text diklat-participant-group-action" style="border-radius: 0 0.35rem 0.35rem 0 !important; height: 56.97px;"><i class="zmdi zmdi-close text-red"></i></span>
                      </a>
                    </div>
                  </div>
                </div>
            </div>
        </div>
      `;
      $(".participant-collection").append(dom);

      // Collect temp id
      _participant_idExist.push(pegawaiId);

      // Set value
      $(`.diklat-participant-id-${_participant_index}`).val(dataId).trigger("input");
      $(`.diklat-pegawai_id-${_participant_index}`).val(pegawaiId).trigger("input");
      $(`.diklat-nomor_sertifikat-${_participant_index}`).val(nomorSertifikat).trigger("input");
      $(`.diklat-sebagai-${_participant_index}`).val(sebagai).trigger("input");
      $(`.diklat-participant-nrp-${_participant_index}`).html(nrp);
      $(`.diklat-participant-nama_lengkap-${_participant_index}`).html(namaLengkap);

      // Reset pegawai search
      $(".diklat-pegawai_search").trigger("select2:unselecting").select2("close");

      _setParticipantVisibility();

      if (scrollToBottom === true) {
        $(".participant-collection").animate({
          scrollTop: $(".participant-collection").prop("scrollHeight")
        }, 300);
      };
    };

    // Handle sertifikat visibility
    function _setParticipantVisibility() {
      var sertifikatContainer = $(`.diklat-participant-sertifikat-item`);
      var extendContainer = $(`.diklat-participant-extend-item`);
      var groupContainer = $(`.diklat-participant-group-container`);
      var groupActionContainer = $(`.diklat-participant-group-action`);
      var kategori = $(`#${_modal_form} input[type=radio][name=tipe]:checked`).val();
      var endDate = $(`#${_modal_form} .diklat-tanggal_selesai`).val();
      var currentDate = moment().format("YYYY-MM-DD");
      var isSame = moment(currentDate).isSame(moment(endDate));
      var isAfter = moment(currentDate).isAfter(moment(endDate));

      if (kategori === "Eksternal" && (isSame === true || isAfter === true)) {
        sertifikatContainer.show();
      } else {
        sertifikatContainer.hide();
      };

      if (kategori === "Internal") {
        extendContainer.show();
        groupContainer.css("height", "95.96px");
        groupActionContainer.css("height", "95.96px");
      } else {
        extendContainer.hide();
        groupContainer.css("height", "56.97px");
        groupActionContainer.css("height", "56.97px");
      };
    };

    // Handle template sertifikat visibility
    function _setTemplateSertifikatVisibility() {
      var kategori = $(`#${_modal_form} input[type=radio][name=tipe]:checked`).val();
      var tempatPelatihanContainer = $(`.diklat-tempat_pelatihan-container`);
      var templateSertifikatContainer = $(`.diklat-template_sertifikat-container`);

      if (kategori === "Internal") {
        tempatPelatihanContainer.removeClass("col-sm-12").addClass("col-sm-6");
        templateSertifikatContainer.show();
      } else {
        tempatPelatihanContainer.removeClass("col-sm-5").addClass("col-sm-12");
        templateSertifikatContainer.hide();
      };
    };

    // Handle pegawai list format
    function formatSelect2Result_pegawai(item) {
      var $container = $(
        `<div class="select2-result-repository clearfix">
            <div class="select2-result-repository__title" style="font-weight: 600;"></div>
            <div class="select2-result-repository__description"></div>
        </div>`
      );

      $container.find(".select2-result-repository__title").text(item.text);
      $container.find(".select2-result-repository__description").html(item.nrp);

      return $container;
    };

  });
</script>