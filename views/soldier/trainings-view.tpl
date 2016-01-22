<div id="soldier_trainings_view" class="new_admin_tpl mar_custom">

    <?php $this->getAlerts(false); ?>
    
    <h4 class="page-header no_margin_top">Dane powiązania do szkolenia</h4>
    
    <div class="row">
        <div class="col-sm-3 text-right row_title">Status:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['status_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Data przypisania do szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['date_training_add']; ?></div>
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
        <div class="col-sm-3 text-right row_title">Data odesłania:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['date_training_return']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Użytkownik odesłania:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['user_return'] ? '#'.$this->tpl_values['user_return']['id_user'].' '.$this->tpl_values['user_return']['name'].' '.$this->tpl_values['user_return']['surname'] : ''; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Powód odesłania:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['description_return']; ?></div>
        </div>
    </div>
    
    <?php } ?>
    
    <div class="row margin_top_15">
        <div class="col-sm-12">
            <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/szkolenia/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy szkoleń żołnierza</a>
            
            <?php if($this->tpl_values['returned'] == '0' || $this->tpl_values['id_login_permission'] == '1'){ ?>
    
            <form method="post" class="pull-right">
                <?php if($this->tpl_values['returned'] == '0'){ ?>
                
                    <a class="btn btn-warning mar_button" href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/szkolenia/odeslij/<?php echo $this->tpl_values['id_soldier2trainings']; ?>" title="Odeślij">
                        <span class="glyphicon glyphicon-pencil"></span>Odeślij
                    </a>
                
                <?php } ?>
                
                <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                <input type="hidden" name="id_soldier2trainings" value="<?php echo $this->tpl_values['id_soldier2trainings']; ?>" />
                <input type="hidden" name="id_training" value="<?php echo $this->tpl_values['id_training']; ?>" />
                <button class="btn btn-danger jsconfirm" data-confirm="Czy na pewno chcesz usunąć to szkolenie?" type="submit" name="form_action" value="training_delete"><span class="glyphicon glyphicon-trash"></span> Usuń powiązanie</button>
            </form>
            
            <?php } ?>
            
        </div>
    </div>
    
    <h4 class="page-header">Dane szkolenia</h4>
    
    <?php if($this->tpl_values['training']->load_class == '1'){ ?>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Nazwa szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['training']->name; ?></div>
        </div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Kod szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['training']->code; ?></div>
        </div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Ośrodek szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['training']->training_center_name; ?></div>
        </div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Opis:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['training']->description; ?></div>
        </div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Data rozpoczęcia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['training']->date_start_name; ?></div>
        </div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Data zakończenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['training']->date_end_name; ?></div>
        </div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Aktywna:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['training']->active_name; ?></div>
        </div>
        <div class="col-sm-offset-3 col-sm-9 clear sew_hint">Gdy szkolenie jest aktywne, można do niego dodać żołnierzy.</div>
    </div>

    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Status:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['training']->status; ?></div>
        </div>
    </div>
    
    <?php }else{ ?>
    
    <div class="row margin_top_15">
        <div class="col-sm-12 text-center sew_red">Szkolenie nie istnieje</div>
    </div>
    
    <?php } ?>
    
</div>
