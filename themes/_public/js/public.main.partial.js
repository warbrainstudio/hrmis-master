$(document).ajaxStart(function () {
	$(".spinner").css("display", "flex");
	$(".spinner-action").css("display", "inline-block");
	$(".spinner-action-button").attr("disabled", true);
});

$(document).ajaxStop(function () {
	$(".spinner").css("display", "none");
	$(".spinner-action").css("display", "none");
	$(".spinner-action-button").removeAttr("disabled");
});

$(document).ajaxError(function (event, request, settings) {
	$(".spinner").css("display", "none");
	$(".spinner-action").css("display", "none");
	$(".spinner-action-button").removeAttr("disabled");

	swal({
		title: "Error " + request.status,
		text: "An error occurred while processing the request.",
		type: "error",
		showCancelButton: false,
		confirmButtonColor: "#39bbb0",
		confirmButtonText: "OK",
		closeOnConfirm: false,
	});
});

// Initialize select2
if ($("select.select2-partial")[0]) {
	var a = $(".select2-parent")[0] ? $(".select2-parent") : $("body");
	if ($("select.select2-partial").hasClass("select2-hidden-accessible")) {
		$("select.select2-partial").select2("destroy");
	};
	$("select.select2-partial").select2({
		dropdownAutoWidth: !0,
		width: "100%",
		dropdownParent: a,
	});
}
if ($("select.select2-partial-desc")[0]) {
	var a = $(".select2-parent")[0] ? $(".select2-parent") : $("body");
	$("select.select2-partial-desc")
		.select2({
			dropdownAutoWidth: !0,
			width: "100%",
			dropdownParent: a,
			templateResult: function (opt) {
				var optdesc = $(opt.element).attr("data-desc");
				var $opt = $("<div>" + optdesc + "</div>");
				return $opt;
			},
		})
		.on("select2:open", function (e) {
			var search_placeholder = $(this).attr("search-placeholder");
			$(".select2-search__field").attr("placeholder", search_placeholder);
		});
}

function notify(nMessage, nType) {
	$.notify(
		{ message: nMessage },
		{
			type: nType,
			z_index: 999992,
			delay: 800,
			timer: 2000,
			placement: {
				from: "top",
				align: "center",
			},
			template:
				'<div data-notify="container" class="alert alert-dismissible alert-{0} alert--notify" role="alert">' +
				'<span data-notify="message">{2}</span>' +
				'<button type="button" aria-hidden="true" data-notify="dismiss" class="alert--notify__close bg-light rounded text-dark p-1">&nbsp;X&nbsp;</button>' +
				"</div>",
		}
	);
}

function readUploadURL(input) {
	$(".upload .upload-preview").html("");

	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			if (e.target.result != "") {
				var imageSource = "<img src='" + e.target.result + "'/>";
				$(".upload .upload-preview").html(imageSource);
			}
		};
		reader.readAsDataURL(input.files[0]);
	}
}

function readUploadMultipleURL(input) {
	var key = $(input).attr("data-preview");
	$(".upload .preview-" + key).html("");

	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			if (e.target.result != "") {
				var imageSource = "<img src='" + e.target.result + "'/>";
				$(".upload .preview-" + key).html(imageSource);
			}
		};
		reader.readAsDataURL(input.files[0]);
	}
}

function readUploadInlineURL(input) {
	$(".upload-inline .upload-preview").html("");

	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			if (e.target.result != "") {
				var imageSource = "<img src='" + e.target.result + "'/>";
				$(".upload-inline .upload-preview").html(imageSource);
			}
		};
		reader.readAsDataURL(input.files[0]);
	}
}

function readUploadInlineDocURL(input) {
	$(".upload-inline .upload-preview").html("No file chosen");

	if (input.files && input.files[0]) {
		var file = input.files[0];
		var fileInfo = file.name + "<br />" + formatBytes(file.size);
		$(".upload-inline .upload-preview").html(fileInfo);
	}
}

function readUploadInlineDocURLXs(input) {
	$(".upload-inline-xs .upload-preview").html("No file chosen");

	if (input.files && input.files[0]) {
		var file = input.files[0];
		var fileInfo = file.name + "<br />" + formatBytes(file.size);
		$(".upload-inline-xs .upload-preview").html(fileInfo);
	}
}

