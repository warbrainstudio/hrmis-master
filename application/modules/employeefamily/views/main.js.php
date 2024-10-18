<?php $unique_id = (isset($unique_id)) ? $unique_id : 'undefined' ?>

<script type="text/javascript">
    $(document).ready(function() {
        var _action_route = "<?= isset($action_route) ? $action_route : '' ?>";
        var _key = "<?= $key ?>";
        var _unique_id = "<?= (isset($unique_id)) ? $unique_id : '' ?>";
        var _section = "employeefamily";
        var _table_master = "table-employeefamily";
        var _modal_form = "modal-form-employeefamily";
        var _form = "form-employeefamily";
        var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
        var _is_load_partial = "<?= (isset($is_load_partial)) ? $is_load_partial : '0' ?>";
        var _is_first_load = (_key != null && _key != "") ? true : false;
        var _pegawai_id = "<?= @$pegawai_id ?>";

        // Initialize DataTables
        if (_is_load_partial === '0' && $(`#${_table_master}`)[0]) {
            if ($.fn.DataTable.isDataTable(`#${_table_master}`) === false) {
                var table_master = $(`#${_table_master}`).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo base_url('employeefamily/ajax_get_all/') ?>",
                        type: "get",
                        data: {
                            filter: `AND pegawai_id='${_pegawai_id}'`,
                        },
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: "nama_lengkap"
                        },
                        {
                            data: "hubungan"
                        },
                        {
                            data: "no_hp"
                        },
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                return `
                                    <div class="action" style="display: flex; flex-direction: row;">
                                        <a href="<?= base_url('employeefamily/input?ref=') ?>${row.id}&pegawai_id=${_pegawai_id}" modal-id="modal-form-employeefamily" class="btn btn-sm btn-light x-load-modal-partial2" title="Ubah"><i class="zmdi zmdi-edit"></i> Ubah</a>&nbsp;
                                        <a href="<?= base_url('employeefamily/delete?ref=') ?>${row.id}" class="btn btn-sm btn-danger action-delete" title="Hapus"><i class="zmdi zmdi-delete"></i> Hapus</a>
                                    </div>
                                `;
                            }
                        }
                    ],
                    order: [
                        [1, 'asc']
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
                        targets: [0, 1, 2, 3, 4]
                    }, {
                        className: 'tablet',
                        targets: [0, 1, 2, 3, 4]
                    }, {
                        className: 'mobile',
                        targets: [0, 1]
                    }, {
                        responsivePriority: 1,
                        targets: 0
                    }, {
                        responsivePriority: 1,
                        targets: -1
                    }, {
                        "visible": (_action_route !== 'detail') ? true : false,
                        "targets": [4]
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
        $(document).on("click", `#${_modal_form} .employeefamily-action-save-<?= $unique_id ?>`, function(e) {
            e.preventDefault();
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
                        url: "<?php echo base_url('employeefamily/ajax_save/') ?>",
                        data: formData,
                        dataType: "json",
                        enctype: "multipart/form-data",
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(response) {
                            if (response.status === true) {
                                $(`#${_modal_form}`).modal("hide");
                                reloadTableSource();
                                notify(response.data, "success");
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
                var swalTitle = "Anda akan menghapus data, lanjutkan?";
                var swalText = "Setelah dihapus, data tidak dapat dikembalikan lagi!";
                var swalType = "warning";
                var swalExec = function() {
                    $.ajax({
                        type: "delete",
                        url: "<?php echo base_url('employeefamily/ajax_delete/') ?>" + temp.id,
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                reloadTableSource();
                                parent.notifyPartial(response.data, "success");
                            } else {
                                parent.notifyPartial(response.data, "danger");
                            };
                        }
                    });
                };
                parent.swalPartial(swalTitle, swalText, swalType, swalExec);
            };
        });

        function reloadTableSource() {
            var iframe = $("#iframe-employee_family", window.parent.document);
            iframe.attr("src", "<?= base_url('employeefamily?ref=') ?>" + _pegawai_id);
            resizeIframe(iframe);
        };
    });
</script>