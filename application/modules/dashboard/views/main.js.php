<script type="text/javascript">
  $(document).ready(function() {

    $(document).ajaxStart(function() {
      $(".spinner").css("display", "none");
    });

    // Disabled right click on home image
    $(".img-home").contextmenu(function() {
      return false;
    });

    // Init Load Data
    setTimeout(function() {
      getTotalPegawaiAktif();
      getTotalPegawaiHabisKontrak();
      getTotalDemosiMutasi();
      getTotalDiklat();
      getStatistikTingkatPendidikan();
      getStatistikKategoriPegawai();
      getStatistikUsiaPegawai();
      getStatistikJenisKelamin();
      getStatistikHubunganKerja();
    }, 250);

    $(document).on("click", ".under-development-msg", function() {
      swal("Under Development", null, "info");
    });

    $(document).on("click", ".statistic-tingkat_pendidikan-type", function() {
      getStatistikTingkatPendidikan();
    });

    $(document).on("click", ".statistic-usia_pegawai-type", function() {
      getStatistikUsiaPegawai();
    });

    $(document).on("click", ".statistic-jenis_kelamin-type", function() {
      getStatistikJenisKelamin();
    });

    async function getTotalPegawaiAktif() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_total_pegawai_aktif') ?>",
        beforeSend: function() {
          $("#count-total_pegawai_aktif-loader").css("display", "flex");
        },
        success: function(response) {
          $("#count-total_pegawai_aktif-loader").css("display", "none");
          try {
            $("#count-total_pegawai_aktif").html(response);
          } catch (error) {};
        }
      });
    };

    async function getTotalPegawaiHabisKontrak() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_total_pegawai_habis_kontrak') ?>",
        beforeSend: function() {
          $("#count-total_pegawai_habis_kontrak-loader").css("display", "flex");
        },
        success: function(response) {
          $("#count-total_pegawai_habis_kontrak-loader").css("display", "none");
          try {
            $("#count-total_pegawai_habis_kontrak").html(response);
          } catch (error) {};
        }
      });
    };

    async function getTotalDemosiMutasi() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_total_demosi_mutasi') ?>",
        beforeSend: function() {
          $("#count-total_demosi_mutasi-loader").css("display", "flex");
        },
        success: function(response) {
          $("#count-total_demosi_mutasi-loader").css("display", "none");
          try {
            $("#count-total_demosi_mutasi").html(response);
          } catch (error) {};
        }
      });
    };

    async function getTotalDiklat() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_total_diklat') ?>",
        beforeSend: function() {
          $("#count-total_diklat-loader").css("display", "flex");
        },
        success: function(response) {
          $("#count-total_diklat-loader").css("display", "none");
          try {
            $("#count-total_diklat").html(response);
          } catch (error) {};
        }
      });
    };

    async function getStatistikTingkatPendidikan() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_statistic_tingkat_pendidikan') ?>",
        beforeSend: function() {
          $("#statistic-tingkat_pendidikan-loader").css("display", "flex");
        },
        success: function(response) {
          $("#statistic-tingkat_pendidikan-loader").css("display", "none");
          try {
            $("#statistic-tingkat_pendidikan").html(response);
          } catch (error) {};
        }
      });
    };

    async function getStatistikKategoriPegawai() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_statistic_kategori_pegawai') ?>",
        beforeSend: function() {
          $("#statistic-kategori_pegawai-loader").css("display", "flex");
        },
        success: function(response) {
          $("#statistic-kategori_pegawai-loader").css("display", "none");
          try {
            $("#statistic-kategori_pegawai").html(response);
          } catch (error) {};
        }
      });
    };

    async function getStatistikUsiaPegawai() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_statistic_usia_pegawai') ?>",
        beforeSend: function() {
          $("#statistic-usia_pegawai-loader").css("display", "flex");
        },
        success: function(response) {
          $("#statistic-usia_pegawai-loader").css("display", "none");
          try {
            $("#statistic-usia_pegawai").html(response);
          } catch (error) {};
        }
      });
    };

    async function getStatistikJenisKelamin() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_statistic_jenis_kelamin') ?>",
        beforeSend: function() {
          $("#statistic-jenis_kelamin-loader").css("display", "flex");
        },
        success: function(response) {
          $("#statistic-jenis_kelamin-loader").css("display", "none");
          try {
            $("#statistic-jenis_kelamin").html(response);
          } catch (error) {};
        }
      });
    };

    async function getStatistikHubunganKerja() {
      await $.ajax({
        type: "get",
        url: "<?php echo base_url('dashboard/get_statistic_hubungan_kerja') ?>",
        beforeSend: function() {
          $("#statistic-hubungan_kerja-loader").css("display", "flex");
        },
        success: function(response) {
          $("#statistic-hubungan_kerja-loader").css("display", "none");
          try {
            $("#statistic-hubungan_kerja").html(response);
          } catch (error) {};
        }
      });
    };

  });
</script>