function readUploadMultipleDocURLXs(input) {
	var key = $(input).attr("data-preview");

	$(".upload-inline-xs .data-preview-" + key).html("No file chosen");

	if (input.files && input.files[0]) {
		var file = input.files[0];
		var fileInfo = file.name + "<br />" + formatBytes(file.size);
		$(".upload-inline-xs .data-preview-" + key).html(fileInfo);
	}
}

function readUploadSimple(input, preview) {
	if (!preview) return false;

	$(preview).html("No file chosen");

	if (input.files && input.files[0]) {
		var file = input.files[0];
		var fileInfo = file.name + " (" + formatBytes(file.size) + ")";
		$(preview).html(fileInfo);
	};
}

// Initialize TinyMce
tinymce.init({
	selector: "textarea.tinymce-init-partial",
	plugins:
		"print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons",
	imagetools_cors_hosts: ["picsum.photos"],
	menubar: "file edit view insert format tools table tc",
	toolbar:
		"undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl",
	// autosave_ask_before_unload: true,
	autosave_interval: "30s",
	autosave_prefix: "{path}{query}-{id}-",
	autosave_restore_when_empty: false,
	autosave_retention: "2m",
	image_advtab: true,
	content_css: [
		"//fonts.googleapis.com/css?family=Lato:300,300i,400,400i",
		"//www.tiny.cloud/css/codepen.min.css",
	],
	importcss_append: true,
	template_cdate_format: "[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]",
	template_mdate_format: "[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]",
	height:
		$(".tinymce-init-partial").attr("data-height") !== undefined
			? parseInt($(".tinymce-init-partial").attr("data-height"))
			: 600,
	image_caption: true,
	quickbars_selection_toolbar:
		"bold italic | quicklink h2 h3 blockquote quickimage quicktable",
	noneditable_noneditable_class: "mceNonEditable",
	toolbar_drawer: "sliding",
	contextmenu: "link image imagetools table",
});

// Intialize MaskInput
$(".mask-partial-money").mask("#,##0", { reverse: true });
$(".mask-partial-money-decimal").inputmask({
	alias: "decimal",
	rightAlign: false,
	groupSeparator: ".",
	autoGroup: true,
});
$(".mask-partial-email").mask("A", {
	translation: {
		A: { pattern: /[\w@\.]/, recursive: true },
	},
});
$(".mask-partial-age").mask("00", { reverse: true });
$(".mask-partial-number").mask("#0", { reverse: true });
$(".mask-partial-slug").mask("A", {
	translation: {
		A: { pattern: /[\w-\/]/, recursive: true },
	},
});
$(".mask-partial-date").mask("9999-99-99", { placeholder: "YYYY-MM-DD" });
$(".mask-partial-date-id").mask("99-99-9999", { placeholder: "DD-MM-YYYY" });
$(".mask-partial-percent").mask("00,00", {
	reverse: true,
	placeholder: "__,__",
});

// Handle percentage input
$(document).on("keyup", ".handle-percent", function () {
	$(this).val(function (index, old) {
		var cur = old.replace(/[^0-9.]/g, "");
		if (cur > 100) {
			notify("Persentase tidak boleh lebih besar dari 100", "danger");
			cur = 0;
		}
		return cur;
	});
});

// Initialize Flatpickr
$(".flatpickr-partial-date").flatpickr({
	allowInput: true,
});
$(".flatpickr-partial-date-id").flatpickr({
	allowInput: true,
	dateFormat: "d-m-Y",
});
$(".flatpickr-partial-datemax-today").flatpickr({
	allowInput: true,
	dateFormat: "Y-m-d",
	maxDate: "today",
});
$(".flatpickr-partial-datemax-today-id").flatpickr({
	allowInput: true,
	dateFormat: "d-m-Y",
	maxDate: "today",
});
$(".flatpickr-partial-datetime").flatpickr({
	enableTime: true,
	dateFormat: "Y-m-d H:i",
});
$(".flatpickr-partial-datetime-id").flatpickr({
	enableTime: true,
	dateFormat: "d-m-Y H:i",
});
$(".flatpickr-partial-datetime-24").flatpickr({
	enableTime: true,
	dateFormat: "Y-m-d H:i:S",
	time_24hr: true,
});
$(".flatpickr-partial-datetime-24-id").flatpickr({
	enableTime: true,
	dateFormat: "d-m-Y H:i:S",
	time_24hr: true,
});
$(".flatpickr-partial-timeonly").flatpickr({
	enableTime: true,
	noCalendar: true,
	dateFormat: "H:i:S",
	time_24hr: true,
});
$(".flatpickr-partial-date-range").flatpickr({
	allowInput: false,
	dateFormat: "Y-m-d",
	mode: "range",
	locale: { rangeSeparator: " s/d " },
	onChange: function (dates) {
		if (dates.length == 2) {
			var prefix = $(this)[0].input.attributes["data-prefix"]
				? $(this)[0].input.attributes["data-prefix"].value
				: null;
			prefix = prefix != null ? prefix : "date_range";

			window[prefix + "_start"] = moment(dates[0]).format("YYYY-MM-DD");
			window[prefix + "_end"] = moment(dates[1]).format("YYYY-MM-DD");

			$("." + prefix + "_start").val(window[prefix + "_start"]);
			$("." + prefix + "_end").val(window[prefix + "_end"]);
		}
	},
});

