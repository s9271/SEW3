<?php

class User
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
              include("views/user/create.php");
              break;

              case 'list':
                  return $this->getAllItems();
                  break;

          case 'update':
          // update item to DB
              if (isset($_GET) && !empty($_GET)) {
                  $this->updateItem();
              }
              include("views/user/update.php");
              break;

          case 'delete':
              return;

          default:
              # code...
              include("views/user/create.php");
              break;
      }
  }



  private function addItem()
  {
    if (empty($_POST['user_name'])) {
                $this->errors[] = "Puste pole Nazwa Użytkownika. Spróbuj ponownie.";
            }
            elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
                $this->errors[] = "Puste pole Hasło. Spróbuj ponownie.";
            }
            elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
                $this->errors[] = "Hasła nie są takie same. Spróbuj ponownie.";
            }
            elseif (strlen($_POST['user_password_new']) < 6) {
                $this->errors[] = "Hasło ma mieć długość co najmniej 6 znaków. Spróbuj ponownie.";
            }
            elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
                $this->errors[] = "Nazwa użytkownika nie może być krótsza niż 2 lub dłuższa niż 64 znaków. Spróbuj ponownie.";
            }
            elseif (!preg_match('/^[a-z\d]{2,64}[0123a-ce-jyz\^\-]$/i', $_POST['user_name'])) {
                $this->errors[] = "Nazwa użytkownika nie pasuje do schematu. Proszę spróbuj nie używać polskich liter. Spróbuj ponownie.";
            }
            elseif (empty($_POST['user_email'])) {
                $this->errors[] = "Pole e-mail nie może być puste. Spróbuj ponownie.";
            }
            elseif (strlen($_POST['user_email']) > 64) {
                $this->errors[] = "Pole e-mail nie może być dłuższe niż 64 znaków. Spróbuj ponownie.";
            }
            elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Twój adres e-mail nie jest w poprawnym formacie e-mail. Spróbuj ponownie.";
            }
            elseif (!empty($_POST['user_name'])
                && strlen($_POST['user_name']) <= 64
                && strlen($_POST['user_name']) >= 2
                && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
                && !empty($_POST['user_email'])
                && strlen($_POST['user_email']) <= 64
                && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
                && !empty($_POST['user_password_new'])
                && !empty($_POST['user_password_repeat'])
                && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
            ) {
                $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
              }
                if (!$this->db_connection->connect_errno) {


                    $user_name = $this->db_connection->real_escape_string(strip_tags($_POST['user_name'], ENT_QUOTES));
                    $first_name = $this->db_connection->real_escape_string(strip_tags($_POST['first_name'], ENT_QUOTES));
                    $second_name = $this->db_connection->real_escape_string(strip_tags($_POST['second_name'], ENT_QUOTES));
                    $birthday = $this->db_connection->real_escape_string(strip_tags($_POST['birthday'], ENT_QUOTES));
                    $user_email = $this->db_connection->real_escape_string(strip_tags($_POST['user_email'], ENT_QUOTES));
                    $phone = $this->db_connection->real_escape_string(strip_tags($_POST['phone'], ENT_QUOTES));
                    $militaryRank = $this->db_connection->real_escape_string(strip_tags($_POST['militaryRank'], ENT_QUOTES));
                    $code = $this->db_connection->real_escape_string(strip_tags($_POST['code'], ENT_QUOTES));
                    $street = $this->db_connection->real_escape_string(strip_tags($_POST['street'], ENT_QUOTES));
                    $numberHouse = $this->db_connection->real_escape_string(strip_tags($_POST['numberHouse'], ENT_QUOTES));
                    $jwNumber = $this->db_connection->real_escape_string(strip_tags($_POST['jwNumber'], ENT_QUOTES));


                    $user_password = $_POST['user_password_new'];

                    $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

                    $sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                    $query_check_user_name = $this->db_connection->query($sql);

                    if ($query_check_user_name->num_rows == 1) {
                        $this->errors[] = "Niestety, ten adres login / e-mail jest już zajęta. Spróbuj ponownie.";
                    }
                    else {

                        $sql = "INSERT INTO users (user_name, first_name, second_name, birthday, user_password_hash, user_email, phone, militaryRank, code, street, numberHouse, jwNumber)
                                VALUES('" . $user_name . "', '" . $first_name . "', '" . $second_name . "', '" . $birthday . "', '" . $user_password_hash . "', '" . $user_email . "', '" . $phone . "'
                              , '" . $militaryRank . "', '" . $code . "', '" . $street . "', '" . $numberHouse . "', '" . $jwNumber . "');";
                        $query_new_user_insert = $this->db_connection->query($sql);


                        if ($query_new_user_insert) {
                            $this->messages[] = "Konto zostało pomyślnie utworzone.";
                        }
                        else {
                            $this->errors[] = "Niestety, nie powiodło się. Spróbuj ponownie.";
                        }
                    }
                }
                else {
                    $this->errors[] = "Niestety, nie ma połączenia z bazą danych. Spróbuj ponownie.";
                }

            /*else {
                $this->errors[] = "Wystąpił nieznany błąd. Spróbuj ponownie.";
            }*/
        }

        public function getAllItems()
        {
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if (!$this->db_connection->connect_errno) {
                $limit = 10;
                $sql = "SELECT * FROM users";
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
            include("views/user/list.php");
            return;
        }

        private function redirectToMainPage()
        {

            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'user.php?action=list';

            header("Location: " . $extra);
            exit;
        }







}
