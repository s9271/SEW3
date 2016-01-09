<div id="soldier_languages_list" class="container soldier_children_list">
    <div class="jumbotron">
        <h1 class="controller_child_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <div class="row">
            <div class="col-sm-8">
                <form method="post" class="clearfix">
                    <table class="table table_add table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="table_name">Nazwa</th>
                                <th class="table_level">Stopień zaawansowania</th>
                                <th class="table_akcje">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table_name">
                                    <input id="form_name" class="form-control input-sm" type="text" placeholder="Nazwa" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
                                </td>
                                <td class="table_level">
                                    <select class="input-sm jsselect" id="form_language_level" name="form_language_level" required="required">
                                        <option value="">Wybierz</option>
                                        <?php
                                            if (isset($this->tpl_values['language_levels']) && $this->tpl_values['language_levels']){
                                                foreach ($this->tpl_values['language_levels'] as $type){
                                                    echo '<optgroup label="'.htmlspecialchars($type['name']).'">';
                                                
                                                    if (isset($type['childs']) && is_array($type['childs']) && count($type['childs']) > 0)
                                                    {
                                                        foreach ($type['childs'] as $key_type => $child)
                                                        {
                                                            $selected = ((isset($this->tpl_values['form_language_level']) && $this->tpl_values['form_language_level'] == $key_type) ? ' selected="selected"' : '');
                                                            echo '<option value="'.$key_type.'"'.$selected.'>'.htmlspecialchars($child).'</option>';
                                                        }
                                                    }
                                                    
                                                    echo '</optgroup>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td class="table_akcje">
                                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                                    <button class="btn btn-primary mar_button" type="submit" name="form_action" value="language_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_id">ID</th>
                            <th class="table_name">Nazwa</th>
                            <th class="table_level">Stopień zaawansowania</th>
                            <th class="table_akcje">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if($this->tpl_values['items']):
                            foreach ($this->tpl_values['items'] as $key => $item):
                        ?>
                        <tr>
                            <td class="table_id"><?php echo $item['id_language']; ?></td>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_level"><?php echo $item['language_level_name']; ?></td>
                            <td class="table_akcje">
                                
                                <form method="post">
                                    <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                    <input type="hidden" name="id_language" value="<?php echo $item['id_language']; ?>" />
                                    
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć język?" type="submit" name="form_action" value="language_delete">Usuń</button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                        <?php
                            endforeach;
                            else:
                        ?>
                        <tr>
                            <td class="table_null" colspan="5">Żołnierz nie posiada języków</td>
                        </tr>
                        <?php
                            endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php $this->getPages(); ?>
        
        <a href="/zolnierze" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
        <a href="/zolnierze/podglad/<?php echo $this->tpl_values['id_soldier']; ?>" class="btn btn-primary mar_button">Podgląd żołnierza</a>
    </div>
</div>
