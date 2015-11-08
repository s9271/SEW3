<?php
$sql = 'SELECT * FROM `tabela` WHERE `id` = 5' ;
$result = mysql_query($sql);
$obj = mysql_fetch_object($result);
?>
/* to co dostajesz z bazy wrzucasz do jakiegos formularza */
<form method="post">
<input type="text" name="nazwa" value="<?=$obj->nazwa;?>" />
<input type="hidden" name="id" value="<?=$obj->id;?>" />
<input type="submit" value="Zmień" name="edit" />
</form>
<?php
if(isset($_POST['submit'] && $_POST['submit'] == 'edit') {
  $sql = 'UPDATE `tabela` SET `nazwa` = \'' . $_POST['nazwa'] . '\' WHERE `id` =' . $_POST['id'];
$query = mysql_query($sql);
echo $query ? 'zmieniono pomyślnie':'nastąpił błąd';
}
?>
<?php

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
