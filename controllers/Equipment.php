<?php

class Equipment
{
    private $limit = 10;
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
                include("views/equipment/create.php");
                break;

            case 'list':
                return $this->getAllItems();
                break;

            case 'update':
            // update item to DB
                if (isset($_GET) && !empty($_GET)) {
                    $this->updateItem();
                }
                include("views/equipment/update.php");
                break;

            case 'delete':
                return;

            default:
                # code...
                include("views/equipment/create.php");
                break;
        }
    }

    /**
     *
     */
    private function addItem()
    {
        if (empty($_POST['equipmentMark']) || empty($_POST['equipmentSerial']) ) {
            $this->errors[] = "Wszystkie pola są wymagane. Spróbuj ponownie.";
        }else {
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            if (!$this->db_connection->connect_errno) {

                // escaping, additionally removing everything that could be (html/javascript-) code
            //  $equipmentNumber = $this->db_connection->real_escape_string(strip_tags($_POST['equipmentNumber'], ENT_QUOTES));
                $equipmentType = $this->db_connection->real_escape_string(strip_tags($_POST['equipmentType'], ENT_QUOTES));
                $equipmentMark = $this->db_connection->real_escape_string(strip_tags($_POST['equipmentMark'], ENT_QUOTES));
                $equipmentSerial = $this->db_connection->real_escape_string(strip_tags($_POST['equipmentSerial'], ENT_QUOTES));
             //   $equipmentDate = $this->db_connection->real_escape_string(strip_tags($_POST['equipmentDate'], ENT_QUOTES));
               // $equipmentDate1 = $this->db_connection->real_escape_string(strip_tags($_POST['equipmentDate1'], ENT_QUOTES));


                // write new item data into database
                $sql = "INSERT INTO equipment
                          ( equipmentType, equipmentMark, equipmentSerial)
                        VALUES
                          ('" . $equipmentType . "', '" . $equipmentMark . "', '" . $equipmentSerial . "');
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

//        $this->redirectToMainPage();
    }


    /**
     * Get all records from table SOLDIERS
     */
    public function getAllItems()
    {
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$this->db_connection->connect_errno) {

            $sql = "SELECT * FROM equipment";
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


        // draw view
        include("views/equipment/list.php");
        return;
    }

    private function redirectToMainPage()
    {

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'equipment.php?action=list';

        header("Location: " . $extra);
        exit;
    }




}
