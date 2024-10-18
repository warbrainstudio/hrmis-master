<script type="text/javascript">
    $(document).ready(function() {
        var _key = "<?= $key ?>";
        var _section = "demosimutasi";
        var _table_master = "table-demosimutasi";
        var _modal_view = "modal-view-demosimutasi";
        var _modal_form = "modal-form-demosimutasi";
        var _form = "form-demosimutasi";
        var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
        var _is_load_partial = "<?= (isset($is_load_partial)) ? $is_load_partial : '0' ?>";
        var _is_first_load = (_key != null && _key != "") ? true : false;
        var _pegawai_id = "<?= @$pegawai_id ?>";
        var _pegawai_namaLengkap = "<?= @$pegawai_nama_lengkap ?>";

        // Init on load
        initSelect2_enter(".demosimutasi-pegawai_id", "Cari dengan NRP / Nama Lengkap...", "<?= base_url('ref/ajax_search_pegawai') ?>", formatSelect2Result_pegawai);
        load_select2DefaultValue();

        // Initialize DataTables
        if (_is_load_partial === '0' && $(`#${_table_master}`)[0]) {
            if ($.fn.DataTable.isDataTable(`#${_table_master}`) === false) {
                var table_master = $(`#${_table_master}`).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo base_url('demosimutasi/ajax_get_all/') ?>",
                        type: "get"
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
                            data: "nrp"
                        },
                        {
                            data: "nama_lengkap"
                        },
                        {
                            data: "no_sk",
                            render: function(data, type, row, meta) {
                                if (data != null) {
                                    return moment(data).format('Y-MM-DD');
                                };
                                return "-";
                            }
                        },
                        {
                            data: "no_skppj",
                            render: function(data, type, row, meta) {
                                if (data != null) {
                                    return moment(data).format('Y-MM-DD');
                                };
                                return "-";
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
                                    <a href="<?= base_url('demosimutasi/detail?ref=') ?>${row.id}" modal-id="modal-view-demosimutasi" class="btn btn-sm btn-success x-load-modal-partial" title="Rincian"><i class="zmdi zmdi-eye"></i></a>&nbsp;
                                    <a href="<?= base_url('demosimutasi/input?ref=') ?>${row.id}" modal-id="modal-form-demosimutasi" class="btn btn-sm btn-light x-load-modal-partial" title="Ubah"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;
                                    <a href="<?= base_url('demosimutasi/delete?ref=') ?>${row.id}" class="btn btn-sm btn-danger action-delete" title="Hapus"><i class="zmdi zmdi-delete"></i> Hapus</a>
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
                        targets: [0, 1, 2, 3]
                    }, {
                        className: 'mobile',
                        targets: [0, 3]
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
                    initComplete: function(a, b) {
                        $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
                            '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
                            '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
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
        $(document).on("click", `#${_modal_form} .demosimutasi-action-save`, function(e) {
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
                            url: "<?php echo base_url('demosimutasi/ajax_save/') ?>",
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
                            url: "<?php echo base_url('demosimutasi/ajax_delete/') ?>" + temp.id,
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

        // Handle new unit change
        $(`#${_form} .demosimutasi-new_unit_id`).on("select2:select", function() {
            load_refSubUnit($(this).val(), 'new_sub_unit_id');
        });

        // Handle pegawai selected
        $(document).on("select2:select", `#${_modal_form} .demosimutasi-pegawai_id`, function() {
            var selected = $(`#${_modal_form} .demosimutasi-pegawai_id`).find(":selected").data();
            if (typeof selected != "undefined") {
                // Temp value
                $(`#${_modal_form} .demosimutasi-old_unit_id`).val(selected.data.unit_id).trigger("input");
                $(`#${_modal_form} .demosimutasi-old_sub_unit_id`).val(selected.data.sub_unit_id).trigger("input");
                $(`#${_modal_form} .demosimutasi-old_jabatan_id`).val(selected.data.jabatan_id).trigger("input");
                $(`#${_modal_form} .demosimutasi-old_tenaga_unit_id`).val(selected.data.tenaga_unit_id).trigger("input");
                $(`#${_modal_form} .demosimutasi-old_jenis_pegawai_id`).val(selected.data.jenis_pegawai_id).trigger("input");
                // Auto filled text
                $(`.auto-filled-text-nama_unit`).html(selected.data.nama_unit);
                $(`.auto-filled-text-nama_sub_unit`).html(selected.data.nama_sub_unit);
                $(`.auto-filled-text-nama_jabatan`).html(selected.data.nama_jabatan);
                $(`.auto-filled-text-nama_tenaga_unit`).html(selected.data.nama_tenaga_unit);
                $(`.auto-filled-text-nama_jenis_pegawai`).html(selected.data.nama_jenis_pegawai);
            };
        });

        // Handle fetch sub unit
        async function load_refSubUnit(unitId, subUnitId) {
            var cmpSubUnit = $(`#${_form} .demosimutasi-${subUnitId}`);
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

        // Handle select2 default value for edit
        function load_select2DefaultValue() {
            setTimeout(() => {
                if (_is_first_load === true && (_pegawai_id != "" && _pegawai_namaLengkap != "")) {
                    var optionPegawai = new Option(_pegawai_namaLengkap, _pegawai_id, true, true);
                    $(".demosimutasi-pegawai_id").append(optionPegawai).trigger("change");
                };
            }, 300);
        };
    });
</script>