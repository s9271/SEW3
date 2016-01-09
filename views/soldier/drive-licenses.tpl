<div id="soldier_drive_licenses_list" class="container soldier_children_list">
    <div class="jumbotron">
        <h1 class="controller_child_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <div class="row">
            <div class="col-sm-8">
                <form method="post" class="clearfix">
                    <table class="table table_add table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="table_category">Kategoria</th>
                                <th class="table_date_start">Data ważności od</th>
                                <th class="table_date_end">Data ważności do</th>
                                <th class="table_akcje">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table_category">
                                    <select class="input-sm jsselect" id="form_drive_category" name="form_drive_category" required="required">
                                        <option value="">Wybierz</option>
                                        <?php
                                            if (isset($this->tpl_values['drive_categories']) && $this->tpl_values['drive_categories']){
                                                foreach ($this->tpl_values['drive_categories'] as $key_drive_category => $name) {
                                                    echo '<option value="'.$key_drive_category.'"'.((isset($this->tpl_values['form_drive_category']) && $this->tpl_values['form_drive_category'] == $key_drive_category) ? ' selected="selected"' : '').'>'.htmlspecialchars($name).'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td class="table_date_start">
                                    <input id="form_date_start" class="form-control input-sm jsdate" type="text" placeholder="Data ważności od" name="form_date_start" required="required" value="<?php echo ((isset($this->tpl_values['form_date_start']) && $this->tpl_values['form_date_start'] != '') ? $this->tpl_values['form_date_start'] : ''); ?>" />
                                </td>
                                <td class="table_date_end">
                                    <input id="form_date_end" class="form-control input-sm jsdate" type="text" placeholder="Data ważności do" name="form_date_end" required="required" value="<?php echo ((isset($this->tpl_values['form_date_end']) && $this->tpl_values['form_date_end'] != '') ? $this->tpl_values['form_date_end'] : ''); ?>" />
                                </td>
                                <td class="table_akcje">
                                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                                    <button class="btn btn-primary mar_button" type="submit" name="form_action" value="drive_license_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
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
                            <th class="table_category">Kategoria</th>
                            <th class="table_date_start">Data ważności od</th>
                            <th class="table_date_end">Data ważności do</th>
                            <th class="table_akcje">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if($this->tpl_values['items']):
                            foreach ($this->tpl_values['items'] as $key => $item):
                        ?>
                        <tr>
                            <td class="table_id"><?php echo $item['id_driver_license']; ?></td>
                            <td class="table_category"><?php echo $item['drive_category_name']; ?></td>
                            <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                            <td class="table_date_end"><?php echo $item['date_end']; ?></td>
                            <td class="table_akcje">
                                
                                <form method="post">
                                    <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                    <input type="hidden" name="id_driver_license" value="<?php echo $item['id_driver_license']; ?>" />
                                    
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['drive_category_name']; ?>: Czy na pewno chcesz usunąć prawo jazdy?" type="submit" name="form_action" value="drive_license_delete">Usuń</button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                        <?php
                            endforeach;
                            else:
                        ?>
                        <tr>
                            <td class="table_null" colspan="5">Żołnierz nie posiada prawa jazdy</td>
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
