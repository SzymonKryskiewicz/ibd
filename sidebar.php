<div class="col-md-2">
    <h1>Bestsellery</h1>
    <?php

    use Ibd\Ksiazki;

    $ksiazki = new Ksiazki();
    $lista = $ksiazki->pobierzBestsellery();
    ?>
    <ul>
        <?php foreach ($lista as $ks) : ?>
            <?php $dane_autora = $ksiazki->pobierzAutora($ks['id_autora']);?>
            <li>
                <?php if (!empty($ks['zdjecie'])) : ?>
                    <img src="zdjecia/<?= $ks['zdjecie'] ?>" alt="<?= $ks['tytul'] ?>" width="70"
                         class="img-thumbnail"/>
                <?php else : ?>
                    brak zdjęcia
                <?php endif; ?>
                <br><b><?= $ks['tytul'] ?></b>
                <br><?= $dane_autora['imie']?> <?= $dane_autora['nazwisko']?>
                <br><?= $ks['cena'] ?> PLN

                <br><a href="#" title="dodaj do koszyka"><i class="fas fa-cart-plus"></i></a>
                <a href="ksiazki.szczegoly.php?id=<?= $ks['id'] ?>" title="szczegóły"><i
                            class="fas fa-folder-open"></i></a>
            </li>

        <?php endforeach; ?>
    </ul>

</div>