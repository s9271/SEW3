<div id="soldier_missions_list" class="container soldier_children_list">
    <div class="jumbotron">
        <h1 class="controller_child_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <div class="row">
            <div class="col-sm-6">
                <form method="post" class="clearfix">
                    <table class="table table_add table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="table_mission">*Misja</th>
                                <th class="table_akcje">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table_mission">
                                    <select class="form-control input-sm jsselectajax" data-ajax-class="<?php echo $this->tpl_values['ajax_class']; ?>" data-ajax-function="<?php echo $this->tpl_values['ajax_function']; ?>" data-id-soldier="<?php echo $this->tpl_values['id_soldier']; ?>" id="form_mission" name="form_mission" required="required">
                                        <?php echo $this->tpl_values['mission_selectes']; ?>
                                        
                                    </select>
                                    
                                </td>
                                <td class="table_akcje">
                                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                                    <button class="btn btn-primary mar_button" type="submit" name="form_action" value="mission_add"><span class="glyphicon glyphicon-plus"></span>Przydziel do misji</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="table_description" colspan="2">
                                    <label for="add_form_description" class="control-label">Dodatkowe informacje (opcjonalnie):</label>
                                    <textarea name="form_description" class="form-control input-sm" placeholder="Dodatkowe informacje (opcjonalnie)"><?php echo ((isset($this->tpl_values['form_description']) && $this->tpl_values['form_description'] != '') ? $this->tpl_values['form_description'] : ''); ?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_id">ID</th>
                            <th class="table_name">Nazwa misji</th>
                            <th class="table_date_start">Data rozpoczęcia</th>
                            <th class="table_date_end">Data zakończenia</th>
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
                            <td class="table_id"><?php echo $item['id_soldier2missions']; ?></td>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                            <td class="table_date_end"><?php echo $item['date_end']; ?></td>
                            <td class="table_status"><?php echo $item['status_name']; ?></td>
                            <td class="table_akcje<?php echo $item['detached'] == '0' || $this->tpl_values['id_login_permission'] == '1' ? '' : ' detached'; ?>">
                                
                                <form method="post">
                                    <div class="btn-group">
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/misje/podglad/<?php echo $item['id_soldier2missions']; ?>" class="edit btn btn-primary" title="Podgląd">
                                            <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                        </a>
                                        
                                        <?php if($item['detached'] == '0' || $this->tpl_values['id_login_permission'] == '1'){ ?>
                                        
                                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                <span class="glyphicon glyphicon-chevron-down"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/misje/edytuj/<?php echo $item['id_soldier2missions']; ?>" title="Edytuj">
                                                        <span class="glyphicon glyphicon-pencil"></span>Edytuj dodatkową informację
                                                    </a>
                                                </li>
                                                <li class="divider"> </li>
                                                
                                                <?php if($item['detached'] == '0'){ ?>
                                                
                                                <li>
                                                    <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/misje/oddeleguj/<?php echo $item['id_soldier2missions']; ?>" title="Oddeleguj">
                                                        <span class="glyphicon glyphicon-pencil"></span>Oddeleguj
                                                    </a>
                                                </li>
                                                
                                                <?php } ?>
                                                
                                                <li>
                                                    <form method="post">
                                                        <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                                        <input type="hidden" name="id_soldier2missions" value="<?php echo $item['id_soldier2missions']; ?>" />
                                                        <input type="hidden" name="id_mission" value="<?php echo $item['id_mission']; ?>" />
                                                        <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo htmlspecialchars($item['name']); ?>: Czy na pewno chcesz usunąć misję?" type="submit" name="form_action" value="mission_delete"><span class="glyphicon glyphicon-trash"></span> Usuń</button>
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
                            <td class="table_null" colspan="6">Żołnierz nie posiada misji</td>
                        </tr>
                        <?php
                            endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php $this->getPages(); ?>
        
        <a href="/zolnierze" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
        <a href="/zolnierze/podglad/<?php echo $this->tpl_values['id_soldier']; ?>" class="btn btn-primary mar_button">Podgląd żołnierza</a>
    </div>
</div>
