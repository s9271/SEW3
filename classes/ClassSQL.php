<?php
    /*
        Klasa: ClassSQL
        Wersja: 1.4
        Opis: Obsluga baz danych
        
        Brak praw do kopiowania klasy!!!
        Autor Panek Mariusz
    */

    class ClassSQL{
        private $pdo;
        private $host;
        private $user;
        private $password;
        
        public function __construct($host, $user, $password, $database, $connect = true){
            $this->host = $host;
            $this->user = $user;
            $this->password = $password;
            $this->database = $database;

            if ($connect) {
                $this->connect();
            }
            
            // $this->pdo = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8', $user, $pass);
            // $this->pdo->exec("set names utf8;");
        }

        public function __destruct(){
            $this->disconnect();
        }
        
        public function disconnect(){
            $this->pdo = null;
            unset($this->pdo);
        }
        
        public function connect(){
            try {
                $this->pdo = $this->_getPDO($this->host, $this->user, $this->password, $this->database, 5);
            } catch (PDOException $e) {
                die("Problem z połączeniem się do bazy danych: ".utf8_encode($e->getMessage()));
            }
            
            if ($this->pdo->exec('SET NAMES \'utf8\'') === false) {
                die("Problem z ustawieniem UTF8 w bazie");
            }

            return $this->pdo;
        }
        
        private function _getPDO($host, $user, $password, $dbname, $timeout = 5){
            $dsn = 'mysql:';
            if ($dbname) {
                $dsn .= 'dbname='.$dbname.';';
            }
            if (preg_match('/^(.*):([0-9]+)$/', $host, $matches)) {
                $dsn .= 'host='.$matches[1].';port='.$matches[2];
            } elseif (preg_match('#^.*:(/.*)$#', $host, $matches)) {
                $dsn .= 'unix_socket='.$matches[1];
            } else {
                $dsn .= 'host='.$host;
            }
            $dsn .= ';charset=utf8';

            return new PDO($dsn, $user, $password, array(PDO::ATTR_TIMEOUT => $timeout, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
        }
        
        /* ************ UNIWERSALNE *********** */
        /* ************************************ */
        
            // pobieranie 1 rekorda z bazy
            // $sql -> zapytanie SQL
            // zwracanie: array('name' => 'value', ...)
            public function pdo_fetch($sql){
                $statement = $this->pdo->query($sql);
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                return $row;
            }
            
            // pobieranie wielu rekordow z bazy
            // $sql -> zapytanie SQL
            // zwracanie: array([0] => array('name' => 'value', ...), [1] => array('name' => 'value', ...), ...)
            public function pdo_fetch_all($sql){
                $statement = $this->pdo->query($sql);
                $row = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            
            // pobieranie wielu rekordow z bazy
            // $sql -> zapytanie SQL
            // pobiera tylko wartosc z pierwszej kolumny
            // zwracanie: array([0] => pierwsza_kolumna_value, [1] => pierwsza_kolumna_value, ...)
            public function pdo_fetch_all_column($sql){
                $statement = $this->pdo->query($sql);
                $row = $statement->fetchAll(PDO::FETCH_COLUMN);
                return $row;
            }
            
            // pobieranie wielu rekordow z bazy
            // $sql -> zapytanie SQL
            // pobiera pierwsza kolumne jako klucz arraya
            // zwracanie: array([pierwsza_kolumna_value] => array('name' => 'value', ...), [pierwsza_kolumna_value] => array('name' => 'value', ...), ...)
            public function pdo_fetch_all_group($sql, $reset = true){
                $statement = $this->pdo->query($sql);
                $row = $statement->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
                
                if($reset){
                    $row = array_map('reset', $row);
                }
                return $row;
            }
            
            // pobieranie wielu rekordow z bazy
            // $sql -> zapytanie SQL
            // pobiera pierwsza kolumne jako klucz arraya + druga kolumne jako wartosc
            // zwracanie: array([pierwsza_kolumna_value] => druga_kolumna_value, [pierwsza_kolumna_value] => druga_kolumna_value, ...)
            public function pdo_fetch_all_group_column($sql, $reset = true){
                $statement = $this->pdo->query($sql);
                $row = $statement->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
                
                if($reset){
                    $row = array_map('reset', $row);
                }
                return $row;
            }
            
            // wykonywanie operacji na bazie
            // zwracanie: id ostatnio dodanego rekordu
            public function pdo_query($sql){
                $statement = $this->pdo->query($sql);
                $id = $this->pdo->lastInsertId();
                
                if($id != 0){
                    return $id;
                }
                return false;
            }
        
        /* ************* DODATKOWE ************ */
        /* ************************************ */
        
            public function pobierz($sql){
                return $this->pdo_fetch($sql);
            }
        
            public function pobierz_wszystkie($sql){
                return $this->pdo_fetch_all($sql);
            }
    }
?>