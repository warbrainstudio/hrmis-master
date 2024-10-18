<?php $uniqueId = md5(date('YmdHis')) ?>

<div class="modal fade" id="modal-view-contract" data-backdrop="static" data-keyboard="false">
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
                            <label>Nomor</label>
                            <div class="form-control"><?= @$contract->no_kontrak ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Pegawai</label>
                            <div class="form-control"><?= @$contract->nrp . ' / ' . @$contract->nama_lengkap ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Kategori Pegawai</label>
                            <div class="form-control"><?= @$contract->nama_kategori_pegawai ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Status Pegawai</label>
                            <div class="form-control"><?= @$contract->nama_jenis_pegawai ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Status Kontrak</label>
                            <div class="form-control"><?= @$contract->nama_status_kontrak ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Jabatan</label>
                            <div class="form-control"><?= @$contract->nama_jabatan ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Unit</label>
                            <div class="form-control"><?= @$contract->nama_unit ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Sub Unit</label>
                            <div class="form-control"><?= @$contract->nama_sub_unit ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>SOC</label>
                            <div class="form-control"><?= @$contract->soc ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>EOC</label>
                            <div class="form-control"><?= @$contract->eoc ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <div class="form-control"><?= (@$contract->status_active == 1) ? 'Aktif' : 'Tidak Aktif' ?></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn--icon-text contract-action-download-<?= $uniqueId ?>" data-id="<?= @$contract->id ?>" data-type="<?= @$contract->jenis_pegawai_id ?>">
                    <i class="zmdi zmdi-download"></i> Cetak Kontrak
                </button>
                <button type="button" class="btn btn-light btn--icon-text contract-action-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Handle download
    $(document).on("click", "button.contract-action-download-<?= $uniqueId ?>", function(e) {
        e.preventDefault();
        var ref = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var output = "pdf"; // docx or pdf

        if (ref != null && type != null) {
            notify("Sedang melakukan generate, silahkan tunggu sampai loading selesai...", "info");
            $.ajax({
                type: "get",
                url: "<?php echo base_url('contract/ajax_generate/') ?>",
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
                                $("#modal-view-contract").modal("hide");
                                $("#modal-view-embed").modal("show");
                                $("#modal-view-embed-title").html("Kontrak Kerja");
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