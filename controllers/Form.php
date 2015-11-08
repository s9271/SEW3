<?php

class Form
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


    public function __construct($action = 'default')
    {
        $_action = isset($_GET['action']) ? $_GET['action'] : 'default';

        switch ($_action) {
          case 'create-post':
            // add item to DB
            if (isset($_POST) && !empty($_POST)) {
              $this->addItem();
            }
            break;

          case 'list':
            return $this->getAllItems();
            break;

          default:
            # code...
            break;
        }

        if (isset($_POST) && !empty($_POST)) {
          // add item to DB
          $this->addItem();

        } else {


        }
    }


    /**
     *
     */
    private function addItem()
    {
        if (empty($_POST['soldierName']) || empty($_POST['soldierSurname']) || empty($_POST['birthday']) || empty($_POST['sex']) || empty($_POST['phone']) || empty($_POST['email']) || empty($_POST['code']) || empty($_POST['city']) || empty($_POST['street']) || empty($_POST['numberHouse']) || empty($_POST['militaryRank']) || empty($_POST['jwNumber']) || empty($_POST['missions']) || empty($_POST['training']) || empty($_POST['weapon']) || empty($_POST['weaponsNumber']) || empty($_POST['equipmentSoldier']) || empty($_POST['someText'])) {
            $this->errors[] = "Wszystkie pola są wymagane. Spróbuj ponownie.";
        }else {


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
                $sql = "INSERT INTO soldiers (soldierName, soldierSurname, birthday, sex, phone, email, code, city, street, numberHouse, militaryRank, jwNumber, missions, training, weapon, weaponsNumber, equipmentSoldier, someText)
                        VALUES('" . $soldierName . "', '" . $soldierSurname . "', '" . date('dd/mm/yyyy') . "', '" . $sex . "', '" . $phone . "', '" . $email . "', '" . $code . "', '" . $city . "', '" . $street . "', '" . $numberHouse . "', '" . $militaryRank . "', '" . $jwNumber . "', '" . $missions . "', '" . $training . "', '" . $weapon . "', '" . $weaponsNumber . "', '" . $equipmentSoldier . "', '" . $someText . "');";

                $query_new_user_insert = $this->db_connection->query($sql);


                if ($query_new_user_insert) {
                    $this->messages[] = "<h6>Rekord został dodany.</h6>";
                } else {
                    $this->errors[] = "<h6>Spróbuj ponownie.</h6>";
                }
            } else {
                $this->errors[] = "<h6>Nie ma połączenia z bazą danych. Spróbuj ponownie.</h6>";
            }
        }

        $this->redirectToMainPage();
    }

    public function getAllItems()
    {

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
    }


    private function redirectToMainPage()
    {

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'form-list.php';

        header("Location: form-list.php");
        exit;
    }
}
