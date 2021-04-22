<?php

namespace Ibd;

class Stronicowanie
{
    /**
     * Instancja klasy obsługującej połączenie do bazy.
     *
     * @var Db
     */
    private Db $db;

    /**
     * Liczba rekordów wyświetlanych na stronie.
     *
     * @var int
     */
    private int $naStronie = 5;

    /**
     * Aktualnie wybrana strona.
     *
     * @var int
     */
    private int $strona = 0;

    /**
     * Dodatkowe parametry przekazywane w pasku adresu (metodą GET).
     *
     * @var array
     */
    private array $parametryGet = [];

    /**
     * Parametry przekazywane do zapytania SQL.
     *
     * @var array
     */
    private array $parametryZapytania;

    public function __construct(array $parametryGet, array $parametryZapytania = [])
    {
        $this->db = new Db();
        $this->parametryGet = $parametryGet;
        $this->parametryZapytania = $parametryZapytania;
        if (!empty($parametryGet['strona'])) {
            $this->strona = (int)$parametryGet['strona'];
        }
    }

    /**
     * Dodaje do zapytania SELECT klauzulę LIMIT.
     *
     * @param string $select
     * @return string
     */
    public function dodajLimit(string $select): string
    {
        return sprintf('%s LIMIT %d, %d', $select, $this->strona * $this->naStronie, $this->naStronie);
    }

    /**
     * Generuje linki do wszystkich podstron.
     *
     * @param string $select Zapytanie SELECT
     * @param string $plik Nazwa pliku, do którego będą kierować linki
     * @return string
     */
    public function pobierzLinki(string $select, string $plik): string
    {
        $rekordow = $this->db->policzRekordy($select, $this->parametryZapytania);
        $liczbaStron = ceil($rekordow / $this->naStronie);
        $parametry = $this->_przetworzParametry();

        $linki = "<nav><ul class='pagination'>";
        for ($i = 0; $i < $liczbaStron; $i++) {
            if ($i == $this->strona) {
                $linki .= sprintf("<li class='page-item active'><a class='page-link'>%d</a></li>", $i + 1);
            } else {
                $linki .= sprintf(
                    "<li class='page-item'><a href='%s?%s&strona=%d' class='page-link'>%d</a></li>",
                    $plik,
                    $parametry,
                    $i,
                    $i + 1
                );
            }
        }
        $linki .= "</ul>";

        //Dodanie nawiagacji do strony pierwszej, poprzedniej, następnej i ostatniej po spełnieniu warunku, że liczba stron jest większa niż 1
        if ($liczbaStron > 1) {
            $linki .= "<ul class='pagination'>";
            //Link do pierwszej podstrony
            if ($this->strona == 0) {
                $linki .= sprintf("<li class='page-item active'><a class='page-link'>%s</a></li>", "Początek");
            } else {
                $linki .= sprintf(
                    "<li class='page-item'><a href='%s?%s&strona=%d' class='page-link'>%s</a></li>",
                    $plik,
                    $parametry,
                    0,
                    "Początek"
                );
            }

            //Link do strony poprzedniej
            if ($this->strona == 0) {
                $linki .= sprintf("<li class='page-item disabled'><a class='page-link'>%s</a></li>", "Poprzednia");
            } else {
                $linki .= sprintf(
                    "<li class='page-item'><a href='%s?%s&strona=%d' class='page-link'>%s</a></li>",
                    $plik,
                    $parametry,
                    $this->strona - 1,
                    "Poprzednia"
                );
            }
            //Link do strony nastepnej
            if ($this->strona == $liczbaStron - 1) {
                $linki .= sprintf("<li class='page-item disabled'><a class='page-link'>%s</a></li>", "Następna");
            } else {
                $linki .= sprintf(
                    "<li class='page-item'><a href='%s?%s&strona=%d' class='page-link'>%s</a></li>",
                    $plik,
                    $parametry,
                    $this->strona + 1,
                    "Następna"
                );
            }

            //Link do ostatniej strony
            if ($this->strona == $liczbaStron - 1) {
                $linki .= sprintf("<li class='page-item active'><a class='page-link'>%s</a></li>", "Koniec");
            } else {
                $linki .= sprintf(
                    "<li class='page-item'><a href='%s?%s&strona=%d' class='page-link'>%s</a></li>",
                    $plik,
                    $parametry,
                    $liczbaStron - 1,
                    "Koniec"
                );
            }
            $linki .= "</ul>";
        }
        //Dodanie informacji o liczbie wybranych rekordow i obecnie wyswietlanych
        $linki .= "</ul></nav>";
        $linki .= "<p align='left'>Wyświetlono ";
        if ($rekordow > 0){
            $linki .= sprintf($this->strona * $this->naStronie + 1);
            $linki .= " - ";
            //Jeżeli jest wyświetlona ostatnia strona
            if ($this->strona == $liczbaStron - 1) {
                $linki .= sprintf($rekordow);
            } //Jeżeli jest wyświetlona inna niż ostatnia strona
            else {
                $linki .= sprintf($this->strona * $this->naStronie + $this->naStronie);
            }
            $linki .= " z ";
        }
        $linki .= sprintf($rekordow);
        $linki .= " rekordów</p>";
        return $linki;
    }

    /**
     * Przetwarza parametry wyszukiwania.
     * Wyrzuca zbędne elementy i tworzy gotowy do wstawienia w linku zestaw parametrów.
     *
     * @return string
     */
    private function _przetworzParametry(): string
    {
        $temp = [];
        $usun = ['szukaj', 'strona'];
        foreach ($this->parametryGet as $kl => $wart) {
            if (!in_array($kl, $usun))
                $temp[] = "$kl=$wart";
        }
        return implode('&', $temp);
    }
}
