<?php

class Soldier
{

    /**
     * @var object $db_connection The database connection
     */
    private $db_connection = null;

    /**
     * @var array $errors Collection of error messages
     */
    public $errors = array();

    /**
     * @var array $messages Collection of success / neutral messages
     */
    public $messages = array();


    public $items = array();

    public function __construct()
    {
        $_action = isset($_GET['action']) ? $_GET['action'] : 'default';

        switch ($_action) {
            case 'create':
                // add item to DB
                if (isset($_POST) && !empty($_POST)) {
                    $this->addItem();
                }
                include("views/soldier/create.php");
                break;

            case 'list':
                return $this->getAllItems();
                break;

            case 'update':
            // update item to DB
                if (isset($_GET)) {
                    $this->updateItem();
                }
                include("views/soldier/update.php");
                break;

            case 'delete':
                return;

            default:
                # code...
                include("views/soldier/create.php");
                break;
        }
    }

    private function addItem()
    {
        if (empty($_POST['soldierName']) || empty($_POST['soldierSurname']) ) {
            $this->errors[] = "Wszystkie pola są wymagane. Spróbuj ponownie.";
        }
        else {
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

              // change character set to utf8 and check it
              if (!$this->db_connection->set_charset("utf8")) {
                  $this->errors[] = $this->db_connection->error;
              }

            if (!$this->db_connection->connect_errno) {

                // escaping, additionally removing everything that could be (html/javascript-) code
                $soldierName = $this->db_connection->real_escape_string(strip_tags($_POST['soldierName'], ENT_QUOTES));
                $soldierSurname = $this->db_connection->real_escape_string(strip_tags($_POST['soldierSurname'], ENT_QUOTES));
                $birthday = $this->db_connection->real_escape_string(strip_tags($_POST['birthday'], ENT_QUOTES));
                $sex = $this->db_connection->real_escape_string(strip_tags($_POST['sex'], ENT_QUOTES));
                $phone = $this->db_connection->real_escape_string(strip_tags($_POST['phone'], ENT_QUOTES));
                $email = $this->db_connection->real_escape_string(strip_tags($_POST['email'], ENT_QUOTES));
                $code = $this->db_connection->real_escape_string(strip_tags($_POST['code'], ENT_QUOTES));
                $city = $this->db_connection->real_escape_string(strip_tags($_POST['city'], ENT_QUOTES));
                $street = $this->db_connection->real_escape_string(strip_tags($_POST['street'], ENT_QUOTES));
                $numberHouse = $this->db_connection->real_escape_string(strip_tags($_POST['numberHouse'], ENT_QUOTES));
                $militaryRank = $this->db_connection->real_escape_string(strip_tags($_POST['militaryRank'], ENT_QUOTES));
                $jwNumber = $this->db_connection->real_escape_string(strip_tags($_POST['jwNumber'], ENT_QUOTES));
                $missions = $this->db_connection->real_escape_string(strip_tags($_POST['missions'], ENT_QUOTES));
                $training = $this->db_connection->real_escape_string(strip_tags($_POST['training'], ENT_QUOTES));
                $weapon = $this->db_connection->real_escape_string(strip_tags($_POST['weapon'], ENT_QUOTES));
                $weaponsNumber = $this->db_connection->real_escape_string(strip_tags($_POST['weaponsNumber'], ENT_QUOTES));
                $equipmentSoldier = $this->db_connection->real_escape_string(strip_tags($_POST['equipmentSoldier'], ENT_QUOTES));
                $someText = $this->db_connection->real_escape_string(strip_tags($_POST['someText'], ENT_QUOTES));

                // write new item data into database
                $sql = "INSERT INTO soldiers
                          (soldierName, soldierSurname, birthday, sex, phone, email, code, city, street, numberHouse, militaryRank, jwNumber, missions, training, weapon, weaponsNumber, equipmentSoldier, someText )
                        VALUES
                          ('" . $soldierName . "', '" . $soldierSurname . "', '" . $birthday . "', '" . $sex . "', '" . $phone . "', '" . $email . "', '" . $code . "', '" . $city . "', '" . $street . "',
                          '" . $numberHouse . "', '" . $militaryRank . "', '" . $jwNumber . "', '" . $missions . "', '" . $training . "', '" . $weapon . "', '" . $weaponsNumber . "', '" . $equipmentSoldier . "', '" . $someText . "');
                        ";

                $query_new_insert = $this->db_connection->query($sql);


                if ($query_new_insert) {
                    $this->messages[] = "Rekord został dodany.";
                } else {
                    $this->errors[] = "Spróbuj ponownie.";
                }
            } else {
                $this->errors[] = "Nie ma połączenia z bazą danych. Spróbuj ponownie.";
            }
        }
  }


    /**
     * Get all records from table SOLDIERS
     *//* 
    public function getAllItems()
    {
        global $DB;
        
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$this->db_connection->connect_errno) {

            $sql = "SELECT * FROM soldiers";
            $result = $this->db_connection->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $this->items[] = $row;
                }
            } else {
                $this->errors[] = "0 results";
            }
        } else {
            $this->errors[] = "Nie ma połączenia z bazą danych.";
        }
        
        $wyj = $DB->pdo_fetch_all($sql);
        
        print_r($wyj);

        // draw view
        include("views/soldier/list.php");
        return;
    } */
    
    public function getAllItems()
    {
        global $DB;
        $zapytanie = "SELECT * FROM soldiers";
        
        $sql = $DB->pdo_fetch_all($zapytanie);
        
        if(!$sql || !is_array($sql) || count($sql) < 1){
            $this->errors[] = "Brak żołnierzy";
            $this->items = false;
        }else{
            foreach($sql as $wyj){
                $this->items[$wyj['id']]['soldier_imie'] = $wyj['soldierName'];
                $this->items[$wyj['id']]['soldier_nazwisko'] = $wyj['soldierSurname'];
                $this->items[$wyj['id']]['soldier_telefon'] = $wyj['phone'];
                $this->items[$wyj['id']]['soldier_mail'] = $wyj['email'];
                $this->items[$wyj['id']]['soldier_druzyna'] = '';
                $this->items[$wyj['id']]['soldier_misja'] = '';
            }
        }

        // draw view
        include("views/soldier/list.php");
        return;
    }

    /*funkcja update*/
    private function updateItem()
    {
      if ((!empty($_GET['soldierName'])) || (!empty($_GET['soldierSurname'])) ) {
        $this->errors[] = "Wszystkie pola są wymagane. Spróbuj ponownie.";
    }

      else{
      $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

      if (!$this->db_connection->connect_errno) {
        $sql="select * from soldiers";
        /*
        $sql = "UPDATE  soldiers SET soldierName='$soldierName', soldierSurname='$soldierSurname', birthday='$birthday', sex='$sex', phone='$phone', email='$email',
        code='$code', city='$city', street='$street', numberHouse='$numberHouse', militaryRank='$militaryRank', jwNumber='$jwNumber', missions='$missions',
        training='$training', equipment='$equipment', weapon='$weapon', weaponsNumber='$weaponsNumber', equipmentSoldier='$equipmentSoldier', someText='$someText'";
        mysql_result($sql);
        */
         $result = $this->db_connection->query($sql);
       }
    }
  }


    /*koniec funkcji update*/

    private function redirectToMainPage()
    {

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'soldier.php?action=list';

        header("Location: " . $extra);
        exit;
    }




}
