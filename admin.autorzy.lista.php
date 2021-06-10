<?php

require_once 'vendor/autoload.php';

use Ibd\Autorzy;
use Ibd\Stronicowanie;
$autorzy = new Autorzy();
$select = $autorzy->pobierzSelect($_GET);
//$lista = $autorzy->pobierzWszystko();


// dodawanie warunków stronicowania i generowanie linków do stron
$stronicowanie = new Stronicowanie($_GET, $select['parametry']);
$linki = $stronicowanie->pobierzLinki($select['sql'], 'admin.autorzy.lista.php');
$zapytanie = $stronicowanie->dodajLimit($select['sql']);
$lista = $autorzy->pobierzStrone($zapytanie, $select['parametry']);
include 'admin.header.php';
?>

<h2>
    Autorzy
    <small><a href="admin.autorzy.dodaj.php">dodaj</a></small>
</h2>
    <form method="get" action="" class="form-inline mb-4">
        <input type="text" name="fraza" placeholder="szukaj" class="form-control form-control-sm mr-2"
               value="<?= $_GET['fraza'] ?? '' ?>"/>

        <select name="sortowanie" id="sortowanie" class="form-control form-control-sm mr-2">
            <option value="">sortowanie</option>
            <option value="a.nazwisko ASC"
                <?= ($_GET['sortowanie'] ?? '') == 'nazwisko ASC' ? 'selected' : '' ?>
            >nazwisku rosnąco
            </option>
            <option value="a.nazwisko DESC"
                <?= ($_GET['sortowanie'] ?? '') == 'nazwisko DESC' ? 'selected' : '' ?>
            >nazwisku malejąco
            </option>
        </select>

        <button class="btn btn-sm btn-primary" type="submit">Szukaj</button>
    </form>


<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Autor został dodany.</p>
<?php endif; ?>

<table id="autorzy" class="table table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Liczba książek</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= $a['imie'] ?></td>
                <td><?= $a['nazwisko'] ?></td>
                <td><?= $autorzy->liczbaKsiazek($a['id'])?></td>
                <td>
                    <a href="admin.autorzy.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujAutora"><em class="fas fa-pencil-alt"></em></a>
                    <?php if ($autorzy->liczbaKsiazek($a['id']) <= 0): ?><a href="admin.autorzy.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunAutora"><em class="fas fa-trash"></em></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    <nav class="text-center">
        <?= $linki ?>
    </nav>
<?php include 'admin.footer.php'; ?>