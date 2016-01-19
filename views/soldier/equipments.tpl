<div id="soldier_eqipments_list" class="container soldier_children_list">
    <div class="jumbotron">
        <h1 class="controller_child_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover table_items">
                    <thead>
                        <tr>
                            <th class="table_id">ID</th>
                            <th class="table_name">Nazwa wyposażenia</th>
                            <th class="table_name_type">Typ wyposażenia</th>
                            <th class="table_count">Ilość</th>
                            <th class="table_status">Status</th>
                            <th class="table_akcje">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if($this->tpl_values['items']):
                            foreach ($this->tpl_values['items'] as $key => $item):
                        ?>
                        <tr>
                            <td class="table_id"><?php echo $item['id_soldier2equipments']; ?></td>
                            <td class="table_name"><?php echo $item['equipment_name']; ?></td>
                            <td class="table_name_type"><?php echo $item['equipment_name_type']; ?></td>
                            <td class="table_count"><?php echo $item['equipment_count']; ?></td>
                            <td class="table_status"><?php echo $item['status_name']; ?></td>
                            <td class="table_akcje<?php echo $item['returned'] == '0' || $this->tpl_values['id_login_permission'] == '1' ? '' : ' returned'; ?>">
                                
                                <form method="post">
                                    <div class="btn-group">
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/wyposazenie/podglad/<?php echo $item['id_soldier2equipments']; ?>" class="edit btn btn-primary" title="Podgląd">
                                            <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                        </a>
                                        
                                        <?php if($item['returned'] == '0' || $this->tpl_values['id_login_permission'] == '1'){ ?>
                                        
                                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                <span class="glyphicon glyphicon-chevron-down"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/wyposazenie/edytuj/<?php echo $item['id_soldier2equipments']; ?>" title="Edytuj">
                                                        <span class="glyphicon glyphicon-pencil"></span>Edytuj
                                                    </a>
                                                </li>
                                                <li class="divider"> </li>
                                                
                                                <?php if($item['returned'] == '0'){ ?>
                                                
                                                <li>
                                                    <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/wyposazenie/zwroc/<?php echo $item['id_soldier2equipments']; ?>" title="Zwróć">
                                                        <span class="glyphicon glyphicon-pencil"></span>Zwróć
                                                    </a>
                                                </li>
                                                
                                                <?php } ?>
                                                
                                                <li>
                                                    <form method="post">
                                                        <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                                        <input type="hidden" name="id_soldier2equipments" value="<?php echo $item['id_soldier2equipments']; ?>" />
                                                        <input type="hidden" name="id_equipment" value="<?php echo $item['id_equipment']; ?>" />
                                                        <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo htmlspecialchars($item['equipment_name']); ?>: Czy na pewno chcesz usunąć wyposażenie?" type="submit" name="form_action" value="equipment_delete"><span class="glyphicon glyphicon-trash"></span> Usuń</button>
                                                    </form>
                                                </li>
                                            </ul>
                                            
                                        <?php } ?>
                                        
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                        <?php
                            endforeach;
                            else:
                        ?>
                        <tr>
                            <td class="table_null" colspan="5">Żołnierz nie posiada wyposażenia</td>
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
                <a href="/zolnierze" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
                <a href="/zolnierze/podglad/<?php echo $this->tpl_values['id_soldier']; ?>" class="btn btn-primary mar_button">Podgląd żołnierza</a>
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/wyposazenie/dodaj" class="btn btn-primary mar_button pull-right"><span class="glyphicon glyphicon-plus"></span> Dodaj wyposażenie</a>
            </div>
        </div>
        
    </div>
</div>
