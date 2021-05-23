<?php

namespace Ibd;

class Koszyk
{
	/**
	 * Instancja klasy obsługującej połączenie do bazy.
	 *
	 */
	private Db $db;

	public function __construct()
	{
		$this->db = new Db();
	}

	/**
	 * Pobiera dane książek w koszyku.
	 *
	 * @return array
	 */
	public function pobierzWszystkie(): array
    {
		$sql = "
			SELECT ks.*, ko.liczba_sztuk, ko.id AS id_koszyka, kat.nazwa AS kategoria, CONCAT(aut.imie, ' ', aut.nazwisko) AS autor
			FROM ksiazki ks JOIN koszyk ko ON ks.id = ko.id_ksiazki
			JOIN kategorie kat ON ks.id_kategorii = kat.id
			JOIN autorzy aut ON ks.id_autora = aut.id
			WHERE ko.id_sesji = '" . session_id() . "'
			ORDER BY ko.data_dodania DESC";

		return $this->db->pobierzWszystko($sql);
	}

	/**
	 * Dodaje książkę do koszyka.
	 *
	 * @param int    $idKsiazki
	 * @param string $idSesji
	 * @return int
	 */
	public function dodaj(int $idKsiazki, string $idSesji): int
    {
		$dane = [
			'id_ksiazki' => $idKsiazki,
			'id_sesji' => $idSesji
		];

		return $this->db->dodaj('koszyk', $dane);
	}

	/**
	 * Sprawdza, czy podana książka znajduje się w koszyku.
	 *
	 * @param int    $idKsiazki
	 * @param string $idSesji
	 * @return bool
	 */
	public function czyIstnieje(int $idKsiazki, string $idSesji): bool
    {
		$sql = "SELECT * FROM koszyk WHERE id_sesji = '$idSesji' AND id_ksiazki = :id_ksiazki";
		$ile = $this->db->policzRekordy($sql, [':id_ksiazki' => $idKsiazki]);
		
		return $ile > 0;
	}

	/**
	 * Zmienia (usuwa) ilości sztuk książek w koszyku.
	 *
	 * @param array $dane Tablica z danymi (klucz to id rekordu w koszyku, wartość to liczba sztuk)
	 */
	public function zmienLiczbeSztuk(array $dane): void
	{
		foreach($dane as $idKoszyka => $ilosc) {
		    if ($ilosc <= 0) {
                $this->db->usun('koszyk', $idKoszyka);
            } else {
                $this->db->aktualizuj('koszyk', ['liczba_sztuk' => $ilosc], $idKoszyka);
            }
		}
	}

    /**Zwraca ilość książek w koszyku.
     * @return int
     */
	public function ileWKoszyku(): int
    {
        $wKoszyku = 0;
        $listaKsiazek = $this->pobierzWszystkie();
        if(count($listaKsiazek) > 0)
        {
            foreach($listaKsiazek as $ks)
             {
                 $wKoszyku = $wKoszyku + $ks['liczba_sztuk'];
             }
        }
        return $wKoszyku;

    }

    /**
     * Zwraca informacje na temat książki o podanym ID z koszyka
     * @param int $idKsiazki
     * @return array
     */
    public function pobierzKsiazkeZKoszyka(int $idKsiazki): array
    {
        return $this->db->pobierzKsiazkeZKoszyka('koszyk', $idKsiazki);
    }
    /**
     * Czyści koszyk.
     *
     * @param string $idSesji
     * @return bool
     */
    public function wyczysc(string $idSesji): bool
    {
        return $this->db->wykonaj("DELETE FROM koszyk WHERE id_sesji = :id_sesji", ['id_sesji' => $idSesji]);
    }
}
