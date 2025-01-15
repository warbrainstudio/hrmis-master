<script type="text/javascript">
    $(document).ready(function() {
        var _key = "<?= $key ?>";
        var _section = "contract";
        var _table_master = "table-contract";
        var _modal_view = "modal-view-contract";
        var _modal_form = "modal-form-contract";
        var _form = "form-contract";
        var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
        var _is_load_partial = "<?= (isset($is_load_partial)) ? $is_load_partial : '0' ?>";
        var _is_first_load = (_key != null && _key != "") ? true : false;
        var _pegawai_id = "<?= @$pegawai_id ?>";
        var _pegawai_namaLengkap = "<?= @$pegawai_nama_lengkap ?>";
        var _contract_type = <?= $contract_type ?>;

        // Init on load
        initSelect2_enter(".contract-pegawai_id", "Cari dengan NRP / Nama Lengkap...", "<?= base_url('ref/ajax_search_pegawai') ?>", formatSelect2Result_pegawai);
        load_select2DefaultValue();

        // Initialize DataTables
        if (_is_load_partial === '0' && $(`#${_table_master}`)[0]) {
            if ($.fn.DataTable.isDataTable(`#${_table_master}`) === false) {
                var table_master = $(`#${_table_master}`).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo base_url('contract/ajax_get_all/') ?>",
                        type: "get"
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: "no_kontrak"
                        },
                        {
                            data: "nrp"
                        },
                        {
                            data: "nama_lengkap"
                        },
                        {
                            data: "nama_jenis_pegawai"
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
                                    <a href="<?= base_url('contract/detail?ref=') ?>${row.id}" modal-id="modal-view-contract" class="btn btn-sm btn-success x-load-modal-partial" title="Rincian"><i class="zmdi zmdi-eye"></i></a>&nbsp;
                                    <a href="<?= base_url('contract/input?ref=') ?>${row.id}" modal-id="modal-form-contract" class="btn btn-sm btn-light x-load-modal-partial" title="Ubah"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;
                                    <a href="<?= base_url('contract/delete?ref=') ?>${row.id}" class="btn btn-sm btn-danger action-delete" title="Hapus"><i class="zmdi zmdi-delete"></i> Hapus</a>
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
                        targets: [0, 1, 3, 4]
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
        $(document).on("click", `#${_modal_form} .contract-action-save`, function(e) {
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
                            url: "<?php echo base_url('contract/ajax_save/') ?>",
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
                            url: "<?php echo base_url('contract/ajax_delete/') ?>" + temp.id,
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

        // Handle pegawai selected
        $(document).on("select2:select", `#${_modal_form} .contract-pegawai_id`, function() {
            var selected = $(`#${_modal_form} .contract-pegawai_id`).find(":selected").data();
            if (typeof selected != "undefined") {
                load_refSubUnit(selected.data.unit_id).then(function() {
                    $(`#${_modal_form} .contract-kategori_pegawai_id`).val(selected.data.kategori_pegawai_id).trigger("change");
                    $(`#${_modal_form} .contract-jenis_pegawai_id`).val(selected.data.jenis_pegawai_id).trigger("change");
                    $(`#${_modal_form} .contract-status_kontrak_id`).val(selected.data.status_kontrak_id).trigger("change");
                    $(`#${_modal_form} .contract-jabatan_id`).val(selected.data.jabatan_id).trigger("change");
                    $(`#${_modal_form} .contract-unit_id`).val(selected.data.unit_id).trigger("change.select2");
                    $(`#${_modal_form} .contract-sub_unit_id`).val(selected.data.sub_unit_id).trigger("change");
                });
            };
        });

        // Handle unit change
        $(`#${_form} .contract-unit_id`).on("change", function() {
            load_refSubUnit($(this).val());
        });

        // Handle status pegawai change
        // $(`#${_form} .contract-jenis_pegawai_id`).on("change", function() {
        //     var statusPegawai = $(this).val();
        //     statusPegawai = (statusPegawai != "" && statusPegawai != null) ? parseInt(statusPegawai) : null;

        //     var isMitra = $.inArray(statusPegawai, _contract_type.mitra);
        //     isMitra = (isMitra != -1) ? true : false;

        //     if (isMitra === true) {
        //         $(`#${_form} .section-contract_pembayaran`).show();
        //     } else {
        //         $(`#${_form} .section-contract_pembayaran`).hide();
        //     };
        // });

        // Handle fetch sub unit
        async function load_refSubUnit(unitId) {
            var cmpSubUnit = $(`#${_form} .contract-sub_unit_id`);
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
                    $(".contract-pegawai_id").append(optionPegawai).trigger("change");
                };
            }, 300);
        };
    });
</script>