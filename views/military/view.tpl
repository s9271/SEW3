<div id="users_view" class="new_admin_tpl mar_custom">
    
    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-3 text-right row_title">Nazwa jednostki:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Numer jednostki:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_number']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Rodzaj jednostki:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['military_group_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Lokalizacja:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_location']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Status jednostki:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['active_name']; ?></div>
        </div>
    </div>
    
    
    
    <div class="row margin_top_50">
        <div class="col-sm-12 text-right">
            <a href="/jednostki/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy jednostek wojskowej</a>
            
            <a href="/jednostki/edytuj/<?php echo $this->tpl_values['id_military']; ?>" class="btn btn-warning mar_button">
                <i class="fa fa-pencil-square-o"></i>
                Edytuj
            </a>
        </div>
    </div>
        
</div>
