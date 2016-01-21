<div id="training_view" class="new_admin_tpl mar_custom">

    <div class="row">
        <div class="col-sm-3 text-right row_title">Nazwa szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_name']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Kod szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_code']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Ośrodek szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_training_center']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Opis:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_description']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Data rozpoczęcia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_date_start']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Data zakończenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['form_date_end']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Aktywna:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo ($this->tpl_values['form_active'] == '1' ? 'Tak' : 'Nie'); ?></div>
            <div class="sew_hint">Gdy szkolenie jest aktywne, można do niego dodać żołnierzy.</div>
        </div>
    </div>
    
    <div class="row margin_top_15">
        <div class="col-sm-3 text-right row_title">Status szkolenia:</div>
        <div class="col-sm-9">
            <div class="row_content"><?php echo $this->tpl_values['status']; ?></div>
        </div>
    </div>
    
    <div class="row margin_top_50">
        <div class="col-sm-12 text-right">
            <a href="/szkolenia/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy szkoleń</a>
            
            <a href="/szkolenia/edytuj/<?php echo $this->tpl_values['id_training']; ?>" class="btn btn-warning mar_button">
                <i class="fa fa-pencil-square-o"></i>
                Edytuj
            </a>
        </div>
    </div>
    
    <?php /* if(isset($this->tpl_values['log']) && $this->tpl_values['log']): ?>
    
    <div id="log_table" class="sew-row clearfix">
        <div class="clearfix col-sm-offset-2 col-sm-8 no_padding">
            <h1 class="controller_title">Daty ostatnich zmian:</h1>
            
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="log_date_edit">Data zmiany</th>
                        <th class="log_id_user">Id użytkownika</th>
                        <th class="log_user">Użytkownik</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $this->tpl_values['date_update']; ?></td>
                        <td><?php echo $this->tpl_values['user']['id_user']; ?></td>
                        <td><?php echo $this->tpl_values['user']['name'].' '.$this->tpl_values['user']['surname']; ?></td>
                    </tr>
                    
                <?php foreach($this->tpl_values['log'] as $item_log): ?>
                    <tr>
                        <td><?php echo $item_log['date_update']; ?></td>
                        <td><?php echo $item_log['id_user']; ?></td>
                        <td><?php echo $item_log['name'].' '.$item_log['surname']; ?></td>
                    </tr>
                <?php endforeach; ?>
                
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; */ ?>
</div>
