<div class="table-responsive">
    <table class="table table-sm table-hover text-center text-xss">
        <thead class="table-danger">
            <tr>
                <th rowspan="2">Jenis Kelamin</th>
                <th colspan="11">Kode Unit</th>
            </tr>
            <tr>
                <th>001</th>
                <th>002</th>
                <th>003</th>
                <th>004</th>
                <th>005</th>
                <th>006</th>
                <th>007</th>
                <th>008</th>
                <th>009</th>
                <th>010</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($datas) > 0) : ?>
                <?php foreach ($datas as $index => $item) : ?>
                    <tr>
                        <td class="text-left"><?= $item->jenis_kelamin ?></td>
                        <td><?= ($item->{'001'} != 0) ? $item->{'001'} : '-' ?></td>
                        <td><?= ($item->{'002'} != 0) ? $item->{'002'} : '-' ?></td>
                        <td><?= ($item->{'003'} != 0) ? $item->{'003'} : '-' ?></td>
                        <td><?= ($item->{'004'} != 0) ? $item->{'004'} : '-' ?></td>
                        <td><?= ($item->{'005'} != 0) ? $item->{'005'} : '-' ?></td>
                        <td><?= ($item->{'006'} != 0) ? $item->{'006'} : '-' ?></td>
                        <td><?= ($item->{'007'} != 0) ? $item->{'007'} : '-' ?></td>
                        <td><?= ($item->{'008'} != 0) ? $item->{'008'} : '-' ?></td>
                        <td><?= ($item->{'009'} != 0) ? $item->{'009'} : '-' ?></td>
                        <td><?= ($item->{'010'} != 0) ? $item->{'010'} : '-' ?></td>
                        <td><?= ($item->jumlah != 0) ? $item->jumlah : '-' ?></td>
                    </tr>
                <?php endforeach ?>
            <?php else : ?>
                <tr>
                    <td colspan="12">Tidak ditemukan data</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>