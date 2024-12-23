<script type="text/javascript">
  $(document).ready(function() {

    var _key = "";
    var _section = "menuConfiguration";
    var _table = "table-menuConfiguration";
    var _modal = "modal-form-menuConfiguration";
    var _form = "form-menuConfiguration";

    // Initialize DataTables: Index
    if ($("#" + _table)[0]) {
      var table_menuConfiguration = $("#" + _table).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "<?php echo base_url('menuconfiguration/ajax_getall/') ?>",
          type: "get"
        },
        columns: [{
            data: null,
            render: function(data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          },
          {
            data: "parent_name"
          },
          {
            data: "name"
          },
          {
            data: "link",
            render: function(data, type, row, meta) {
              return (data.length >= 40) ? `<span title="${data}">${data.substring(0, 40).trim()}...</span>` : data;
            }
          },
          {
            data: "role_pic",
            render: function(data, type, row, meta) {
              const temp = JSON.parse(data);
              const itemCount = temp.length;
              const maxItem = 2;
              const itemSisa = itemCount - maxItem;
              var tempData = "";

              $(temp).each(function(index, item) {
                if (index < maxItem) {
                  tempData += `<span class="badge badge-secondary mr-1 mb-1">${item}</span>`;
                };
              });

              if (itemCount > maxItem) {
                tempData += `<span class="badge badge-warning mr-1 mb-1">${itemSisa}+ more</span>`;
              };

              return tempData;
            }
          },
          {
            data: "is_newtab",
            render: function(data, type, row, meta) {
              return (data == 1) ? "Yes" : "No";
            }
          },
          {
            data: "order_pos"
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
            // display: $.fn.dataTable.Responsive.display.modal(),
            renderer: $.fn.dataTable.Responsive.renderer.tableAll({
              tableClass: "table dt-details"
            }),
            type: "inline",
            target: 'tr',
          }
        },
        columnDefs: [{
          targets: [1],
          visible: false
        }, {
          className: 'desktop',
          targets: [0, 2, 3, 4, 5]
        }, {
          className: 'tablet',
          targets: [0, 2, 4, 5]
        }, {
          className: 'mobile',
          targets: [0, 2, 5]
        }, {
          responsivePriority: 1,
          targets: 0
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
            '<div class="dataTables_buttons hidden-sm-down actions" style="display: flex; align-items: center;">' +
            '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" style="padding: 0px 5px; cursor: pointer;" />' +
            '</div>'
          );

          // Handle referensi data
          ref_parentId();
        },
        drawCallback: function(settings) {
          var api = this.api();
          var rows = api.rows({
            page: 'current'
          }).nodes();
          var last = null;

          api.column(1, {
            page: 'current'
          }).data().each(function(group, i) {
            group = (group != null) ? group : "(Has no parent)";
            if (last !== group) {
              $(rows).eq(i).before(
                '<tr class="group"><td colspan="11">' + group + '</td></tr>'
              );

              last = group;
            }
          });
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

    // Handle data add
    $("#" + _section).on("click", "button.menu-action-add", function(e) {
      e.preventDefault();
      resetForm();
    });

    // Handle data edit
    $("#" + _table).on("click", "a.action-edit", function(e) {
      e.preventDefault();
      var temp = table_menuConfiguration.row($(this).closest('tr')).data();
      var parent_id = (temp.parent_id !== null) ? temp.parent_id : 0;

      // Set key for update params, important!
      _key = temp.id;

      $("#" + _form + " .menu-parent_id").val(parent_id);
      $("#" + _form + " .menu-name").val(temp.name);
      $("#" + _form + " .menu-link").val(temp.link);
      $("#" + _form + " .menu-icon").val(temp.icon);
      $("#" + _form + " .option-is_newtab").attr("class", "btn option-is_newtab");
      $("#" + _form + " input[name=is_newtab][value=" + temp.is_newtab + "]").prop('checked', true);
      $("#" + _form + " input[name=is_newtab][value=" + temp.is_newtab + "]").closest("label").attr("class", "btn option-is_newtab active");
      $("#" + _form + " .menu-order_pos").val(temp.order_pos);
      $("#" + _form + " .menu-role_pic").val(JSON.parse(temp.role_pic)).change();

      // Handle link
      if (temp.link_tobase == "1") {
        $("#" + _form + " .menu-link_tobase").attr("checked", true);
        $("#" + _form + " .menu-data-link_tobase").show();
      } else {
        $("#" + _form + " .menu-link_tobase").removeAttr("checked");
        $("#" + _form + " .menu-data-link_tobase").hide();
      };
    });

    // Handle data delete
    $("#" + _table).on("click", "a.action-delete", function(e) {
      e.preventDefault();
      var temp = table_menuConfiguration.row($(this).closest('tr')).data();

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
            type: "GET",
            url: "<?php echo base_url('menuconfiguration/ajax_delete/') ?>" + temp.id,
            dataType: "json",
            success: function(response) {
              if (response.status) {
                $("#" + _table).DataTable().ajax.reload(null, false);
                ref_parentId();
                notify(response.data, "success");
              } else {
                notify(response.data, "danger");
              };
            }
          });
        };
      });
    });

    // Handle data submit
    $("#" + _modal + " .menu-action-save").on("click", function(e) {
      e.preventDefault();
      $.ajax({
        type: "post",
        url: "<?php echo base_url('menuconfiguration/ajax_save/') ?>" + _key,
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

    // Handle link toggle
    $("#" + _form + " .menu-link_tobase").on("click", function() {
      $(".menu-data-link_tobase").toggle();
    });

    // Handle form reset
    resetForm = () => {
      _key = "";
      $("#" + _form).trigger("reset");
      $("#" + _form + " .menu-link_tobase").attr("checked", true);
      $("#" + _form + " .menu-data-link_tobase").show();
      $("#" + _form + " .menu-role_pic").val(["Administrator"]).change();
      ref_parentId();
    };

    // Handle referensi: parent_id
    ref_parentId = () => {
      $.ajax({
        type: "get",
        url: "<?php echo base_url('menuconfiguration/ajax_getrefall/') ?>",
        success: function(response) {
          var response = JSON.parse(response);
          if (response.data.length > 0) {
            var element = '<option value="0">(Empty)</option>';
            $.each(response.data, function(key, item) {
              element += '<option value="' + item.id + '">' + item.name + '</option>';
            });
            $(".menu-parent_id").html(element);
          };
        }
      });
    };

  });
</script>