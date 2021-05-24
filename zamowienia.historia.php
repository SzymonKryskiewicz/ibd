<?php
require_once 'vendor/autoload.php';
include 'header.php';

use Ibd\Zamowienia;

$zamowienia = new Zamowienia();
$listaZamowien = $zamowienia->pobierzWszystkie();

?>

    <h2>Historia zamówień</h2>
    <table class="table table-striped table-condensed" id="zamowienia">
        <thead>
        <tr>
            <th>ID&nbsp;zamówienia</th>
            <th>Data zamówienia</th>
            <th>Status zamówienia</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($listaZamowien as $zam): ?>
            <tr>
                <td><?= $zam['id'] ?></td>
                <td><?= $zam['data_dodania'] ?></td>
                <td><?= $zamowienia->pobierzStatus($zam['id_statusu']) ?></td>
                <td style="white-space: nowrap">
                    <a href="zamowienia.szczegoly.php?id=<?= $zam['id'] ?>" title="szczegóły">
                        <i class="fas fa-folder-open"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


<?php include 'footer.php'; ?>