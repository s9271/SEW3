<div id="soldier_missions" class="container soldier_option_page">
    <div class="jumbotron">
        <h1 class="controller_title text-left">Lista misji żołnierza: <?php echo $this->tpl_values['name']; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <form method="post" action="" id="add_form" class="clearfix">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="add_select">Lista misji</th>
                        <th class="add_akcje">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="add_select">
                            <select class="form-control input-sm jsselectajax" data-ajax-class="<?php echo $this->tpl_values['ajax_class']; ?>" data-ajax-function="<?php echo $this->tpl_values['ajax_function']; ?>" data-id-soldier="<?php echo $this->tpl_values['id_soldier']; ?>" id="add_form_list_id" name="add_form_list_id" required="required">
                            </select>
                            
                            <?php if(isset($this->tpl_values['add_hint']) && $this->tpl_values['add_hint'] != ''): ?>
                            
                            <p class="hint"><?php echo $this->tpl_values['name']; ?></p>
                            <?php endif; ?>
                            
                        </td>
                        <td class="add_akcje">
                            <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                            <button class="btn btn-primary" type="submit" name="form_action" value="add_mission">Przydziel do misji</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="add_description" colspan="2">
                            <textarea name="add_form_description" class="form-control input-sm" placeholder="Dodatkowe informacje (opcjonalnie)"><?php echo ((isset($this->tpl_values['add_form_description']) && $this->tpl_values['add_form_description'] != '') ? $this->tpl_values['add_form_description'] : ''); ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        
        <a href="#" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
        
        <table class="table table-striped table-bordered table-hover" id="table_list">
            <thead>
                <tr>
                    <th class="table_name">Nazwa misji</th>
                    <th class="table_date_start">Data rozpoczęcia</th>
                    <th class="table_date_end">Data zakończenia</th>
                    <th class="table_status">Status</th>
                    <th class="table_date_add">Data przydziału</th>
                    <th class="table_account">Użytkownik</th>
                    <th class="table_akcje">Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if($this->tpl_values && isset($this->tpl_values['table_list'])):
                    foreach ($this->tpl_values['table_list'] as $key => $item):
                ?>
                
                <tr>
                    <td class="table_name"><?php echo $item['name']; ?></td>
                    <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                    <td class="table_date_end"><?php echo $item['date_end_name']; ?></td>
                    <td class="table_status"><?php echo $item['status']; ?></td>
                    <td class="table_date_add"><?php echo $item['date_add']; ?></td>
                    <td class="table_account"><?php echo $item['account']; ?></td>
                    <td class="table_akcje">
                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/misje/<?php echo $item['id_mission']; ?>" class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć misję?" title="Usuń">Usuń</a>
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                
                <tr>
                    <td class="table_null" colspan="7">Brak misji do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
                
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
    </div>
</div>
