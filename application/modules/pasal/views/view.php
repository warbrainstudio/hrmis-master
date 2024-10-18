<div class="modal fade" id="modal-view-pasal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Rincian' ?>
                </h5>
            </div>
            <div class="modal-body">
                <?= $pasal ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn--icon-text pasal-action-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>