// Initialize Select2

// File size format
function formatBytes(bytes, decimals = 2) {
	if (bytes === 0) return "0 Bytes";

	const k = 1024;
	const dm = decimals < 0 ? 0 : decimals;
	const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

	const i = Math.floor(Math.log(bytes) / Math.log(k));

	return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
}

// Handle visibility password event
$(".visibility-password")
	.html('<i class="zmdi zmdi-eye"></i>')
	.css("width", "30px");
$(".visibility-password").on("click", function () {
	var obj = $(this);
	var input = obj.attr("data-input");

	if (obj.html() === '<i class="zmdi zmdi-eye"></i>') {
		$(input).prop("type", "text");
		obj.html('<i class="zmdi zmdi-eye-off"></i>');
	} else {
		$(input).prop("type", "password");
		obj.html('<i class="zmdi zmdi-eye"></i>');
	}
});

// Get current data
function getCurrentDate() {
	var d = new Date();

	var month = d.getMonth() + 1;
	var day = d.getDate();

	var output =
		d.getFullYear() +
		"-" +
		(month < 10 ? "0" : "") +
		month +
		"-" +
		(day < 10 ? "0" : "") +
		day;

	return output;
}

// Handle input number max-length
$(document).on("keypress", "input[type='number']", function () {
	var maxLength = $(this).attr("maxlength");

	if (typeof maxLength !== "undefined") {
		if ($(this).val().length == maxLength) return false;
	}

	return true;
});

// Initialize responsive tabs
$(".nav-responsive:first").responsiveTabs();
$(".nav-responsive-2").responsiveTabs();
$(".nav-responsive-3:first").responsiveTabs();
$(".nav-responsive-4:first").responsiveTabs();
$(".nav-responsive-5:first").responsiveTabs();
$(".nav-responsive-6:first").responsiveTabs();
$(".nav-responsive-7:first").responsiveTabs();
$(".nav-responsive-8:first").responsiveTabs();
$(".nav-responsive-9:first").responsiveTabs();
$(".nav-responsive-10:first").responsiveTabs();

// Initailize timeline
(function () {
	// Vertical Timeline - by CodyHouse.co
	function VerticalTimeline(element) {
		this.element = element;
		this.blocks = this.element.getElementsByClassName("cd-timeline__block");
		this.images = this.element.getElementsByClassName("cd-timeline__img");
		this.contents = this.element.getElementsByClassName("cd-timeline__content");
		this.offset = 0.8;
		this.hideBlocks();
	}

	VerticalTimeline.prototype.hideBlocks = function () {
		if (!"classList" in document.documentElement) {
			return; // no animation on older browsers
		}
		//hide timeline blocks which are outside the viewport
		var self = this;
		for (var i = 0; i < this.blocks.length; i++) {
			(function (i) {
				if (
					self.blocks[i].getBoundingClientRect().top >
					window.innerHeight * self.offset
				) {
					self.images[i].classList.add("cd-timeline__img--hidden");
					self.contents[i].classList.add("cd-timeline__content--hidden");
				}
			})(i);
		}
	};

	VerticalTimeline.prototype.showBlocks = function () {
		if (!"classList" in document.documentElement) {
			return;
		}
		var self = this;
		for (var i = 0; i < this.blocks.length; i++) {
			(function (i) {
				if (
					self.contents[i].classList.contains("cd-timeline__content--hidden") &&
					self.blocks[i].getBoundingClientRect().top <=
						window.innerHeight * self.offset
				) {
					// add bounce-in animation
					self.images[i].classList.add("cd-timeline__img--bounce-in");
					self.contents[i].classList.add("cd-timeline__content--bounce-in");
					self.images[i].classList.remove("cd-timeline__img--hidden");
					self.contents[i].classList.remove("cd-timeline__content--hidden");
				}
			})(i);
		}
	};

	var verticalTimelines = document.getElementsByClassName("js-cd-timeline"),
		verticalTimelinesArray = [],
		scrolling = false;
	if (verticalTimelines.length > 0) {
		for (var i = 0; i < verticalTimelines.length; i++) {
			(function (i) {
				verticalTimelinesArray.push(new VerticalTimeline(verticalTimelines[i]));
			})(i);
		}

		//show timeline blocks on scrolling
		window.addEventListener("scroll", function (event) {
			if (!scrolling) {
				scrolling = true;
				!window.requestAnimationFrame
					? setTimeout(checkTimelineScroll, 250)
					: window.requestAnimationFrame(checkTimelineScroll);
			}
		});
	}

	function checkTimelineScroll() {
		verticalTimelinesArray.forEach(function (timeline) {
			timeline.showBlocks();
		});
		scrolling = false;
	}
})();

