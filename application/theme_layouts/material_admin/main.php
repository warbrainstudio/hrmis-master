<?php include_once('header.php') ?>

<script type="text/javascript">
    var _isLockDaftarUlang = false;
    var _isLockDaftarUlang_redirect = true;
</script>

<section class="content">
    <?php
    // Announcement
    // Santri
    if ($this->session->userdata('user')['role'] === 'Santri') {
        // Check Santri Data
        if (isset($app->check_santri_data)) {
            $_checkSantriData = $app->check_santri_data;

            if ($_checkSantriData->status === true) {
                echo $_checkSantriData->data;
            };
        };
    };
    // Calon Santri
    if ($this->session->userdata('user')['role'] === 'Calon Santri') {
        // PSB Step-by-step
        if (isset($app->check_calon_santri) && $app->check_calon_santri !== false) {
            $psbStatus = $app->check_calon_santri->status;
            $isLockDaftarUlang = ((int) $psbStatus === 3 && boolval($app->check_calon_santri_dokumen) === false) ? 'true' : 'false';

            // Inject script
            echo '
                <script type="text/javascript">
                    _isLockDaftarUlang = ' . $isLockDaftarUlang . ';
                </script>
            ';

            if (!$app->is_mobile) {
                switch ((int) $psbStatus) {
                    case 0:
                        $csStep = [2 => '', 3 => '', 4 => '', 5 => ''];
                        break;
                    case 1:
                        $csStep = [2 => 'active', 3 => '', 4 => '', 5 => ''];
                        break;
                    case 2:
                        $csStep = [2 => 'active', 3 => 'active', 4 => 'error', 5 => 'error'];
                        break;
                    case 3:
                        $csStep = [2 => 'active', 3 => 'active', 4 => 'active', 5 => ''];
                        break;
                    case 4:
                        $csStep = [2 => 'active', 3 => 'active', 4 => 'active', 5 => 'active'];
                        break;
                    default:
                        $csStep = [2 => '', 3 => '', 4 => '', 5 => ''];
                        break;
                };

                echo '
                    <div class="row cs-step-container">
                        <div class="cs-step-line"></div>
                        <div class="col">
                            <div class="cs-step active">
                                <span class="badge badge-light badge-pill" style="margin-right: .50rem;">1</span>
                                Melakukan Pendaftaran
                            </div>
                        </div>
                        <div class="col">
                            <a href="' . base_url('post/detail/3') . '" class="cs-step ' . $csStep[2] . ' ripple-effect">
                                <span class="badge badge-light badge-pill" style="margin-right: .50rem;">2</span>
                                Membayar Pendaftaran
                            </a>
                        </div>
                        <div class="col">
                            <a href="' . base_url('post/detail/4') . '" class="cs-step ' . $csStep[3] . ' ripple-effect">
                                <span class="badge badge-light badge-pill" style="margin-right: .50rem;">3</span>
                                Mengikuti Ujian Masuk
                            </a>
                        </div>
                        <div class="col">
                            <div class="cs-step ' . $csStep[4] . '">
                                <span class="badge badge-light badge-pill" style="margin-right: .50rem;">4</span>
                                Lulus Ujian Masuk
                            </div>
                        </div>
                        <div class="col">
                            <a href="' . base_url('post/detail/1') . '" class="cs-step ' . $csStep[5] . ' ripple-effect">
                                <span class="badge badge-light badge-pill" style="margin-right: .50rem;">5</span>
                                Melakukan Daftar Ulang
                            </a>
                        </div>
                    </div>
                ';
            } else {
                switch ((int) $psbStatus) {
                    case 0:
                        $stepXsContent = '
                            <a href="' . base_url('post/detail/3') . '" class="cs-step-xs-content ripple-effect">
                                Membayar Pendaftaran
                            </a>
                        ';
                        break;
                    case 1:
                        $stepXsContent = '
                            <a href="' . base_url('post/detail/3') . '" class="cs-step-xs-content ripple-effect">
                                Mengikuti Ujian Masuk
                            </a>
                        ';
                        break;
                    case 3:
                        $stepXsContent = '
                            <a href="' . base_url('post/detail/3') . '" class="cs-step-xs-content ripple-effect">
                                Melakukan Daftar Ulang
                            </a>
                        ';
                        break;
                    default:
                        $stepXsContent = '
                            <div class="cs-step-xs-content">
                                Maaf, tidak tersedia
                            </div>
                        ';
                        break;
                };

                if (in_array((int) $psbStatus, [0, 1, 3])) {
                    echo '
                        <div class="cs-step-container-xs">
                            <div class="cs-step-xs">
                                <div class="mb-2">
                                    <i class="zmdi zmdi-info"></i>
                                    Langkah selanjutnya
                                </div>
                                ' . $stepXsContent . '
                            </div>
                        </div>
                    ';
                };
            };

            // Link to learndash-wp
            if ((int) $psbStatus === 1) {
                $petunjukLms = $app->petunjuk_lms;
                if ($petunjukLms !== false && !is_null($petunjukLms->konten)) {
                    echo '
                        <div class="alert alert-dark">
                            ' . $petunjukLms->konten . '
                        </div>
                    ';
                };
            };
        };
    };
    ?>

    <?php if (isset($page_title) && !empty($page_title) && !is_null($page_title)) : ?>
        <header class="content__title">
            <h1><?php echo $page_title ?></h1>

            <?php if (isset($page_subTitle) && !empty($page_subTitle) && !is_null($page_subTitle)) : ?>
                <small><?php echo $page_subTitle ?></small>
            <?php endif; ?>
        </header>
    <?php endif; ?>

    {content}

    <?php include_once('footerCredit.php') ?>
</section>

<?php include_once('footer.php') ?>