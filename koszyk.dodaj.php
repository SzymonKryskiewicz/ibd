<?php
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
session_start();
require_once 'vendor/autoload.php';

use Ibd\Koszyk;

$koszyk = new Koszyk();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
    if ($koszyk->czyIstnieje($_GET['id'], session_id())) {
       //książka jest w koszyku, zwiększ liczbę sztuk o jeden
        $ksiazka = $koszyk->pobierzKsiazkeZKoszyka($_GET['id']);
        $koszyk->zmienLiczbeSztuk([$ksiazka['id'] => $ksiazka['liczba_sztuk'] + 1]);

        echo 'ok';
    } else {
        // książki nie ma w koszyku, dodaj do koszyka
        if ($koszyk->dodaj($_GET['id'], session_id())) {
            echo 'ok';
        }
    }
}