<div id="mission_list" class="new_admin_tpl m_table_list">

    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-12">
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
                    
                    <?php echo $this->getSearchForm(); ?>
                    
                </thead>
                <tbody>
                    <?php 
                        if($this->tpl_values):
                        foreach ($this->tpl_values as $key => $item):
                    ?>
                    <tr>
                        <td class="table_id"><?php echo $item['id_mission']; ?></td>
                        <td class="table_name"><?php echo $item['name']; ?></td>
                        <td class="table_rodzaj"><?php echo $item['mission_type_name']; ?></td>
                        <td class="table_lokalizacja"><?php echo $item['location']; ?></td>
                        <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                        <td class="table_date_end"><?php echo $item['date_end_name']; ?></td>
                        <td class="table_status"><?php echo $item['status']; ?></td>
                        <td class="table_akcje">
                            
                            <div class="btn-group-action view_dropdown">
                                <div class="btn-group">
                                    <a href="/misje/podglad/<?php echo $item['id_mission']; ?>" class="edit btn btn-primary" title="Podgląd">
                                        <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                    </a>
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="/misje/edytuj/<?php echo $item['id_mission']; ?>" title="Edytuj">
                                                <span class="glyphicon glyphicon-pencil"></span>Edytuj
                                            </a>
                                        </li>
                                        <li class="divider"> </li>
                                        <li>
                                            <form method="post">
                                                <input type="hidden" name="id_mission" value="<?php echo $item['id_mission']; ?>" />
                                                <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo htmlspecialchars($item['name']); ?>: Czy na pewno chcesz usunąć misję?" type="submit" name="form_action" value="mission_delete">
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
                        <td class="table_null" colspan="8">Brak misji do wyświetlenia</td>
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
            <a href="/misje/dodaj" class="btn btn-info pull-right mar_button">
                <i class="fa fa-plus"></i>
                Dodaj misję
            </a>
        </div>
    </div>
</div>
