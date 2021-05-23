<div class="col-md-3">
    <?php

    use Ibd\Ksiazki;

    $ksiazki = new Ksiazki();
    $lista = $ksiazki->pobierzBestsellery();
    ?>
    <?php if (empty($_SESSION['id_uzytkownika'])): ?>
        <h1>Logowanie</h1>

        <form method="post" action="logowanie.php">
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" class="form-control input-sm" />
            </div>
            <div class="form-group">
                <label for="haslo">Hasło:</label>
                <input type="password" id="haslo" name="haslo" class="form-control input-sm" />
            </div>
            <div class="form-group">
                <button type="submit" name="zaloguj" id="submit" class="btn btn-primary btn-sm">Zaloguj się</button>
                <a href="rejestracja.php" class="btn btn-link btn-sm">Zarejestruj się</a>
                <input type="hidden" name="powrot" value="<?= basename($_SERVER['SCRIPT_NAME']) ?>" />
            </div>
        </form>
    <?php else: ?>
        <p class="text-right">
            Zalogowany: <strong><?= $_SESSION['login'] ?></strong>
            &nbsp;
            <a href="wyloguj.php" class="btn btn-secondary btn-sm">wyloguj się</a>
        </p>
    <?php endif; ?>

    <h1>Koszyk</h1>
    <p>
        Suma wartości książek w koszyku:
        <strong>0</strong> PLN
    </p>

    <h1>Bestsellery</h1>
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