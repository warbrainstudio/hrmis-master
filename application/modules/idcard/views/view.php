<?php $uniqueId = md5(date('YmdHis')) ?>

<style type="text/css">
    #idcard-container {
        background-color: #f2f2f2;
        width: 300px;
        height: 500px;
        border: 1px solid #f2f2f2;
    }

    #idcard-container img {
        width: 100px;
        height: 100px;
    }

    .idcard-item {
        position: relative;
    }

    body .modal-dialog {
        /* Width */
        max-width: 100%;
        width: auto !important;
        display: inline-block;
    }

    .modal {
        z-index: -1;
        display: flex !important;
        justify-content: center;
        align-items: center;
    }

    .modal-open .modal {
        z-index: 1050;
    }
</style>

<div class="modal fade" id="modal-view-idcard" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
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
                <div id="idcard-container" style="<?= 'width: ' . @$config['background']['width'] . ';height: ' . @$config['background']['height'] . ';background-size: ' . @$config['background']['background-size'] . ';background-image: url(' . @$config['background']['src'] . ');' ?>">
                    <?php if (count($config) > 0) : ?>
                        <?php foreach ($config as $index => $item) : ?>
                            <?php
                            $inlineStyle = (@$item['active'] == 1) ? 'display: inherit;' : 'display: none;';
                            foreach ($item as $key => $value) {
                                if ($key === "font-style") {
                                    $value = ($value == 1) ? 'italic' : '';
                                } else if ($key === "font-weight") {
                                    $value = ($value == 1) ? 'bold' : '';
                                } else if (@$item['type'] === 'image' && $key === "src") {
                                    $key = 'background-image';
                                    $value = 'url(' . base_url((!is_null(@$employee->foto) && !empty(@$employee->foto)) ? @$employee->foto : @$item['src']) . ')';
                                    $inlineStyle .= 'background-position:center;';
                                } else if (@$item['type'] === 'image' && $key === "object-fit") {
                                    $key = 'background-size';
                                };
                                $inlineStyle .= $key . ':' . $value . ';';
                            };
                            ?>
                            <?php if (@$item['type'] === 'label') : ?>
                                <div class="idcard-item" style="<?= $inlineStyle ?>"><?= @$employee->$index ?></div>
                            <?php elseif (@$item['type'] === 'image' && @$index !== 'background') : ?>
                                <!-- <img src="<?= base_url((!is_null(@$employee->foto) && !empty(@$employee->foto)) ? @$employee->foto : @$item['src']) ?>" class="idcard-item" style="<?= $inlineStyle ?>" /> -->
                                <div class="idcard-item" style="<?= $inlineStyle ?>"><?= @$employee->$index ?></div>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn--icon-text idcard-action-download-<?= $uniqueId ?>">
                    <i class="zmdi zmdi-download"></i> Unduh PNG
                </button>
                <button type="button" class="btn btn-light btn--icon-text idcard-action-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Handle download
    $(document).on("click", ".idcard-action-download-<?= $uniqueId ?>", function() {
        html2canvas(document.getElementById("idcard-container")).then(function(canvas) {
            var anchorTag = document.createElement("a");
            var imageData = canvas.toDataURL("image/png");
            document.body.appendChild(anchorTag);
            anchorTag.download = "id-card-<?= $uniqueId ?>.png";
            anchorTag.href = imageData.replace(/^data:image\/png/, "data:application/octet-stream");
            anchorTag.target = '_blank';
            anchorTag.click();
        });
    });
</script>