<div id="training_list" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Lista szkoleń</h1>
        
        <?php $this->getAlerts(false); ?>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_name">Nazwa szkolenia</th>
                    <th class="table_traning_center">Centrum szkolenia</th>
                    <th class="table_date_start">Data rozpoczęcia</th>
                    <th class="table_date_end">Data zakończenia</th>
                    <th class="table_status">Status</th>
                    <th class="table_akcje">Akcje</th>
                </tr>
                
                <?php echo $this->getSearchForm(); ?>
                
            </thead>
            <tbody>
                <?php 
                    if($this->tpl_values):
                    foreach ($this->tpl_values as $key => $item):
                ?>
                
                <tr>
                    <td class="table_id"><?php echo $item['id_training']; ?></td>
                    <td class="table_name"><?php echo $item['name']; ?></td>
                    <td class="table_traning_center"><?php echo $item['training_center_name']; ?></td>
                    <td class="table_date_start"><?php echo $item['date_start_name']; ?></td>
                    <td class="table_date_end"><?php echo $item['date_end_name']; ?></td>
                    <td class="table_status"><?php echo $item['status']; ?></td>
                    <td class="table_akcje">
                    
                        <form method="post">
                            <input type="hidden" name="id_training" value="<?php echo $item['id_training']; ?>" />
                            
                            <div class="btn-group btn-group-sm">
                                <a href="/szkolenia/podglad/<?php echo $item['id_training']; ?>" class="btn btn-primary" title="Podgląd">Podgląd</a>
                                <a href="/szkolenia/edytuj/<?php echo $item['id_training']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć szkolenie?" type="submit" name="form_action" value="training_delete">Usuń</button>
                            </div>
                        </form>
                        
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                
                <tr>
                    <td class="table_null" colspan="7">Brak szkoleń do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
                
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
        
        <a href="/szkolenia/dodaj" class="btn btn-info mar_button">Dodaj szkolenie</a>
    </div>
</div>
