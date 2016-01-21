<div id="equipment_type_view" class="new_admin_tpl mar_custom">
    
    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-3 text-right row_title">Nazwa typu wyposażenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Kategoria główna:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['category_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Status wyposażenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['active_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_50">
        <div class="col-sm-12 text-right">
            
            <a href="/typy-wyposazenia/<?php echo $this->tpl_values['form_parent'] !== NULL ? $this->tpl_values['form_parent'] : ''; ?>" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy typów wyposażenia</a>
        
            <a href="/typy-wyposazenia/edytuj/<?php echo $this->tpl_values['id_equipment_type']; ?>" class="btn btn-warning mar_button">
                <i class="fa fa-pencil-square-o"></i>
                Edytuj
            </a>
        </div>
    </div>
        
</div>
