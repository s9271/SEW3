<div id="military_list" class="new_admin_tpl m_table_list">
        
    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="table_id">ID</th>
                        <th class="table_number">Numer</th>
                        <th class="table_name">Nazwa</th>
                        <th class="table_lokalizacja">Lokalizacja</th>
                        <th class="table_rodzaj">Rodzaj jednostki</th>
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
                        <td class="table_id"><?php echo $item['id_military']; ?></td>
                        <td class="table_number"><?php echo $item['number']; ?></td>
                        <td class="table_name"><?php echo $item['name']; ?></td>
                        <td class="table_lokalizacja"><?php echo $item['location']; ?></td>
                        <td class="table_rodzaj"><?php echo $item['military_group_name']; ?></td>
                        <td class="table_status"><?php echo $item['active_name']; ?></td>
                        <td class="table_akcje">
                            
                            <div class="btn-group-action view_dropdown">
                                <div class="btn-group">
                                    <a href="/jednostki/podglad/<?php echo $item['id_military']; ?>" class="edit btn btn-primary" title="Podgląd">
                                        <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                    </a>
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="/jednostki/edytuj/<?php echo $item['id_military']; ?>" title="Edytuj">
                                                <span class="glyphicon glyphicon-pencil"></span>Edytuj
                                            </a>
                                        </li>
                                        <li class="divider"> </li>
                                        <li>
                                            <form method="post">
                                                <input type="hidden" name="id_military" value="<?php echo $item['id_military']; ?>" />
                                                <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo htmlspecialchars($item['name']); ?>: Czy na pewno chcesz usunąć jednostkę?" type="submit" name="form_action" value="military_delete">
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
                        <td class="table_null" colspan="7">Brak jednostek do wyświetlenia</td>
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
        <div class="col-sm-12">
            <a href="/jednostki/dodaj" class="btn btn-info pull-right mar_button">
                <i class="fa fa-plus"></i>
                Dodaj jednostkę wojskową
            </a>
        </div>
    </div>
</div>
