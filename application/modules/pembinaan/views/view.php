<?php $uniqueId = md5(date('YmdHis')) ?>

<div class="modal fade" id="modal-view-pembinaan" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Rincian' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <div class="form-control"><?= @$pembinaan->kategori ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>No. Pembinaan</label>
                            <div class="form-control"><?= @$pembinaan->no_pembinaan ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Pegawai</label>
                            <div class="form-control"><?= @$pembinaan->nrp . ' / ' . @$pembinaan->nama_lengkap ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Perihal</label>
                            <div class="form-control"><?= @$pembinaan->perihal ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Unit</label>
                            <div class="form-control"><?= @$pembinaan->nama_unit ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Jabatan</label>
                            <div class="form-control"><?= @$pembinaan->nama_jabatan ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <div class="form-control"><?= @$pembinaan->start_date ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Tanggal Selesai</label>
                            <div class="form-control"><?= @$pembinaan->end_date ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <?php if (@$pembinaan->kategori != 'Scorsing') : ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>Pelanggaran</label>
                                <div class="form-control"><?= @$pembinaan->pelanggaran ?>&nbsp;</div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label>Sanksi</label>
                            <div class="form-control"><?= @$pembinaan->sanksi ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn--icon-text pembinaan-action-download-<?= $uniqueId ?>" data-id="<?= @$pembinaan->id ?>" data-type="<?= @$pembinaan->kategori ?>">
                    <i class="zmdi zmdi-download"></i> Cetak Pembinaan
                </button>
                <button type="button" class="btn btn-light btn--icon-text pembinaan-action-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Handle download
    $(document).on("click", "button.pembinaan-action-download-<?= $uniqueId ?>", function(e) {
        e.preventDefault();
        var ref = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var output = "pdf"; // docx or pdf

        if (ref != null && type != null) {
            notify("Sedang melakukan generate, silahkan tunggu sampai loading selesai...", "info");
            $.ajax({
                type: "get",
                url: "<?php echo base_url('pembinaan/ajax_generate/') ?>",
                data: {
                    ref: ref,
                    type: type,
                    output: output
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        notify(response.data, "success");
                        setTimeout(function() {
                            if (output === 'pdf') {
                                $("#modal-view-pembinaan").modal("hide");
                                $("#modal-view-embed").modal("show");
                                $("#modal-view-embed-title").html("Pembinaan Kerja");
                                $("#modal-view-embed-content").html("");

                                if (PDFObject.supportsPDFs) {
                                    PDFObject.embed(response.file_to_stream, "#modal-view-embed-content");
                                } else {
                                    $("#modal-view-embed-content").html("Inline PDFs are not supported by this browser, try using the latest version of Chrome / Firefox.");
                                };
                            } else {
                                window.open(response.file_to_stream, "_blank") || window.location.replace(response.file_to_stream);
                            };
                        }, 1000);
                    } else {
                        notify(response.data, "danger");
                    };
                }
            });
        } else {
            notify("Parameter tidak memenuhi syarat, silahkan hubungi Administrator.", "danger");
        };
    });
</script>