function getMonthNameByNum(bulanNum) {
	switch (parseInt(bulanNum)) {
		case 1:
			return "Januari";
		case 2:
			return "Februari";
		case 3:
			return "Maret";
		case 4:
			return "April";
		case 5:
			return "Mei";
		case 6:
			return "Juni";
		case 7:
			return "Juli";
		case 8:
			return "Agustus";
		case 9:
			return "September";
		case 10:
			return "Oktober";
		case 11:
			return "November";
		case 12:
			return "Desember";
		default:
			return "Undefined";
	}
}

function normalizeDateToLocal(date) {
	const extractDate = (string) =>
		(([year, month, day]) => ({ day, month, year }))(string.split("-"));
	var objDate = extractDate(date);
	var monthName = getMonthNameByNum(objDate.month);
	var result = objDate.day + " " + monthName + " " + objDate.year;

	return result;
}

function initSelect2_enter(cmp, placeholder = "Cari...", url, templateResult) {
	var select2Option = {
		placeholder: placeholder,
		language: {
			noResults: function () {
				return "Please input a keyword and press Enter";
			},
		},
		templateResult: templateResult,
		allowClear: true,
	};

	// Initialize component
	$(cmp).select2(select2Option);
	$(cmp).empty();
	$(cmp).val(null).trigger("change");

	// Trigger search
	$(document).on(
		"keyup",
		".select2-container .select2-search__field",
		function (e) {
			if (e.key === "Enter" || e.keyCode === 13) {
				if ($(cmp).length > 0) {
					var keyword = $(cmp).data("select2").dropdown.$search.val();
					if (keyword.trim() != "") {
						$(cmp).select2("close");
						$.ajax({
							url: `${url}?q=${keyword}`,
							dataType: "json",
							cache: false,
						}).then(function (response) {
							if (response.items) {
								var data = response.items;
								select2Option["data"] = data;
								$(cmp).empty();
								$(cmp).select2("destroy").select2(select2Option);
								$(cmp).val(null).trigger("change");
								$(cmp).select2("open");
								return false; // Break if data exists
							}
							// Default action when data is not exists
							$(cmp).empty();
							$(cmp).val(null).trigger("change");
							$(cmp).select2("open");
							notify(`No results found for "${keyword}"`, "warning");
						});
					}
				}
			}
		}
	);

	$(cmp).on("select2:unselecting", function (e) {
		$(cmp).empty();
		$(cmp).val(null).trigger("change");
		$(cmp).select2("open");
	});
}

