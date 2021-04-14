<?php

// jesli nie podano parametru id, przekieruj do listy książek
if (empty($_GET['id'])) {
    header("Location: ksiazki.lista.php");
    exit();
}

$id = (int)$_GET['id'];

include 'header.php';

use Ibd\Ksiazki;
$ksiazki = new Ksiazki();
$dane = $ksiazki->pobierz($id);
$dane_autora = $ksiazki->pobierzAutora($dane['id_autora']);
$dane_kategorii = $ksiazki->pobierzKategorie($dane['id_kategorii']);
?>

    <h2><?= $dane['tytul'] ?></h2>


    <p>
        <a href="ksiazki.lista.php"><i class="fas fa-chevron-left"></i> Powrót</a>
    </p>


    <div style="text-align: justify;">
        <p style="float: left; padding-right: 15px;">
            <?php if (!empty($dane['zdjecie'])) : ?>
                <img src="zdjecia/<?= $dane['zdjecie'] ?>" alt="<?= $dane['tytul'] ?>" width="250"
                     class="img-thumbnail"/>
            <?php else : ?>
        <p><b>brak zdjęcia</b></p><br>
            <?php endif; ?>

        </p>
        <p>
            <b>Cena:</b> <?= $dane['cena'] ?> PLN
            <br><b>Autor:</b> <?= $dane_autora['imie']?> <?=$dane_autora['nazwisko'] ?>
            <br><b>Kategoria:</b> <?= $dane_kategorii['nazwa'] ?>

            <br><b>Liczba stron:</b> <?= $dane['liczba_stron'] ?>
            <br><b>ISBN:</b> <?= $dane['isbn'] ?>


        </p>
    </div>
    <div style="float: left;text-align: justify;">
        <p>
        <b>OPIS</b><br><?= $dane['opis'] ?>
        </p>
    </div>


<?php include 'footer.php'; ?>