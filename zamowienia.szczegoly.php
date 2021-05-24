<?php

// jesli nie podano parametru id, przekieruj do listy zamowien
if (empty($_GET['id'])) {
    header("Location: zamowienia.historia.php");
    exit();
}

$id = (int)$_GET['id'];

include 'header.php';

use Ibd\Zamowienia;
use Ibd\Ksiazki;

$zamowienia = new Zamowienia();
$ksiazka = new Ksiazki();
$zam = $zamowienia->pobierzZamowienie($id);

//Jeśli użytkownik chce wejść w nie swoje zamówienie, przekieruj do listy zamówień.
if ($zam['id_uzytkownika'] <> $_SESSION['id_uzytkownika']) {
    header("Location: zamowienia.historia.php");
}
$listaKsiazek = $zamowienia->pobierzSzczegoly($id);

?>

    <h2>Zamówienie numer <?= $listaKsiazek[0]['id_zamowienia'] ?></h2>
    <p>
        <a href="zamowienia.historia.php"><i class="fas fa-chevron-left"></i> Powrót</a>
    </p>


    <table class="table table-striped table-condensed" id="koszyk">
        <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Tytuł</th>
            <th>Autor</th>
            <th>Kategoria</th>
            <th>Cena za sztukę</th>
            <th>Liczba sztuk</th>
            <th>Cena razem</th>
            <th></th>
        </tr>
        </thead>

        <?php $suma = 0; ?>
        <tbody>
        <?php foreach ($listaKsiazek as $ks): ?>

            <?php $dane = $ksiazka->pobierz($ks['id']);
            $dane_autora = $ksiazka->pobierzAutora($dane['id_autora']);
            $dane_kategorii = $ksiazka->pobierzKategorie($dane['id_kategorii']); ?>
            <?php //dd($ks['cena']);?>
            <tr>
                <td style="width: 100px">
                    <?php if (!empty($dane['zdjecie'])): ?>
                        <img src="zdjecia/<?= $dane['zdjecie'] ?>" alt="<?= $dane['tytul'] ?>" class="img-thumbnail"/>
                    <?php else: ?>
                        brak zdjęcia
                    <?php endif; ?>
                </td>
                <td><?= $dane['tytul'] ?></td>
                <td><?= $dane_autora['imie'] . " " . $dane_autora['nazwisko'] ?></td>
                <td><?= $dane_kategorii['nazwa'] ?></td>
                <td><?= $ks['cena'] ?></td>
                <td><?= $ks['liczba_sztuk'] ?></td>
                <td><?= number_format($ks['cena'] * $ks['liczba_sztuk'], 2) ?></td>
                <td>
                    <a href="ksiazki.szczegoly.php?id=<?= $ks['id'] ?>" title="szczegóły">
                        <i class="fas fa-folder-open"></i>
                    </a>
                </td>
            </tr>
            <?php $suma = $suma + $ks['cena'] * $ks['liczba_sztuk']; ?>
        <?php endforeach; ?>
        <tr>
            <th colspan="6" style="text-align:right">Razem:</th>
            <td id='razem'><?= number_format($suma, 2); ?></td>
            <td></td>
        </tr>
        </tbody>

    </table>


<?php include 'footer.php'; ?>