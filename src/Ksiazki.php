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
        return $this->db->pobierz('ksiazki', $id);
    }

    /**
     * Pobiera najlepiej sprzedające się książki.
     *
     */
    public function pobierzBestsellery()
    {
        $sql = "SELECT * FROM ksiazki ORDER BY RAND() LIMIT 5";

        // uzupełnić funkcję
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
     * Pobiera zapytanie SELECT oraz jego parametry;
     *
     * @param array $params
     * @return array
     */
    public function pobierzZapytanie(array $params = []): array
    {
        $parametry = [];
        $sql = "SELECT k.* FROM ksiazki k WHERE 1=1 ";

        // dodawanie warunków do zapytanie
        if (!empty($params['fraza'])) {
            $sql .= "AND k.tytul LIKE :fraza ";
            $parametry['fraza'] = "%$params[fraza]%";
        }
        if (!empty($params['id_kategorii'])) {
            $sql .= "AND k.id_kategorii = :id_kategorii ";
            $parametry['id_kategorii'] = $params['id_kategorii'];
        }

        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['k.tytul', 'k.cena'];
            $kierunki = ['ASC', 'DESC'];
            [$kolumna, $kierunek] = explode(' ', $params['sortowanie']);

            if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
                $sql .= " ORDER BY " . $params['sortowanie'];
            }
        }

        return ['sql' => $sql, 'parametry' => $parametry];
    }

    /**
     * Pobiera stronę z danymi książek.
     *
     * @param string $select
     * @param array  $params
     * @return array
     */
    public function pobierzStrone(string $select, array $params = []): array
    {
        return $this->db->pobierzWszystko($select, $params);
    }
}
