<div id="soldier_trainings_list" class="new_admin_tpl m_table_list soldier_children_list">

    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-9">
            <form method="post" class="clearfix">
                <table class="table table_add table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_training">*Szkolenia</th>
                            <th class="table_date_add">*Data przypisania do szkolenia</th>
                            <th class="table_akcje">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="table_training">
                                <select class="form-control input-sm jsselectajax" data-ajax-class="<?php echo $this->tpl_values['ajax_class']; ?>" data-ajax-function="<?php echo $this->tpl_values['ajax_function']; ?>" data-id-soldier="<?php echo $this->tpl_values['id_soldier']; ?>" id="form_training" name="form_training" required="required">
                                    <?php echo $this->tpl_values['training_selectes']; ?>
                                    
                                </select>
                                
                            </td>
                            <td class="table_date_add">
                                <input name="form_date" type="text" class="form-control input-sm jsdatetime" id="form_date" placeholder="Data przypisania do szkolenia" required="required" value="<?php echo ((isset($this->tpl_values['form_date']) && $this->tpl_values['form_date'] != '') ? $this->tpl_values['form_date'] : ''); ?>" />
                            </td>
                            <td class="table_akcje">
                                <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                                <button class="btn btn-primary mar_button" type="submit" name="form_action" value="training_add"><span class="glyphicon glyphicon-plus"></span>Przydziel do szkolenia</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_description" colspan="3">
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
            <table class="table table-striped table-bordered table-hover table_list">
                <thead>
                    <tr>
                        <th class="table_id">ID</th>
                        <th class="table_name">Nazwa szkolenia</th>
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
                        <td class="table_id"><?php echo $item['id_soldier2trainings']; ?></td>
                        <td class="table_name"><?php echo $item['name']; ?></td>
                        <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                        <td class="table_date_end"><?php echo $item['date_end']; ?></td>
                        <td class="table_status"><?php echo $item['status_name']; ?></td>
                        <td class="table_akcje<?php echo $item['returned'] == '0' || $this->tpl_values['id_login_permission'] == '1' ? '' : ' returned'; ?>">
                            
                            <form method="post">
                                <div class="btn-group">
                                    <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/szkolenia/podglad/<?php echo $item['id_soldier2trainings']; ?>" class="edit btn btn-primary" title="Podgląd">
                                        <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                    </a>
                                    
                                    <?php if($item['returned'] == '0' || $this->tpl_values['id_login_permission'] == '1'){ ?>
                                    
                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            
                                            <?php if($item['returned'] == '0'){ ?>
                                            
                                            <li>
                                                <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/szkolenia/edytuj/<?php echo $item['id_soldier2trainings']; ?>" title="Edytuj">
                                                    <span class="glyphicon glyphicon-pencil"></span>Edytuj dodatkową informację
                                                </a>
                                            </li>
                                            <li class="divider"> </li>
                                            <li>
                                                <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/szkolenia/odeslij/<?php echo $item['id_soldier2trainings']; ?>" title="Odeślij">
                                                    <span class="glyphicon glyphicon-pencil"></span>Odeślij
                                                </a>
                                            </li>
                                            
                                            <?php } ?>
                                            
                                            <li>
                                                <form method="post">
                                                    <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                                    <input type="hidden" name="id_soldier2trainings" value="<?php echo $item['id_soldier2trainings']; ?>" />
                                                    <input type="hidden" name="id_training" value="<?php echo $item['id_training']; ?>" />
                                                    <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo htmlspecialchars($item['name']); ?>: Czy na pewno chcesz usunąć szkolenie?" type="submit" name="form_action" value="training_delete"><span class="glyphicon glyphicon-trash"></span> Usuń</button>
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
                        <td class="table_null" colspan="6">Żołnierz nie posiada szkoleń</td>
                    </tr>
                    <?php
                        endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php $this->getPages(); ?>
    
    <div class="row margin_top_15">
        <div class="col-sm-12 text-right">
            <a href="/zolnierze" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
            <a href="/zolnierze/podglad/<?php echo $this->tpl_values['id_soldier']; ?>" class="btn btn-primary mar_button">Podgląd żołnierza</a>
        </div>
    </div>
</div>
