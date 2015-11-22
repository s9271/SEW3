<div id="soldier_mission_seconded" class="container soldier_option_page">
    <div class="jumbotron">
        <h1 class="controller_title text-left"><?php echo $this->tpl_values['name']; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <form method="post" action="" id="add_form" class="clearfix">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="delete_desc">Powód oddelegowania żołnierza (opcjonalnie)</th>
                        <th class="delete_akcje">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="delete_desc">
                            <textarea name="description_delete" class="form-control input-sm" placeholder="Powód oddelegowania żołnierza (opcjonalnie)"><?php echo ((isset($this->tpl_values['description_delete']) && $this->tpl_values['description_delete'] != '') ? $this->tpl_values['description_delete'] : ''); ?></textarea>
                        </td>
                        <td class="delete_akcje">
                            <input type="hidden" name="id_soldier2missions" value="<?php echo $this->tpl_values['id_soldier2missions']; ?>" />
                            
                            <?php if($this->tpl_values['s2m_deleted'] == '1'): ?>
        
                            <span class="btn btn-default disabled">Oddeleguj</span>
                            
                            <?php else: ?>
                            
                            <button class="btn btn-warning" type="submit" name="form_action" value="soldier_mission_seconded">Oddeleguj</button>
                            
                            <?php endif; ?>
                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        
        <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy misji żołnierza</a>
        
    </div>
</div>
