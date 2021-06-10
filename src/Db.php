<?php

namespace Ibd;

/**
 * Klasa obsługująca połączenie z bazą danych MySQL.
 *
 */
class Db
{
    /**
     * Dane dostępowe do bazy.
     */
    private string $dbLogin = 'root';
    private string $dbPassword = '';
    private string $dbHost = 'localhost';
    private string $dbName = 'ibd';

    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO("mysql:host={$this->dbHost};dbname={$this->dbName}", $this->dbLogin, $this->dbPassword);
        $this->pdo->query("SET NAMES utf8");
    }

    /**
     * Wykonuje podane zapytanie i zwraca wynik w postaci talicy.
     *
     * @param            $sql    string Zapytanie SQL
     * @param array|null $params Tablica z parametrami zapytania
     * @return array Tablica z danymi
     */
    public function pobierzWszystko(string $sql, ?array $params = null): ?array
    {
        $stmt = $this->pdo->prepare($sql);

        if (!empty($params) && is_array($params)) {
            foreach ($params as $k => &$v)
                $stmt->bindParam($k, $v);
        }

        if (!$stmt->execute()) {
            throw new \RuntimeException("Failed to execute [$sql] {$stmt->errorInfo()[2]}");
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Pobiera rekord o podanym ID z wybranej tabeli.
     *
     * @param string  $table
     * @param integer $id
     * @return array
     */
    public function pobierz(string $table, int $id): ?array
    {
        $sql = "SELECT * FROM $table WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]) ? $stmt->fetch(\PDO::FETCH_ASSOC) : null;
    }

    /**
     * Pobiera dane książki o podanym ID z koszyka
     * @param string $table
     * @param int $id
     * @return array|null
     */
    public function pobierzKsiazkeZKoszyka(string $table, int $id): ?array
    {
        $sql = "SELECT * FROM $table WHERE id_ksiazki = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]) ? $stmt->fetch(\PDO::FETCH_ASSOC) : null;
    }

    /**
     * Liczy rekordy zwrócone przez zapytanie.
     *
     * @param string $sql
     * @param array  $params
     * @return int
     */
    public function policzRekordy(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);

        if (!empty($params) && is_array($params)) {
            foreach($params as $k => $v) {
                $stmt->bindParam($k, $v);
            }
        }
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * Dodaje rekord o podanych parametrach do wybranej tabeli.
     *
     * @param string $tabela
     * @param array  $params
     * @return int
     */
    public function dodaj(string $tabela, array $params): int
    {
        $klucze = array_keys($params);
        $sql = "INSERT INTO $tabela (";
        $sql .= implode(', ', $klucze);
        $sql .= ") VALUES (";

        array_walk($klucze, function(&$elem, $klucz) {
            $elem = ":$elem";
        });
        $sql .= implode(', ', $klucze);
        $sql .= ")";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $this->pdo->lastInsertId();
    }

    /**
     * Usuwa rekord o podanym id z wybranej tabeli.
     *
     * @param string $tabela
     * @param int    $id
     * @return bool
     */
    public function usun(string $tabela, int $id): bool
    {
        $sql = "DELETE FROM $tabela WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Aktualizuje rekord w wybranej tabeli o podanym id.
     *
     * @param string $tabela
     * @param array  $params
     * @param int    $id
     * @return bool
     */
    public function aktualizuj(string $tabela, array $params, int $id): bool
    {
        $sql = "UPDATE $tabela SET ";
        foreach ($params as $k => $v) {
            $sql .= "$k = :$k, ";
        }

        $sql = substr($sql, 0, -2);
        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $params['id'] = $id;
        return $stmt->execute($params);
    }

    /**
     * Wykonuje podane zapytanie SQL z parametrami.
     *
     * @param string $sql
     * @param array  $params
     * @return bool
     */
    public function wykonaj(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Sprawdza czy użytkownik o danym loginie istnieje
     */

    public function sprawdz_login(string $login)
    {
        $sql = "SELECT count(*) as czy_istnieje FROM uzytkownicy WHERE login = :login";
        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([':login' => $login]) ? $stmt->fetch(\PDO::FETCH_ASSOC) : null;
        return $result['czy_istnieje'];
    }

    /**
     * Sprawdza czy użytkownik o danym emailu istnieje
     */

    public function sprawdz_email(string $email)
    {
        $sql = "SELECT count(*) as czy_istnieje FROM uzytkownicy WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([':email' => $email]) ? $stmt->fetch(\PDO::FETCH_ASSOC) : null;
        return $result['czy_istnieje'];
    }

    /**
     * Sprawdza ile książek napisał dany autor.
     * @param int $id
     * @return int|mixed
     */
    public function sprawdz_liczbe_ksiazek(int $id)
    {
        $sql = "SELECT id_autora, count(*) as liczba_ksiazek FROM ksiazki WHERE id_autora = :id GROUP BY id_autora";
        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([':id' => $id]) ? $stmt->fetch(\PDO::FETCH_ASSOC) : 0;
        if(isset($result['liczba_ksiazek']))
            return $result['liczba_ksiazek'];
        else
            return 0;
    }


    /**
     * Sprawdza liczbę książek w danej kategorii.
     * @param int $id
     * @return int|mixed
     */
    public function sprawdz_liczbe_ksiazek_w_kategorii(int $id)
    {
        $sql = "SELECT id_kategorii, count(*) as liczba_ksiazek FROM ksiazki WHERE id_kategorii = :id GROUP BY id_kategorii";
        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([':id' => $id]) ? $stmt->fetch(\PDO::FETCH_ASSOC) : 0;
        if(isset($result['liczba_ksiazek']))
            return $result['liczba_ksiazek'];
        else
            return 0;
    }
}
