<div id="equipment_type_list" class="container<?php echo $this->tpl_values['list_page'] ? ' childs' : ''; ?>">
    <div class="jumbotron">
        <h1 class="controller_title">Lista typów wyposażenia<?php echo $this->tpl_values['list_page_name']; ?> - kategorie</h1>
        
        <?php $this->getAlerts(false); ?>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_name">Nazwa</th>
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
                    <td class="table_id"><?php echo $item['id_equipment_type']; ?></td>
                    <td class="table_name"><?php echo $item['name']; ?></td>
                    <td class="table_status"><?php echo $item['active_name']; ?></td>
                    <td class="table_akcje">
                    
                        <form method="post">
                            <input type="hidden" name="id_equipment_type" value="<?php echo $item['id_equipment_type']; ?>" />
                            
                            <div class="btn-group btn-group-sm">
                                <?php if($this->tpl_values['list_page'] === NULL){ ?>
                            
                                    <a href="/typy-wyposazenia/<?php echo $item['id_equipment_type']; ?>" class="btn btn-primary" title="Podgląd">Przeglądaj</a>
                                <?php } ?>
                                
                                <a href="/typy-wyposazenia/edytuj/<?php echo $item['id_equipment_type']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć kategorię typu wyposażenia?" type="submit" name="form_action" value="equipment_type_delete">Usuń</button>
                            </div>
                        </form>
                        
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                <tr>
                    <td class="table_null" colspan="4">Brak kategorii typu wyposażenia do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
            
        <div id="category_buttons" class="clearfix">
        <?php if($this->tpl_values['list_page'] === NULL){ ?>
    
            <a href="/typy-wyposazenia/dodaj" class="btn btn-info mar_button pull-right">Dodaj kategorię główną</a>
        <?php }else{ ?>
            
            <a href="/typy-wyposazenia/" class="btn btn-default mar_button pull-left">Wstecz</a>
            <a href="/typy-wyposazenia/<?php echo $this->tpl_values['list_page']; ?>/dodaj" class="btn btn-info mar_button pull-right">Dodaj podkategorię</a>
        <?php } ?>
        </div>
    </div>
</div>
