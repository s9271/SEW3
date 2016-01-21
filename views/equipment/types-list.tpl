<div id="equipment_type_list" class="new_admin_tpl m_table_list">

    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-12">
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
                        
                            <div class="btn-group-action view_dropdown">
                                <div class="btn-group">
                                    <a href="/typy-wyposazenia/podglad/<?php echo $item['id_equipment_type']; ?>" class="edit btn btn-primary" title="Podgląd">
                                        <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                    </a>
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        
                                        <?php if($this->tpl_values['list_page'] === NULL){ ?>
                                        
                                        <li>
                                            <a href="/typy-wyposazenia/<?php echo $item['id_equipment_type']; ?>" title="Przeglądaj">
                                                <span class="glyphicon glyphicon-folder-open"></span>Przeglądaj podkategorie
                                            </a>
                                        </li>
                                        <li class="divider"> </li>
                                            
                                        <?php } ?>
                                        
                                        <li>
                                            <a href="/typy-wyposazenia/edytuj/<?php echo $item['id_equipment_type']; ?>" title="Edytuj">
                                                <span class="glyphicon glyphicon-pencil"></span>Edytuj
                                            </a>
                                        </li>
                                        <li class="divider"> </li>
                                        <li>
                                            <form method="post">
                                                <input type="hidden" name="id_equipment_type" value="<?php echo $item['id_equipment_type']; ?>" />
                                                <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo htmlspecialchars($item['name']); ?>: Czy na pewno chcesz usunąć typ wyposażenia?" type="submit" name="form_action" value="equipment_type_delete">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                    Usuń
                                                </button>
                                            </form>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                            
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
        </div>
    </div>
    
    <?php $this->getPages(); ?>
    
    <div class="row">
        <div class="col-sm-12 text-right">
            
            <?php if($this->tpl_values['list_page'] === NULL){ ?>

                <a href="/typy-wyposazenia/dodaj" class="btn btn-info mar_button">
                    <i class="fa fa-plus"></i>
                    Dodaj kategorię główną
                </a>
                
            <?php }else{ ?>

                <a href="/typy-wyposazenia" class="btn btn-info mar_button">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    Wstecz
                </a>

                <a href="/typy-wyposazenia/<?php echo $this->tpl_values['list_page']; ?>/dodaj" class="btn btn-info mar_button">
                    <i class="fa fa-plus"></i>
                    Dodaj podkategorię
                </a>
                
            <?php } ?>
        </div>
    </div>
</div>
