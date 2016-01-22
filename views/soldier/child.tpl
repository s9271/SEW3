<div id="soldier_child_list" class="new_admin_tpl m_table_list soldier_children_list">

    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-8">
            <form method="post" class="clearfix">
                <table class="table table_add table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">*Imię</th>
                            <th class="table_surname">*Nazwisko</th>
                            <th class="table_birthday">*Data urodzenia</th>
                            <th class="table_akcje">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="table_name">
                                <input id="form_name" class="form-control input-sm" type="text" placeholder="Imię" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
                            </td>
                            <td class="table_surname">
                                <input id="form_surname" class="form-control input-sm" type="text" placeholder="Nazwisko" name="form_surname" required="required" value="<?php echo ((isset($this->tpl_values['form_surname']) && $this->tpl_values['form_surname'] != '') ? $this->tpl_values['form_surname'] : ''); ?>" />
                            </td>
                            <td class="table_birthday">
                                <input id="form_birthday" class="form-control input-sm jsdate" type="text" placeholder="Data urodzenia" name="form_birthday" required="required" value="<?php echo ((isset($this->tpl_values['form_birthday']) && $this->tpl_values['form_birthday'] != '') ? $this->tpl_values['form_birthday'] : ''); ?>" />
                            </td>
                            <td class="table_akcje">
                                <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                                <button class="btn btn-primary mar_button" type="submit" name="form_action" value="children_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
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
                        <th class="table_name">Imię</th>
                        <th class="table_surname">Nazwisko</th>
                        <th class="table_birthday">Data urodzenia</th>
                        <th class="table_akcje">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if($this->tpl_values['items']):
                        foreach ($this->tpl_values['items'] as $key => $item):
                    ?>
                    <tr>
                        <td class="table_id"><?php echo $item['id_children']; ?></td>
                        <td class="table_name"><?php echo $item['name']; ?></td>
                        <td class="table_surname"><?php echo $item['surname']; ?></td>
                        <td class="table_birthday"><?php echo $item['date_birthday']; ?></td>
                        <td class="table_akcje">
                            
                            <form method="post">
                                <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                <input type="hidden" name="id_children" value="<?php echo $item['id_children']; ?>" />
                                
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć dziecko?" type="submit" name="form_action" value="children_delete">Usuń</button>
                                </div>

                            </form>
                            
                        </td>
                    </tr>
                    <?php
                        endforeach;
                        else:
                    ?>
                    <tr>
                        <td class="table_null" colspan="5">Żołnierz nie posiada dzieci</td>
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
