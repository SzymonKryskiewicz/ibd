<?php

require_once 'vendor/autoload.php';

use Ibd\Kategorie;
use Ibd\Stronicowanie;
$kategorie = new Kategorie();
$select = $kategorie->pobierzSelect($_GET);


// dodawanie warunków stronicowania i generowanie linków do stron
$stronicowanie = new Stronicowanie($_GET, $select['parametry']);
$linki = $stronicowanie->pobierzLinki($select['sql'], 'admin.kategorie.lista.php');
$zapytanie = $stronicowanie->dodajLimit($select['sql']);
$lista = $kategorie->pobierzStrone($zapytanie, $select['parametry']);
include 'admin.header.php';
?>

<h2>
    Kategorie
    <small><a href="admin.kategorie.dodaj.php">dodaj</a></small>
</h2>
    <form method="get" action="" class="form-inline mb-4">
        <input type="text" name="fraza" placeholder="szukaj" class="form-control form-control-sm mr-2"
               value="<?= $_GET['fraza'] ?? '' ?>"/>

        <select name="sortowanie" id="sortowanie" class="form-control form-control-sm mr-2">
            <option value="">sortowanie</option>
            <option value="k.nazwa ASC"
                <?= ($_GET['sortowanie'] ?? '') == 'nazwa ASC' ? 'selected' : '' ?>
            >nazwie kategorii rosnąco
            </option>
            <option value="k.nazwa DESC"
                <?= ($_GET['sortowanie'] ?? '') == 'nazwa DESC' ? 'selected' : '' ?>
            >nazwie kategorii malejąco
            </option>
        </select>

        <button class="btn btn-sm btn-primary" type="submit">Szukaj</button>
    </form>


<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Kategoria została dodana.</p>
<?php endif; ?>

<table id="kategorie" class="table table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nazwa</th>
            <th>Liczba książek&nbsp;</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= $a['nazwa'] ?></td>
                <td><?= $kategorie->liczbaKsiazek($a['id'])?></td>
                <td>
                    <a href="admin.kategorie.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujKategorie"><em class="fas fa-pencil-alt"></em></a>
                    <?php if ($kategorie->liczbaKsiazek($a['id']) <= 0): ?><a href="admin.kategorie.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunAutora"><em class="fas fa-trash"></em></a>
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