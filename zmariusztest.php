<?php exit; ?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
        </head>
        <body>

            <?php
                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                require_once("config/db.php");
                require_once("classes/ClassSQL.php");
                require_once("classes/ClassModel.php");
                require_once("classes/ClassSoldier.php");
                
                $DB = new ClassSQL(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $zapytanie = "SELECT * FROM soldiers";
                
                // $sql = $DB->pdo_fetch($zapytanie);
                // $sql = $DB->pdo_fetch_all($zapytanie);
                // $sql = $DB->pdo_fetch_all_column($zapytanie);
                // $sql = $DB->pdo_fetch_all_group($zapytanie);
                // $sql = $DB->pdo_fetch_all_group_column($zapytanie);
                // $sql = $DB->pobierz_wszystkie($zapytanie);
                
                // print_r($sql);
                
                $data = array(
                    "string_test" => "Xas'dC90sółądsfgćźda'fdf\"gdfh",
                    "string_test2" => "X5dgh`''jgdio;'gosdi"
                );
                
                // $id = $DB->delete('test_sql', 'id_test = 1');
                $value = '483 551 222 2224';
                
                $ClassSoldier = new ClassSoldier();
                $ClassSoldier->id = '123';
                // $ClassSoldier->validByMethod('isPhone', $value, 'nazwa');
                
                var_dump($ClassSoldier->id);
                var_dump($ClassSoldier->load_class);
            ?>
            
        </body>
    </html>