<div id="military_type_view" class="new_admin_tpl mar_custom">
    
    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-3 text-right row_title">Nazwa centrum szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Lokalizacja:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_location']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Status centrum szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['active_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_50">
        <div class="col-sm-12 text-right">
            <a href="/centra-szkolen" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy centrów szkoleń</a>
            
            <a href="/centra-szkolen/edytuj/<?php echo $this->tpl_values['id_training_centre']; ?>" class="btn btn-warning mar_button">
                <i class="fa fa-pencil-square-o"></i>
                Edytuj
            </a>
        </div>
    </div>
        
</div>
