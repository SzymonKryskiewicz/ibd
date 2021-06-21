<?php

require_once 'vendor/autoload.php';

use Ibd\Zamowienia;
use Valitron\Validator;

if (empty($_GET['id'])) {
    header("Location: admin.zamowienia.lista.php");
    exit();
} else {
    $id = (int)$_GET['id'];
}

$zamowienia = new Zamowienia();
$v = new Validator($_POST);

if (!empty($_POST)) {
    $v->rule('required', ['id_statusu']);

    if ($v->validate() && $zamowienia->edytuj($_POST, $id)) {
       header("Location: admin.zamowienia.edycja.php?id=$id&msg=1");
       exit();
   }
   $dane = $_POST;
} else {
    $dane = $zamowienia->pobierzZamowienie($id);
}

include 'admin.header.php';
//pobieranie statusów zamówień

$listaStatusow = $zamowienia->pobierzWszystkieStatusy();
?>

<h2>
	Status zamówienia
	<small>edycja</small><br>
    <small>Zamówienie nr <b><?=$dane['id']?></b></small>
</h2>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 1): ?>
	<p class="alert alert-success">Status został zmieniony.</p>
<?php endif; ?>

<?php include 'admin.zamowienia.form.php' ?>

<?php include 'admin.footer.php'; ?>