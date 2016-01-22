<div id="soldier_equipments_view" class="new_admin_tpl mar_custom">

    <?php $this->getAlerts(false); ?>
    
    <h4 class="page-header no_margin_top">Dane powiązania do wyposażenia</h4>
    
    <div class="row">
        <div class="col-sm-3 text-right row_title">Status:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['status_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Ilość:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['equipment_count']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Data przyznania:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['date_equipment_add']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Dodatkowe informacje:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['description']; ?></div>
        </div>
    </div>
    
    <?php if($this->tpl_values['returned'] == '1'){ ?>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Data zwrotu:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['date_return']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Powód zwrotu:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['description_return']; ?></div>
        </div>
    </div>
    
    <?php } ?>
    
    <div class="row margin_top_15">
        <div class="col-sm-12">
            <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/wyposazenie/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy wyposażenia żołnierza</a>
            
            <?php if($this->tpl_values['returned'] == '0' || $this->tpl_values['id_login_permission'] == '1'){ ?>
    
            <form method="post" class="pull-right">
                <?php if($this->tpl_values['returned'] == '0'){ ?>
                
                    <a class="btn btn-warning mar_button" href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/wyposazenie/edytuj/<?php echo $this->tpl_values['id_soldier2equipments']; ?>" title="Edytuj">
                        <span class="glyphicon glyphicon-pencil"></span>Edytuj
                    </a>
                
                    <a class="btn btn-warning mar_button" href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/wyposazenie/zwroc/<?php echo $this->tpl_values['id_soldier2equipments']; ?>" title="Zwróć">
                        <span class="glyphicon glyphicon-pencil"></span>Zwróć
                    </a>
                
                <?php } ?>
                
                <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                <input type="hidden" name="id_soldier2equipments" value="<?php echo $this->tpl_values['id_soldier2equipments']; ?>" />
                <input type="hidden" name="id_equipment" value="<?php echo $this->tpl_values['id_equipment']; ?>" />
                <button class="btn btn-danger jsconfirm" data-confirm="Czy na pewno chcesz usunąć to wyposażenie?" type="submit" name="form_action" value="equipment_delete"><span class="glyphicon glyphicon-trash"></span> Usuń powiązanie</button>
            </form>
            
            <?php } ?>
            
        </div>
    </div>
    
    <h4 class="page-header">Dane Wyposażenia</h4>
    
    <?php if($this->tpl_values['equipment']->load_class == '1'){ ?>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Nazwa:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['equipment']->name; ?></div>
        </div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Typ wyposażenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['equipment']->equipment_type_name; ?></div>
        </div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Aktywna:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['equipment']->active_name; ?></div>
        </div>
        <div class="col-sm-offset-3 col-sm-9 clear sew_hint">Gdy wyposażenie jest aktywne, można do niego dodać żołnierzy.</div>
    </div>
    
    <?php }else{ ?>
    
    <div class="row margin_top_15">
        <div class="col-sm-12 text-center sew_red">Wyposażenie nie istnieje</div>
    </div>
    
    <?php } ?>
</div>
