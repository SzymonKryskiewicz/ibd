<?php

namespace Ibd;

class Zamowienia
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
     * Dodaje zamówienie.
     * 
     * @param int $idUzytkownika
     * @return int Id zamówienia
     */
    public function dodaj(int $idUzytkownika): int
    {
        return $this->db->dodaj('zamowienia', [
            'id_uzytkownika' => $idUzytkownika,
            'id_statusu' => 1
        ]);
    }

    /**
     * Dodaje szczegóły zamówienia.
     * 
     * @param int   $idZamowienia
     * @param array $dane Książki do zamówienia
     */
    public function dodajSzczegoly(int $idZamowienia, array $dane): void
    {
        foreach ($dane as $ksiazka) {
            $this->db->dodaj('zamowienia_szczegoly', [
                'id_zamowienia' => $idZamowienia,
                'id_ksiazki' => $ksiazka['id'],
                'cena' => $ksiazka['cena'],
                'liczba_sztuk' => $ksiazka['liczba_sztuk']
            ]);
        }
    }

    /**
     * Pobiera wszystkie zamówienia użtkownika o podanym ID
     * @return array zamówienia danego użytkownika
     */
    public function pobierzWszystkie(): array
    {
        $sql = "
			SELECT *
			FROM zamowienia
			WHERE id_uzytkownika = '" . $_SESSION['id_uzytkownika'] . "'
			ORDER BY data_dodania DESC";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera szczegóły zamówienia o podanym ID.
     * @param $id id zamówienia
     * @return array szczegóły zamówienia
     */
    public function pobierzSzczegoly($id): array
    {
        $sql = "
			SELECT *
			FROM zamowienia_szczegoly
			WHERE id_zamowienia = '" . $id . "'
			ORDER BY id_ksiazki DESC";
        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera dane ogólne zamówienia o podanym id
     * @param $id
     * @return array
     */
    public function pobierzZamowienie($id): array
    {
        return $this->db->pobierz('zamowienia', $id);
    }

    /**
     *
     */
    public function pobierzStatus($id): string
    {
        $result = $this->db->pobierz('zamowienia_statusy', $id);
        return $result['nazwa'];
    }


}
