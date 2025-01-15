<script type="text/javascript">
    var _key = "";
    var _section = "employeeexpired";
    var _table = "table-expired";
    var _p_search = "<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>";
    var _is_load_partial = "<?= (isset($is_load_partial)) ? $is_load_partial : '0' ?>";

    $(document).ready(function() {

        // Initialize DataTables
        if (_is_load_partial === '0' && $(`#${_table}`)[0]) {
            if ($.fn.DataTable.isDataTable(`#${_table}`) === false) {
                var table = $(`#${_table}`).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo base_url('employeeexpired/ajax_get_all/') ?>",
                        type: "get"
                    },
                    columns: [
                        {
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
                            data: "nama_sub_unit"
                        },
                        {
                            data: "nama_jenis_pegawai"
                        },
                        {
                            data: "eoc",
                            render: function(data, type, row, meta) {
                                if (data != null) {
                                    return moment(data).format('DD-MM-YYYY');
                                };
                                return "-";
                            }
                        },
                        {
                            data: "nama_status_active",
                            render: function(data, type, row, meta) {
                                var statusColor = (data == 'Aktif') ? 'success' : 'danger';
                                return `<span class="badge badge-${statusColor}">${data}</span>`;
                            }
                        }
                        
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
                        handleCxFilter_setXlsx(_table);
                    },
                    createdRow: function(row, data, dataIndex) {
                        var current_date = new Date();
                        current_date.setHours(0, 0, 0, 0);
                        var eoc_date = new Date(data.eoc);
                        eoc_date.setHours(0, 0, 0, 0); 
                        if (eoc_date < current_date) {
                            $(row).addClass("table-row-red");
                        }
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
                        table.ajax.reload(null, false);
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
        $("#" + _table).DataTable().ajax.url("<?php echo base_url('employeeexpired/ajax_get_all') ?>" + params);
        $("#" + _table).DataTable().clear().draw();
    };

    function handleCxFilter_xlsx() {
        var params = handleCxFilter_getParams();
        var url = "<?php echo base_url('employeeexpired/xlsx') ?>" + params;
        window.location.href = url;
    }

</script>