<div class="table-responsive">
    <table class="table table-sm table-hover text-center text-xss">
        <thead class="table-info">
            <tr>
                <th rowspan="2">Jenis Tenaga</th>
                <th rowspan="2">Jumlah SDM Yang Ada</th>
                <th colspan="2">Status Kepegawaian</th>
            </tr>
            <tr>
                <th>HK</th>
                <th>MK</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($datas) > 0) : ?>
                <?php foreach ($datas as $index => $item) : ?>
                    <?php if ($item->kategori_colspan > 0) : ?>
                        <tr>
                            <td class="text-left" colspan="<?= $item->kategori_colspan ?>"><?= $item->kategori ?></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td class="text-left"><?= $item->kategori ?></td>
                            <td><?= ($item->jumlah != 0) ? $item->jumlah : '-' ?></td>
                            <td><?= ($item->jumlah_hk != 0) ? $item->jumlah_hk : '-' ?></td>
                            <td><?= ($item->jumlah_mk != 0) ? $item->jumlah_mk : '-' ?></td>
                        </tr>
                    <?php endif ?>
                <?php endforeach ?>
            <?php else : ?>
                <tr>
                    <td colspan="12">Tidak ditemukan data</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>