<div id="training_center_list" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Lista Centrów szkolenia</h1>
        
        <?php $this->getAlerts(false); ?>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_name">Nazwa</th>
                    <th class="table_location">Lokalizacja</th>
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
                    <td class="table_id"><?php echo $item['id_training_centre']; ?></td>
                    <td class="table_name"><?php echo $item['name']; ?></td>
                    <td class="table_location"><?php echo $item['location']; ?></td>
                    <td class="table_status"><?php echo $item['active_name']; ?></td>
                    <td class="table_akcje">
                        <form method="post">
                            <input type="hidden" name="id_training_centre" value="<?php echo $item['id_training_centre']; ?>" />
                            
                            <div class="btn-group btn-group-sm">
                                <a href="/centra-szkolen/edytuj/<?php echo $item['id_training_centre']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć centrum szkolenia?" type="submit" name="form_action" value="training_centre_delete">Usuń</button>
                            </div>

                        </form>
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                <tr>
                    <td class="table_null" colspan="7">Brak centrów szkolenia do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
        
        <a href="/centra-szkolen/dodaj" class="btn btn-info">Dodaj Centrum szkolenia</a>
    </div>
</div>