function handleCxFilter_getParams() {
	var params = "?1=1";

	// Get component
	const cxFilter__tanggal = $("[name='cx_filter[tanggal]']");
	const cxFilter__no_po = $("[name='cx_filter[no_po]']");
	const cxFilter__tanggal_po = $("[name='cx_filter[tanggal_po]']");
	const cxFilter__no_faktur = $("[name='cx_filter[no_faktur]']");
	const cxFilter__tanggal_faktur = $("[name='cx_filter[tanggal_faktur]']");
	const cxFilter__no_mutasi = $("[name='cx_filter[no_mutasi]']");
	const cxFilter__tanggal_mutasi = $("[name='cx_filter[tanggal_mutasi]']");
	const cxfilter__tanggal_terima = $("[name='cx_filter[tanggal_terima]']");
	const cxFilter__supplier = $("[name='cx_filter[supplier]']");
	const cxFilter__cara_bayar = $("[name='cx_filter[cara_bayar]']");
	const cxFilter__jenis_barang = $("[name='cx_filter[jenis_barang]']");
	const cxFilter__tujuan = $("[name='cx_filter[tujuan]']");
	const cxFilter__sumber = $("[name='cx_filter[sumber]']");

	if (cxFilter__tanggal.length > 0) {
		var cxFilter__tanggal_start = $("[name='cx_filter[tanggal_start]']").val();
		var cxFilter__tanggal_end = $("[name='cx_filter[tanggal_end]']").val();
		params += `&cxfilter_tanggal_start=${cxFilter__tanggal_start}&cxfilter_tanggal_end=${cxFilter__tanggal_end}`;
	}
	if (cxFilter__no_po.length > 0) {
		params += `&cxfilter_no_po=${cxFilter__no_po.val()}`;
	}
	if (cxFilter__tanggal_po.length > 0) {
		var cxFilter__tangal_po_start = $(
			"[name='cx_filter[tanggal_po_start]']"
		).val();
		var cxFilter__tangal_po_end = $("[name='cx_filter[tanggal_po_end]']").val();
		params += `&cxfilter_tanggal_po_start=${cxFilter__tangal_po_start}&cxfilter_tanggal_po_end=${cxFilter__tangal_po_end}`;
	}
	if (cxFilter__no_faktur.length > 0) {
		params += `&cxfilter_no_faktur=${cxFilter__no_faktur.val()}`;
	}
	if (cxFilter__tanggal_faktur.length > 0) {
		var cxFilter__tangal_faktur_start = $(
			"[name='cx_filter[tanggal_faktur_start]']"
		).val();
		var cxFilter__tangal_faktur_end = $(
			"[name='cx_filter[tanggal_faktur_end]']"
		).val();
		params += `&cxfilter_tanggal_faktur_start=${cxFilter__tangal_faktur_start}&cxfilter_tanggal_faktur_end=${cxFilter__tangal_faktur_end}`;
	}
	if (cxFilter__no_mutasi.length > 0) {
		params += `&cxfilter_no_mutasi=${cxFilter__no_mutasi.val()}`;
	}
	if (cxFilter__tanggal_mutasi.length > 0) {
		var cxFilter__tangal_mutasi_start = $(
			"[name='cx_filter[tanggal_mutasi_start]']"
		).val();
		var cxFilter__tangal_mutasi_end = $(
			"[name='cx_filter[tanggal_mutasi_end]']"
		).val();
		params += `&cxfilter_tanggal_mutasi_start=${cxFilter__tangal_mutasi_start}&cxfilter_tanggal_mutasi_end=${cxFilter__tangal_mutasi_end}`;
	}
	if (cxfilter__tanggal_terima.length > 0) {
		var cxFilter__tangal_terima_start = $(
			"[name='cx_filter[tanggal_terima_start]']"
		).val();
		var cxFilter__tangal_terima_end = $(
			"[name='cx_filter[tanggal_terima_end]']"
		).val();
		params += `&cxfilter_tanggal_terima_start=${cxFilter__tangal_terima_start}&cxfilter_tanggal_terima_end=${cxFilter__tangal_terima_end}`;
	}
	if (cxFilter__supplier.length > 0) {
		params += `&cxfilter_supplier=${cxFilter__supplier.val()}`;
	}
	if (cxFilter__cara_bayar.length > 0) {
		params += `&cxfilter_cara_bayar=${cxFilter__cara_bayar.val()}`;
	}
	if (cxFilter__jenis_barang.length > 0) {
		params += `&cxfilter_jenis_barang=${cxFilter__jenis_barang.val()}`;
	}
	if (cxFilter__tujuan.length > 0) {
		params += `&cxfilter_tujuan=${cxFilter__tujuan.val()}`;
	}
	if (cxFilter__sumber.length > 0) {
		params += `&cxfilter_sumber=${cxFilter__sumber.val()}`;
	}

	return params;
}

function handleCxFilter_setXlsx(dataTableName) {
	var dataCount = $("#" + dataTableName)
		.DataTable()
		.data()
		.count();
	if (dataCount > 0) {
		$(".page-action-xlsx").fadeIn("fast");
	} else {
		$(".page-action-xlsx").fadeOut("fast");
	}
}

