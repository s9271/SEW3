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
                
                $DB = new ClassSQL(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $zapytanie = "SELECT * FROM soldiers";
                
                // $sql = $DB->pdo_fetch($zapytanie);
                // $sql = $DB->pdo_fetch_all($zapytanie);
                // $sql = $DB->pdo_fetch_all_column($zapytanie);
                // $sql = $DB->pdo_fetch_all_group($zapytanie);
                // $sql = $DB->pdo_fetch_all_group_column($zapytanie);
                $sql = $DB->pobierz_wszystkie($zapytanie);
                
                print_r($sql);
                
            ?>
            
        </body>
    </html>