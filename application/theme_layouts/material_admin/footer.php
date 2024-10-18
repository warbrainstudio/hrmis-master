</main>

<!-- Javascript -->
<script type="text/javascript">
	var _baseUrl = "<?= base_url() ?>";
</script>

<!-- Vendors -->
<script src="<?php echo base_url('themes/material_admin/vendors/jquery/jquery.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/popper.js/popper.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/jquery-scrollLock/jquery-scrollLock.min.js') ?>"></script>

<script src="<?php echo base_url('themes/material_admin/vendors/flot/jquery.flot.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/flot/jquery.flot.resize.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/flot.curvedlines/curvedLines.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/jqvmap/jquery.vmap.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/jqvmap/maps/jquery.vmap.world.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/easy-pie-chart/jquery.easypiechart.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/salvattore/salvattore.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/sparkline/jquery.sparkline.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/moment/moment.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/fullcalendar/fullcalendar.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/tinymce/tinymce.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/autosize/autosize.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/jquery-text-counter/textcounter.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/flatpickr/flatpickr.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/jquery-mask-plugin/jquery.mask.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/select2/js/select2.full.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/nouislider/nouislider.min.js') ?>"></script>

<!-- Vendors: Data tables -->
<script src="<?php echo base_url('themes/material_admin/vendors/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/datatables/dataTables.rowGroup.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/datatables-buttons/dataTables.buttons.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/datatables-buttons/buttons.print.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/jszip/jszip.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/datatables-buttons/buttons.html5.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/datatables/dataTables.rowReorder.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/datatables/dataTables.responsive.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/vendors/lightgallery/js/lightgallery-all.min.js') ?>"></script>

<!-- Charts and maps-->
<script src="<?php echo base_url('themes/material_admin/demo/js/flot-charts/curved-line.js') ?>"></script>
<!-- <script src="<?php echo base_url('themes/material_admin/demo/js/flot-charts/dynamic.js') ?>"></script> -->
<script src="<?php echo base_url('themes/material_admin/demo/js/flot-charts/line.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/demo/js/flot-charts/chart-tooltips.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/demo/js/other-charts.js') ?>"></script>
<script src="<?php echo base_url('themes/material_admin/demo/js/jqvmap.js') ?>"></script>

<!-- Webcodecamjs -->
<script src="<?php echo base_url('themes/_public/vendors/webcodecamjs/js/qrcodelib.js') ?>"></script>
<script src="<?php echo base_url('themes/_public/vendors/webcodecamjs/js/webcodecamjquery.js') ?>"></script>

<!-- PrintThis -->
<script src="<?php echo base_url('themes/_public/js/printThis.js') ?>"></script>

<!-- html2canvas -->
<script src="<?php echo base_url('themes/_public/js/html2canvas.js') ?>"></script>

<!-- Responsive Tabs -->
<script src="<?php echo base_url('themes/_public/vendors/responsive-tabs/js/responsive-tabs.js') ?>"></script>

<!-- Fanyxboc -->
<script src="<?php echo base_url('themes/_public/vendors/fancybox/jquery.fancybox.min.js') ?>"></script>

<!-- FileDownload -->
<script src="<?php echo base_url('themes/_public/js/fileDownload.js') ?>"></script>

<!-- MD5 -->
<script src="<?php echo base_url('themes/_public/js/jquery.md5.js') ?>"></script>

<!-- App functions and actions -->
<script src="<?php echo base_url('themes/material_admin/js/app.min.js') ?>"></script>
<script src="<?php echo base_url('themes/_public/js/material-effect.js') ?>"></script>
<script src="<?php echo base_url('themes/_public/js/public.main.js') ?>"></script>

