<div id="soldier_missions_view" class="container mar_custom">
    <div class="jumbotron">
        <h1 class="controller_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <h4 class="page-header no_margin_top">Dane powiązania do misji</h4>
        
        <div class="row">
            <div class="col-sm-3 text-right row_title">Status:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['status_name']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Dodatkowe informacje:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['description']; ?></div>
            </div>
        </div>
        
        <?php if($this->tpl_values['detached'] == '1'){ ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Data oddelegowania:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['date_update']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Użytkownik oddelegowania:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['user_detach'] ? '#'.$this->tpl_values['user_detach']['id_user'].' '.$this->tpl_values['user_detach']['name'].' '.$this->tpl_values['user_detach']['surname'] : ''; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Powód oddelegowania:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['description_detach']; ?></div>
            </div>
        </div>
        
        <?php } ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-12">
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy misji żołnierza</a>
                
                <?php if($this->tpl_values['detached'] == '0' || $this->tpl_values['id_login_permission'] == '1'){ ?>
        
                <form method="post" class="pull-right">
                    <?php if($this->tpl_values['detached'] == '0'){ ?>
                    
                        <a class="btn btn-warning mar_button" href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje/oddeleguj/<?php echo $this->tpl_values['id_soldier2missions']; ?>" title="Oddeleguj">
                            <span class="glyphicon glyphicon-pencil"></span>Oddeleguj
                        </a>
                    
                    <?php } ?>
                    
                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                    <input type="hidden" name="id_soldier2missions" value="<?php echo $this->tpl_values['id_soldier2missions']; ?>" />
                    <input type="hidden" name="id_mission" value="<?php echo $this->tpl_values['id_mission']; ?>" />
                    <button class="btn btn-danger jsconfirm" data-confirm="Czy na pewno chcesz usunąć tą misję?" type="submit" name="form_action" value="mission_delete"><span class="glyphicon glyphicon-trash"></span> Usuń powiązanie</button>
                </form>
                
                <?php } ?>
                
            </div>
        </div>
        
        <h4 class="page-header">Dane Misji</h4>
        
        <?php if($this->tpl_values['mission']->load_class == '1'){ ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Kryptonim misji:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['mission']->name; ?></div>
            </div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Rodzaj misji:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['mission']->mission_type_name; ?></div>
            </div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Lokalizacja misji:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['mission']->location; ?></div>
            </div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Opis:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['mission']->description; ?></div>
            </div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Data rozpoczęcia:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['mission']->date_start_pl; ?></div>
            </div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Data zakończenia:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['mission']->date_end_name_pl; ?></div>
            </div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Aktywna:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['mission']->active_name; ?></div>
            </div>
            <div class="col-sm-offset-3 col-sm-9 clear sew_hint">Gdy misja jest aktywna, można do niej dodać żołnierzy.</div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Status:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['mission']->status; ?></div>
            </div>
        </div>
        
        <?php }else{ ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-12 text-center sew_red">Misja nie istnieje</div>
        </div>
        
        <?php } ?>
        
    </div>
</div>
