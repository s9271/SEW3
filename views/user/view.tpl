<div id="users_view" class="new_admin_tpl mar_custom">
    
    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-3 text-right row_title">Nazwa użytkownika:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_login']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Imię:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Nazwisko:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_surname']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Adres e-mail:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_mail']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Telefon:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_phone']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Profil uprawnień:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_permission']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Jednostka wojskowa:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_military']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Status użytkownika:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_active']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Guard:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_guard']; ?></div>
        </div>
    </div>
    
    
    
    <div class="row margin_top_50">
        <div class="col-sm-12 text-right">
            <a href="/uzytkownicy/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy użytkowników</a>
            
            <a href="/uzytkownicy/edytuj/<?php echo $this->tpl_values['id_user']; ?>" class="btn btn-warning mar_button">
                <i class="fa fa-pencil-square-o"></i>
                Edytuj
            </a>
            
            <a href="/uzytkownicy/edytuj/<?php echo $this->tpl_values['id_user']; ?>/haslo" class="btn btn-warning mar_button">
                <i class="fa fa-pencil-square-o"></i>
                Ustaw nowe hasło
            </a>
        </div>
    </div>
        
</div>
