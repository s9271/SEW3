<div id="equipments_list" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Lista Wyposażenia</h1>
        
        <?php $this->getAlerts(false); ?>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_name">Nazwa</th>
                    <th class="table_type">Typ wyposażenia</th>
                    <th class="table_status">Status</th>
                    <th class="table_akcje">Akcje</th>
                </tr>
                
                <?php echo $this->getSearchForm(); ?>
            </thead>
            <tbody>
                <?php 
                    if($this->tpl_values['items']):
                    foreach ($this->tpl_values['items'] as $key => $item):
                ?>
                <tr>
                    <td class="table_id"><?php echo $item['id_equipment']; ?></td>
                    <td class="table_name"><?php echo $item['name']; ?></td>
                    <td class="table_type"><?php echo $item['equipment_type_name']; ?></td>
                    <td class="table_status"><?php echo $item['active_name']; ?></td>
                    <td class="table_akcje">
                        <form method="post">
                            <input type="hidden" name="id_equipment" value="<?php echo $item['id_equipment']; ?>" />
                            
                            <div class="btn-group btn-group-sm">
                                <a href="/wyposazenie/edytuj/<?php echo $item['id_equipment']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć wyposażenie?" type="submit" name="form_action" value="equipment_delete">Usuń</button>
                            </div>

                        </form>
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                <tr>
                    <td class="table_null" colspan="5">Brak wyposażenia do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
        
        <a href="/wyposazenie/dodaj" class="btn btn-info">Dodaj Wyposażenie</a>
    </div>
</div>
