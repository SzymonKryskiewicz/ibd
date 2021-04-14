<?php

namespace Ibd;

class Ksiazki
{
	/**
	 * Instancja klasy obsługującej połączenie do bazy.
	 *
	 * @var Db
	 */
	private Db $db;

	public function __construct()
    {
        $this->db = new Db();
	}

	/**
	 * Pobiera wszystkie książki.
	 *
	 * @return array
	 */
	public function pobierzWszystkie(): ?array
    {
		//$sql = "SELECT k.* FROM ksiazki k  ";
        $sql = "SELECT k.*, CONCAT(a.imie, ' ', a.nazwisko) AS autor_ksiazki, kat.nazwa AS nazwa_kategorii FROM ksiazki k
                INNER JOIN autorzy a ON k.id_autora = a.id
                INNER JOIN kategorie kat ON k.id_kategorii = kat.id";

		return $this->db->pobierzWszystko($sql);
	}

    /**
     * Pobiera dane książki o podanym id.
     *
     * @param int $id
     * @return array
     */
	public function pobierz(int $id): ?array
    {

        /*$sql = "SELECT k.*, CONCAT(a.imie, ' ', a.nazwisko) AS autor_ksiazki, kat.nazwa as nazwa_kategorii FROM ksiazki k
                INNER JOIN autorzy a ON k.id_autora = a.id
                INNER JOIN kategorie kat ON k.id_kategorii = kat.id where ksiazki.id = $id";*/
        return $this->db->pobierz('ksiazki', $id);
		//return $this->db->pobierz('ksiazki', $id);
	}

    /**
     * Pobiera dane autora o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierzAutora(int $id): ?array
    {
        return $this->db->pobierz('autorzy', $id);

    }

    /**
     * Pobiera dane kategorii o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierzKategorie(int $id): ?array
    {
        return $this->db->pobierz('kategorie', $id);

    }

	/**
	 * Pobiera najlepiej sprzedające się książki.
	 * 
	 */
	public function pobierzBestsellery()
	{
		$sql = "SELECT * FROM ksiazki ORDER BY RAND() LIMIT 5";
        return $this->db->pobierzWszystko($sql);
		// uzupełnić funkcję
	}

}
