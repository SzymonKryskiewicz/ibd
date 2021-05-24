<?php
require_once 'vendor/autoload.php';
session_start();

use Ibd\Db;
use Ibd\Uzytkownicy;
use Valitron\Validator;

$db = new Db();

$uzytkownicy = new Uzytkownicy();
$v = new Validator($_POST);

if (isset($_POST['zapisz'])) {
    $v->rule('required', ['imie', 'nazwisko', 'adres', 'email', 'login', 'haslo']);
    $v->rule('email', ['email']);
    if ($v->validate()) {
        if ($db->sprawdz_login($_POST['login']) <= 0 and $db->sprawdz_email($_POST['email']) <= 0) {
            // brak błędów, można dodać użytkownika
            $uzytkownicy->dodaj($_POST);
            header("Location: index.php?msg=1");
            exit();
        }
    }
}

include 'header.php';
?>

    <h1>Rejestracja</h1>
<?php if (isset($_POST['zapisz'])): ?>
    <?php if ($v->errors() or $db->sprawdz_login($_POST['login']) > 0 or $db->sprawdz_email($_POST['email']) > 0): ?>
        <div class="alert alert-danger">
            <strong>Wystąpił błąd</strong>
            <ul>
                <?php foreach ($v->errors() as $err): ?>
                    <li><?= implode('<br>', $err) ?></li>
                <?php endforeach; ?>
                <?php if ($db->sprawdz_login($_POST['login']) > 0): ?>
                    <li><?= "Podany login już istnieje w bazie. Prośba o zmianę."; ?></li>
                <?php endif; ?>
                <?php if ($db->sprawdz_email($_POST['email']) > 0): ?>
                    <li><?= "Podany email już istnieje w bazie. Prośba o zmianę."; ?></li>
                <?php endif; ?>
            </ul>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <form method="post" action=""">
    <div class="form-group">
        <label for="imie">Imię</label>
        <input type="text" id="imie" name="imie" class="form-control <?= $v->errors('imie') ? 'is-invalid' : '' ?>"
               value="<?= $_POST['imie'] ?? '' ?>"/>
    </div>
    <div class="form-group">
        <label for="nazwisko">Nazwisko</label>
        <input type="text" id="nazwisko" name="nazwisko"
               class="form-control <?= $v->errors('nazwisko') ? 'is-invalid' : '' ?>"
               value="<?= $_POST['nazwisko'] ?? '' ?>"/>
    </div>
    <div class="form-group">
        <label for="adres">Adres</label>
        <input type="text" id="adres" name="adres" class="form-control <?= $v->errors('adres') ? 'is-invalid' : '' ?>"
               value="<?= $_POST['adres'] ?? '' ?>"/>
    </div>
    <div class="form-group">
        <label for="telefon">Telefon</label>
        <input type="text" id="telefon" name="telefon"
               class="form-control <?= $v->errors('telefon') ? 'is-invalid' : '' ?>"
               value="<?= $_POST['telefon'] ?? '' ?>"/>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" class="form-control <?= $v->errors('email') ? 'is-invalid' : '' ?>"
               value="<?= $_POST['email'] ?? '' ?>"/>
    </div>
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" class="form-control <?= $v->errors('login') ? 'is-invalid' : '' ?>"
               value="<?= $_POST['login'] ?? '' ?>"/>
    </div>
    <div class="form-group">
        <label for="haslo">Hasło</label>
        <input type="password" id="haslo" name="haslo"
               class="form-control <?= $v->errors('haslo') ? 'is-invalid' : '' ?>"/>
    </div>

    <input type="submit" name="zapisz" id="zapisz" class="btn btn-primary" value="Zarejestruj się"/><br/><br/>
    </form>

<?php include 'footer.php'; ?>