<!-- Handle javascript disabled -->
<noscript>
	<style type="text/css">
		.main {
			display: none;
		}

		body {
			background-color: rgba(255, 107, 104, 0.3) !important;
		}

		.no-script {
			display: flex;
			flex: 1;
			flex-direction: column;
			text-align: center;
			justify-content: center;
			align-items: center;
			z-index: 9999999999;
			margin-top: 10%;
			margin: 15px 15px;
		}

		.no-script .zmdi {
			font-size: 4rem;
		}

		.no-script p {
			margin: 0;
			font-size: 1.1rem;
		}

		.no-script hr {
			border: 1px solid rgba(255, 255, 255, 0.3);
		}
	</style>
	<div class="no-script">
		<div class="alert alert-danger">
			<i class="zmdi zmdi-language-javascript"></i>
			<p>You cannot continue any activity on this page.</p>
			<hr />
			<p>Please activate javascript in your browser first, and then reload this page!</p>
			<p>If you don't know, please contact the administrator.</p>
		</div>
	</div>
</noscript>

<?php echo (isset($main_js)) ?  $main_js : '' ?>

<script type="text/javascript">
	checkNotification();

	function getTimeAgo(time) {
		return moment(time).fromNow();
	};

	// Notification
	$(".notif-time-ago").each(function(i, obj) {
		var val = $(obj).html();
		$(obj).html(getTimeAgo(val));
	});

	function checkNotification() {
		$.ajax({
			type: "get",
			url: "<?php echo base_url('notification/last/1') ?>",
			dataType: "json",
			success: function(response) {
				var lists = '';

				if (response.count_unread > 0) {
					$("#app-notification-flag").addClass("top-nav__notify");
				} else {
					$("#app-notification-flag").removeClass("top-nav__notify");
				};

				if (response.count > 0) {
					jQuery.each(response.data, function(index, item) {
						var isRead = (item.is_read == "0") ? 'font-weight: bold;' : '';
						var timeAgo = getTimeAgo(item.created_date);
						lists += '<a href="<?php echo base_url('notification/read/') ?>' + item.id + '" class="listview__item" style="padding: .50rem 1rem;">' +
							'<img src="<?php echo base_url('/themes/_public/img/avatar/male-1.png') ?>" class="listview__img">' +
							'<div class="listview__content">' +
							'<p style="white-space: normal; line-break: anywhere; ' + isRead + '">' + item.description + '</p>' +
							'<small style="color: #777;">' + timeAgo + '</small>' +
							'</div>' +
							'</a>';
					});
					$("#app-notification-data").html(lists);
				} else {
					$("#app-notification-data").html("<div style='padding: 1rem;'>You have no notification</div>");
				};
			}
		});
	};
	// END ## Notification

	// Search
	$("#form-app-search").on("submit", function(e) {
		e.preventDefault();
		var keyword = $(".app-search-keyword").val();
		if (keyword.trim() != "") {
			window.location = "<?php echo base_url('search/?q=') ?>" + keyword;
		};
	});
	// END ## Search

	// Handle CSRF serialize
	var csfrData = {};
	csfrData["<?= $this->security->get_csrf_token_name() ?>"] = "<?= $this->security->get_csrf_hash() ?>";
	$.ajaxSetup({
		data: csfrData
	});

	// Handle CSRF form-data
	$.ajaxPrefilter(function(options, originalOptions, jqXHR) {
		if (originalOptions.data instanceof FormData) {
			originalOptions.data.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
		};
	});

	// Handle popup: calon santri lulus
	$(document).ready(function() {
		if (_isLockDaftarUlang == true) {
			swal({
				title: "Selamat atas kelulusan ananda!",
				text: "Selanjutnya, silahkan isi Kelengkapan Dokumen pada halaman profile sebagai persyaratan daftar ulang. Klik \"OK\" untuk informasi lebih lanjut!",
				type: "success",
				showCancelButton: false,
				confirmButtonColor: '#39bbb0',
				confirmButtonText: "OK",
				closeOnConfirm: false
			}).then((result) => {
				if (_isLockDaftarUlang_redirect === true) {
					window.location.href = "<?= base_url('post/s/read/psb--persyaratan-daftar-ulang') ?>";
				};
			});
		};
	});
</script>
</body>

</html>