function handleButtonGroup_setVal(index, prefix, value) {
	var pureIndex = index.split("_");
	var buttons = $(".cx--button-group-option-" + pureIndex[1] + " a");
	var activeButton = $(".btn--" + prefix + "-" + index);
	var input = $(".input--" + prefix + "-" + pureIndex[1]);

	buttons.removeClass("btn-light btn-warning").addClass("btn-light");
	activeButton.removeClass("btn-light").addClass("btn-warning");
	input.val(value).trigger("input");
}

// Handle cxFilter onChange
$(document).on(
	"change keyup",
	"[name='cx_filter[no_po]'],[name='cx_filter[tanggal_po]'],[name='cx_filter[no_faktur]'],[name='cx_filter[tanggal_faktur]'],[name='cx_filter[tanggal_terima]']",
	function () {
		$(".page-action-xlsx").hide();
	}
);
$(document).on(
	"change keyup",
	"[name='cx_filter[tanggal]'],[name='cx_filter[supplier]'],[name='cx_filter[cara_bayar]'],[name='cx_filter[jenis_barang]'],[name='cx_filter[sumber]']",
	function () {
		$(".page-action-xlsx").hide();
	}
);
$(document).on(
	"change keyup",
	"[name='cx_filter[no_mutasi]'],[name='cx_filter[tanggal_mutasi]'],[name='cx_filter[tujuan]']",
	function () {
		$(".page-action-xlsx").hide();
	}
);

// Init cxFilter::Tujuan select2 autocomplete
$("[name='cx_filter[tujuan]").select2({
	ajax: {
		url: _baseUrl + "ref/ajax_get_tujuan", // ToDo: add variable _baseUrl in your javascript controller
		dataType: "json",
		delay: 500,
		data: function (params) {
			return {
				key: "id",
				q: params.term,
			};
		},
		processResults: function (data, params) {
			return {
				results: data.items,
			};
		},
		cache: true,
	},
	placeholder: "Cari tujuan...",
	minimumInputLength: 2,
});

if ($.fn.dataTable) {
	/* Create an array with the values of all the input boxes in a column */
	$.fn.dataTable.ext.order["dom-text"] = function (settings, col) {
		return this.api()
			.column(col, { order: "index" })
			.nodes()
			.map(function (td, i) {
				return $("input", td).val();
			});
	};
	
	/* Create an array with the values of all the input boxes in a column, parsed as numbers */
	$.fn.dataTable.ext.order["dom-text-numeric"] = function (settings, col) {
		return this.api()
			.column(col, { order: "index" })
			.nodes()
			.map(function (td, i) {
				return $("input", td).val() * 1;
			});
	};
	
	/* Create an array with the values of all the select options in a column */
	$.fn.dataTable.ext.order["dom-select"] = function (settings, col) {
		return this.api()
			.column(col, { order: "index" })
			.nodes()
			.map(function (td, i) {
				return $("select", td).val();
			});
	};
	
	/* Create an array with the values of all the checkboxes in a column */
	$.fn.dataTable.ext.order["dom-checkbox"] = function (settings, col) {
		return this.api()
			.column(col, { order: "index" })
			.nodes()
			.map(function (td, i) {
				return $("input", td).prop("checked") ? "1" : "0";
			});
	};
};

function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + "").replace(",", "").replace(" ", "");
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
		dec = typeof dec_point === "undefined" ? "." : dec_point,
		s = "",
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return "" + Math.round(n * k) / k;
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || "").length < prec) {
		s[1] = s[1] || "";
		s[1] += new Array(prec - s[1].length + 1).join("0");
	}
	return s.join(dec);
};

function showBodyLoading() {
	$(document).find(".body-loading").fadeOut("fast", function () {
		$(this).show();
	});
	$(document).find(".cx__filter-submit").attr("disabled", true);
};

function hideBodyLoading() {
	$(document).find(".body-loading").fadeOut("fast", function () {
		$(this).hide();
		document.body.style.overflow = "auto";
	});
	$(document).find(".cx__filter-submit").removeAttr("disabled");
};

function resizeIframe(obj) {
	setTimeout(() => {
		$(obj).css("height", 0);
		$(obj).css("height", $(obj).contents().height());
	}, 500);
};
