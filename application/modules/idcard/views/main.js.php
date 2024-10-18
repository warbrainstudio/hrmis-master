<script type="text/javascript">
  $(document).ready(function() {
    var config = <?= (count(@$config) > 0) ? @$config : json_encode(array()) ?>;
    var activeElementKey = null;
    var draggable = $('.draggable');

    setTimeout(() => {
      initStyle();
    }, 50);

    // Handle draggable
    draggable.on('mousedown', function(e) {
      var dr = $(this).addClass("drag").css("cursor", "move").css("opacity", "0.5");
      var key = $(this).attr("data-key");

      height = dr.outerHeight();
      width = dr.outerWidth();
      max_left = dr.parent().offset().left + dr.parent().width() - dr.outerWidth();
      max_top = dr.parent().offset().top + dr.parent().height() - dr.outerHeight();
      min_left = dr.parent().offset().left;
      min_top = dr.parent().offset().top;
      ypos = dr.offset().top + height - e.pageY;
      xpos = dr.offset().left + width - e.pageX;

      $(document.body).on('mousemove', function(e) {
        var itop = e.pageY + ypos - height;
        var ileft = e.pageX + xpos - width;

        if (dr.hasClass("drag")) {
          if (itop <= min_top) {
            itop = min_top;
          }
          if (ileft <= min_left) {
            ileft = min_left;
          }
          if (itop >= max_top) {
            itop = max_top;
          }
          if (ileft >= max_left) {
            ileft = max_left;
          }
          dr.offset({
            top: itop,
            left: ileft
          });

          // Set config
          if (config[key]) {
            config[key]["top"] = dr.css("top");
            config[key]["left"] = dr.css("left");
          };
        };
      }).on('mouseup', function(e) {
        dr.removeClass("drag").css("opacity", "1");
      });
    });

    // Handle element click
    $(document).on("click", ".element-item", function() {
      var key = $(this).attr("data-key");
      var type = $(this).attr("data-type");
      var element = config[key];

      // Set active temp
      activeElementKey = key;

      $(".attribute-info").hide();
      $(".atrribute-label").hide();
      $(".atrribute-image").hide();
      $(".attribute-form").show();

      // Set attribute form visibility
      if (element.type === 'label') {
        $(".atrribute-label").show();
      } else if (element.type === 'image') {
        $(".atrribute-image").show();
      };

      // Set title
      $(".attribute-active").html(`(${element.text})`);

      // Set icon
      $(`.element-item-icon`).hide();
      $(`.element-item-icon-${key}`).show();

      // Load form
      if (element) {
        $.each(element, (index, value) => {
          if ($.inArray(index, ["font-style", "font-weight", "active"]) != -1) {
            $(`.attributeForm-${index}`).prop("checked", (value == 1) ? true : false);
          } else {
            $(`.attributeForm-${index}`).val(value).trigger("input").trigger("change");
          };
        });
        initStyle();
      };
    });

    // Handle attribute change
    $(document).on("keyup change click", ".attributeForm", function() {
      setTimeout(() => {
        var key = $(this).attr("data-key");
        var value = $(this).val();

        if ($.inArray(key, ["font-style", "font-weight", "active"]) != -1) {
          var isChecked = $(`.attributeForm-${key}`).prop("checked");
          value = (isChecked === true) ? 1 : 0;
        };

        config[activeElementKey][key] = value;
        initStyle();
      }, 500);
    });

    // Handle attribute change : background
    $(document).on("keyup change click", ".attributeFormBacgkround", function() {
      setTimeout(() => {
        var key = $(this).attr("data-key");
        var value = $(this).val();

        if (key !== 'src') {
          if ($.inArray(key, ["font-style", "font-weight", "active"]) != -1) {
            var isChecked = $(`.attributeFormBacgkround-${key}`).prop("checked");
            value = (isChecked === true) ? 1 : 0;
          };
          config['background'][key] = value;
        } else {
          if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
              if (e.target.result != "") {
                config['background'][key] = e.target.result;
                initStyle();
              };
            };
            reader.readAsDataURL(this.files[0]);
          };
        };
        initStyle();
      }, 500);
    });

    // Handle save
    $(document).on("click", ".action-save", function(e) {
      e.preventDefault();
      if (config) {
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
            $.ajax({
              type: "post",
              url: "<?php echo base_url('idcard/ajax_save/') ?>",
              data: {
                "config": config
              },
              dataType: "json",
              success: function(response) {
                if (response.status === true) {
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

    function initStyle() {
      if (config) {
        $.each(config, (key, element) => {
          if (element) {
            if (key === 'background') {
              $.each(element, (index, value) => {
                if (index === 'src') {
                  index = 'background-image';
                  value = (value.includes("data:image")) ? `url(${value})` : `url(${_baseUrl}${value})`;
                };
                $(`.draggable-area`).css(index, value);
              });
            } else {
              $.each(element, (index, value) => {
                if (element.active == 1) {
                  if (index === "font-style") {
                    value = (value == 1) ? 'italic' : '';
                  } else if (index === "font-weight") {
                    value = (value == 1) ? 'bold' : '';
                  };
                  $(`.draggable-${key}`).show();
                  $(`.draggable-${key}`).css(index, value);
                } else {
                  $(`.draggable-${key}`).hide();
                };
              });
            };
          };
        });
      };
    };
  });
</script>