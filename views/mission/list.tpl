<div id="mission_list" class="container">
    <div class="jumbotron">
        
        <span class="glyphicon glyphicon-star"></span><h6>S.E.W.</h6>
        <h5>Lista Misji</h5>

        <?php

        if (isset($this)) {
            if ($this->errors) {
                foreach ($this->errors as $error) {
                    echo $error;
                }
            }
            if ($this->messages) {
                foreach ($this->messages as $message) {
                    echo $message;
                }
            }
        }
        ?>
        
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_name">Kryptonim Misji</th>
                    <th class="table_rodzaj">Rodzaj Misji</th>
                    <th class="table_lokalizacja">Lokalizacja</th>
                    <th class="table_date_start">Data rozpoczęcia</th>
                    <th class="table_date_end">Data zakończenia</th>
                    <th class="table_status">Status</th>
                    <th class="table_akcje">Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if($values):
                    foreach ($values as $key => $item):
                ?>
                <tr>
                    <td class="soldier_id"><?php echo $key; ?></td>
                    <td class="soldier_imie"><?php echo $item['soldier_imie']; ?></td>
                    <td class="soldier_nazwisko"><?php echo $item['soldier_nazwisko']; ?></td>
                    <td class="soldier_telefon"><?php echo $item['soldier_telefon']; ?></td>
                    <td class="soldier_mail"><?php echo $item['soldier_mail']; ?></td>
                    <td class="soldier_druzyna"><?php echo $item['soldier_druzyna']; ?></td>
                    <td class="soldier_status"><?php echo $item['soldier_status']; ?></td>
                    <td class="soldier_akcje">
                    
                        <a href="soldier.php?action=update" class="soldier_edit" title="Edytuj">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        
                        <a href="#" class="soldier_view" title="Podgląd">
                            <span class="glyphicon glyphicon-zoom-in"></span>
                        </a>
                        
                        <a href="#" class="soldier_equipment" title="Wyposażenie">
                            <span class="glyphicon glyphicon-screenshot"></span>
                        </a>
                        
                        <a href="#" class="soldier_missions" title="Misje">
                            <span class="glyphicon glyphicon-move"></span>
                        </a>
                        
                        <a href="#" class="soldier_traniings" title="Szkolenia">
                            <span class="glyphicon glyphicon-check"></span>
                        </a>
                        
                        <a href="#" class="soldier_orders" title="Odznaczenia">
                            <span class="glyphicon glyphicon-tags"></span>
                        </a>
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                <tr>
                    <td class="table_null" colspan="8">Brak misji do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
            </tbody>
        </table>
        
        <a href="/misje/dodaj" class="btn btn-info mar_button">Dodaj Misje</a>
    </div>
</div>
