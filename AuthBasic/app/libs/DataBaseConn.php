<?php

class DataBaseConn {
    private string $host;
    private string $user;
    private string $pass;
    private string $database;

    private $conn;

    public function __construct(string $host, string $user, string $pass, string $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
    }

    /** Funkcja nawiązuje połączenie z bazą danych */
    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    /** Funkcja zamyka połączenie z bazą danych */
    public function disconnect() {
        $this->conn->close();
    }

    /**
     * Funkcja wstawia rekordy do bazy danych.
     * Za pomodą metody implode() tablice rozbijane są na string'i,
     * które potem są używanie to stworzenia zapytania do bazy danych
     * @param string $table Nazwa tabeli w bazie
     * @param array $col Nazwy kolumn w tabeli do których mają zostać przypisane wartości
     * @param array $val Wartości, które zostaną przypisane do rekordu
     */
    public function put(string $table, $col, $val) {
        if ($col === null || $val === null) {
            die("Columns and values must be provided!");
        }

        if (!is_array($col) || !is_array($val)) {
            die("Columns and values must be arrays!");
        }

        $col = implode(", ", $col);
        $val = "'" . implode("', '", $val) . "'";

        $sql = "INSERT INTO $table ($col) VALUES ($val)";
        $result = $this->conn->query($sql);

        if ($result === false) {
            die("Query failed: " . $this->conn->error);
        }

        // return $this->conn->insert_id;
    }

    /**
     * Funkcja wybiera rekordy z bazy danych.
     * Za pomocą metody implode() rozbijane są na string'i,
     * które potem są używanie to stworzenia zapytania do bazy danych
     * Jest możliwość podania dodatkowej opcji WHERE, która pozwoli
     * uszczegółowić zapytanie
     * @param string $table Nazwa tabeli w bazie
     * @param array $col Nazwy kolumn w tabeli, z których odczytane mają być dane
     * @param array $options Daje możliwość użycia klauzuli WHERE
     * @return array $data Tablica z rekordami
     */
    public function get(string $table, $col = array(), $options = array()) {
        if (!is_array($col)) {
            die("Columns must be an array!");
        }
    
        $col = $col ? implode(", ", $col) : '*';
        $whereClause = isset($options['where']) ? 'WHERE ' . $options['where'] : '';
    
        $sql = "SELECT $col FROM $table $whereClause";
        $result = $this->conn->query($sql);
    
        if ($result === false) {
            die("Query failed: " . $this->conn->error);
        }
    
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    
        return $data;
    }
}