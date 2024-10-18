<script type="text/javascript">
  $(document).ready(function() {

    var _key = "<?= $key ?>";
    var _section = "mappinggaji";
    var _table_master = "table-mappinggaji";
    var _modal_form = "modal-form-mappinggaji";
    var _form = "form-mappinggaji";
    var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
    var _is_load_partial = "<?= (isset($is_load_partial)) ? $is_load_partial : '0' ?>";
    var _is_first_load = (_key != null && _key != "") ? true : false;
    var _indikator_index = 0;

    if (_is_load_partial !== '0') {
      load_indikatorItem();
    };

    // Initialize DataTables
    if (_is_load_partial === '0' && $(`#${_table_master}`)[0]) {
      if ($.fn.DataTable.isDataTable(`#${_table_master}`) === false) {
        var table_master = $(`#${_table_master}`).DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "<?php echo base_url('mappinggaji/ajax_get_all/') ?>",
            type: "get"
          },
          columns: [{
              data: null,
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            {
              data: "nama_unit"
            },
            {
              data: "nama_sub_unit"
            },
            {
              data: "nama_jabatan"
            },
            {
              data: "nama_jenis_pegawai"
            },
            {
              data: null,
              render: function(data, type, row, meta) {
                return `
                    <div class="action" style="display: flex; flex-direction: row;">
                        <a href="<?= base_url('mappinggaji/input?ref=') ?>${row.id}" modal-id="modal-form-mappinggaji" class="btn btn-sm btn-light x-load-modal-partial" title="Ubah"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;
                        <a href="<?= base_url('mappinggaji/delete?ref=') ?>${row.id}" class="btn btn-sm btn-danger action-delete" title="Hapus"><i class="zmdi zmdi-delete"></i> Hapus</a>
                    </div>
                `;
              }
            }
          ],
          order: [
            [1, 'desc']
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
            targets: [0, 2, 3]
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
    $(document).on("click", `#${_modal_form} .mappinggaji-action-save`, function(e) {
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
              url: "<?php echo base_url('mappinggaji/ajax_save/') ?>",
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
              url: "<?php echo base_url('mappinggaji/ajax_delete/') ?>" + temp.id,
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

    // Handle unit change
    $(`#${_form} .mappinggaji-unit_id`).on("change", function() {
      load_refSubUnit($(this).val());
    });

    // Handle indikator change
    $(document).on("change", `#${_form} .mappinggaji-indikator`, function() {
      var index = $(this).attr("data-index");
      var attrData = $(this).find(":selected").data();
      var alias = attrData.nama_alias;
      var defaultExpression = attrData.default_expression;

      $(`.mappinggaji-indikator-label-${index}`).html(`(<b>${alias}</b>)`);
      $(`.mappinggaji-expression-${index}`).val(defaultExpression);
    });

    // Handle indikator add row
    $(`#${_form} .mappinggaji-indikator-add`).on("click", function() {
      _addIndikatorItem();
    });

    // Handle indikator delete row
    $(document).on("click", `#${_form} .mappinggaji-indikator-delete`, function() {
      var index = $(this).attr("data-index");
      $(`.mappinggaji-indikator-item-${index}`).remove();
    });

    // Handle fetch sub unit
    async function load_refSubUnit(unitId) {
      var cmpSubUnit = $(`#${_form} .mappinggaji-sub_unit_id`);
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

    async function load_indikatorItem() {
      if (_key != null && _key != "") {
        // Fetch new option
        await $.ajax({
          url: "<?= base_url('mappinggaji/ajax_get_indikator_item/') ?>",
          type: "get",
          data: {
            "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>",
            mapping_gaji_id: _key,
          },
          dataType: "json",
          success: function(response) {
            if (response.length > 0) {
              response.map((item, index) => {
                _addIndikatorItem(item);
              });
            };
          }
        });
      };
    };

    function _addIndikatorItem(data = null) {
      _indikator_index = _indikator_index + 1;
      var dom = `
        <div class="row mappinggaji-indikator-item-${_indikator_index}">
            <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                    <label required>Indikator <span class="mappinggaji-indikator-label-${_indikator_index}"></span></label>
                    <div class="select">
                        <select name="indikator[${_indikator_index}][indikator_gaji_id]" class="form-control select2-partial mappinggaji-indikator mappinggaji-indikator-${_indikator_index}" data-index="${_indikator_index}" data-placeholder="Pilih &#8595;" required>
                            <?= $indikator_list ?>
                        </select>
                    </div>
                    <input type="hidden" name="indikator[${_indikator_index}][id]" class="mappinggaji-indikato-id-${_indikator_index}" readonly />
                </div>
            </div>
            <div class="col-xs-12 col-sm-7">
                <div class="form-group">
                    <label required>Expression</label>
                    <div class="input-group">
                      <textarea name="indikator[${_indikator_index}][expression]" class="form-control mappinggaji-expression mappinggaji-expression-${_indikator_index}" placeholder="Expression" rows="1" required></textarea>
                      <div class="input-group-append">
                        <a href="javascript:;" class="bg-red mappinggaji-indikator-delete" data-index="${_indikator_index}" title="Hapus">
                          <span class="input-group-text" style="border-radius: 0 0.35rem 0.35rem 0 !important;"><i class="zmdi zmdi-close text-red"></i></span>
                        </a>
                      </div>
                    </div>
                </div>
            </div>
        </div>
      `;
      $(".indikator-collection").append(dom);
      $(".select2-partial").select2();

      if (data != null) {
        $(`.mappinggaji-indikato-id-${_indikator_index}`).val(data.id).trigger("input");
        $(`.mappinggaji-indikator-${_indikator_index}`).val(data.indikator_gaji_id).trigger("change");
        $(`.mappinggaji-expression-${_indikator_index}`).val(data.expression).trigger("input");
      };
    };

  });
</script>