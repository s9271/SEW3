<?php

class Registration
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

    public function __construct()
    {
        if (isset($_POST["register"])) {
            $this->registerNewUser();
        }
    }


    private function registerNewUser()
    {
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Puste pole Nazwa Użytkownika. Spróbuj ponownie.";
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $this->errors[] = "Puste pole Hasło. Spróbuj ponownie.";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $this->errors[] = "Hasła nie są takie same. Spróbuj ponownie.";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $this->errors[] = "Hasło ma mieć długość co najmniej 6 znaków. Spróbuj ponownie.";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->errors[] = "Nazwa użytkownika nie może być krótsza niż 2 lub dłuższa niż 64 znaków. Spróbuj ponownie.";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->errors[] = "Nazwa użytkownika nie pasuje do schematu. Proszę spróbuj nie używać polskich liter. Spróbuj ponownie.";
        } elseif (empty($_POST['user_email'])) {
            $this->errors[] = "Pole e-mail nie może być puste. Spróbuj ponownie.";
        } elseif (strlen($_POST['user_email']) > 64) {
            $this->errors[] = "Pole e-mail nie może być dłuższe niż 64 znaków. Spróbuj ponownie.";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Twój adres e-mail nie jest w poprawnym formacie e-mail. Spróbuj ponownie.";
        } elseif (!empty($_POST['user_name'])
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

            // change character set to utf8 and check it
            /*if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }*/


            if (!$this->db_connection->connect_errno) {


                $user_name = $this->db_connection->real_escape_string(strip_tags($_POST['user_name'], ENT_QUOTES));
                $user_email = $this->db_connection->real_escape_string(strip_tags($_POST['user_email'], ENT_QUOTES));

                $user_password = $_POST['user_password_new'];

                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

                $sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                $query_check_user_name = $this->db_connection->query($sql);

                if ($query_check_user_name->num_rows == 1) {
                    $this->errors[] = "Niestety, ten adres login / e-mail jest już zajęta. Spróbuj ponownie.";
                } else {

                    $sql = "INSERT INTO users (user_name, user_password_hash, user_email)
                            VALUES('" . $user_name . "', '" . $user_password_hash . "', '" . $user_email . "');";
                    $query_new_user_insert = $this->db_connection->query($sql);


                    if ($query_new_user_insert) {
                        $this->messages[] = "Twoje konto zostało pomyślnie utworzone. Możesz się teraz zalogować.";
                    } else {
                        $this->errors[] = "Niestety, Twoja rejestracja się nie powiodła. Spróbuj ponownie.";
                    }
                }
            } else {
                $this->errors[] = "Niestety, nie ma połączenia z bazą danych. Spróbuj ponownie.";
            }
        } else {
            $this->errors[] = "Wystąpił nieznany błąd. Spróbuj ponownie.";
        }
    }
}
