<?php // exit; ?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
        </head>
        <body>

            <?php
                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                // require_once("config/db.php");
                // require_once("classes/ClassSQL.php");
                // require_once("classes/ClassModel.php");
                require_once("classes/ClassTools.php");
                
                // $DB = new ClassSQL(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                // $zapytanie = "SELECT * FROM soldiers";
                
                // $sql = $DB->pdo_fetch($zapytanie);
                // $sql = $DB->pdo_fetch_all($zapytanie);
                // $sql = $DB->pdo_fetch_all_column($zapytanie);
                // $sql = $DB->pdo_fetch_all_group($zapytanie);
                // $sql = $DB->pdo_fetch_all_group_column($zapytanie);
                // $sql = $DB->pobierz_wszystkie($zapytanie);
                
                // print_r($sql);
                
                // $data = array(
                    // "string_test" => "Xas'dC90sółądsfgćźda'fdf\"gdfh",
                    // "string_test2" => "X5dgh`''jgdio;'gosdi"
                // );
                
                // $id = $DB->delete('test_sql', 'id_test = 1');
                // $value = '483 551 222 2224';
                
                // $ClassSoldier = new ClassSoldier();
                // $ClassSoldier->id = '123';
                // $ClassSoldier->validByMethod('isPhone', $value, 'nazwa');
                
                // var_dump($ClassSoldier->id);
                // var_dump($ClassSoldier->load_class);
                
                // echo substr_replace('id_mission','log_',3,0); //xabcd 
                
                require_once("libraries/password_compatibility_library.php");
                // print_r(phpinfo());
                $options = array(
                    'cost' => 12,
                    // 'salt' => 'tesasd123lkasd1231245fgkfnkn!@#!@#@sdfst',
                );
                // print_r($options);
                $p1 = password_hash("rasmuslerd!@#^&żłóorf", PASSWORD_BCRYPT, $options);
                // $p2 = password_hash("rasmuslerdorf", PASSWORD_BCRYPT);
                echo $p1."<br />";
                // echo $p2."<br />";
                var_dump(password_verify('rasmuslerdorf', $p1));
                // var_dump(password_verify('rasmuslerdorf', $p2));
                // echo ClassTools::generateRandomString(12);
                print_r($_SERVER);
            ?>
            
        </body>
    </html>