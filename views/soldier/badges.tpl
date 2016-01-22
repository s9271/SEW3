<div id="soldier_badges_list" class="new_admin_tpl m_table_list soldier_children_list">

    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-bordered table-hover table_list">
                <thead>
                    <tr>
                        <th class="table_id">ID</th>
                        <th class="table_name">Nazwa odznaczenia</th>
                        <th class="table_rank">Ilość stopni odznaczenia</th>
                        <th class="table_type">Typ odznaczenia</th>
                        <th class="table_date_grant">Data przyznania</th>
                        <th class="table_status">Status</th>
                        <th class="table_akcje">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if($this->tpl_values['items']):
                        foreach ($this->tpl_values['items'] as $key => $item):
                    ?>
                    <tr>
                        <td class="table_id"><?php echo $item['id_soldier2badges']; ?></td>
                        <td class="table_name"><?php echo $item['badge_name']; ?></td>
                        <td class="table_rank"><?php echo $item['badge_rank_name']; ?></td>
                        <td class="table_type"><?php echo $item['badge_type']; ?></td>
                        <td class="table_date_grant"><?php echo $item['date_grant']; ?></td>
                        <td class="table_status"><?php echo $item['status_name']; ?></td>
                        <td class="table_akcje<?php echo $item['received'] == '0' || $this->tpl_values['id_login_permission'] == '1' ? '' : ' received'; ?>">
                            
                            <form method="post">
                                <div class="btn-group">
                                    <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/odznaczenia/podglad/<?php echo $item['id_soldier2badges']; ?>" class="edit btn btn-primary" title="Podgląd">
                                        <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                    </a>
                                    
                                    <?php if($item['received'] == '0' || $this->tpl_values['id_login_permission'] == '1'){ ?>
                                    
                                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            
                                            <?php if($item['received'] == '0'){ ?>
                                            
                                            <li>
                                                <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/odznaczenia/edytuj/<?php echo $item['id_soldier2badges']; ?>" title="Edytuj">
                                                    <span class="glyphicon glyphicon-pencil"></span>Edytuj
                                                </a>
                                            </li>
                                            <li class="divider"> </li>
                                            <li>
                                                <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/odznaczenia/odbierz/<?php echo $item['id_soldier2badges']; ?>" title="Odeślij">
                                                    <span class="glyphicon glyphicon-pencil"></span>Odbierz odznaczenie
                                                </a>
                                            </li>
                                            
                                            <?php } ?>
                                            
                                            <li>
                                                <form method="post">
                                                    <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                                    <input type="hidden" name="id_soldier2badges" value="<?php echo $item['id_soldier2badges']; ?>" />
                                                    <input type="hidden" name="id_badge" value="<?php echo $item['id_badge']; ?>" />
                                                    <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo htmlspecialchars($item['badge_name']); ?>: Czy na pewno chcesz usunąć odznaczenie?" type="submit" name="form_action" value="badge_delete"><span class="glyphicon glyphicon-trash"></span> Usuń</button>
                                                </form>
                                            </li>
                                        </ul>
                                        
                                    <?php } ?>
                                    
                                </div>

                            </form>
                            
                        </td>
                    </tr>
                    <?php
                        endforeach;
                        else:
                    ?>
                    <tr>
                        <td class="table_null" colspan="7">Żołnierz nie posiada odznaczeń</td>
                    </tr>
                    <?php
                        endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php $this->getPages(); ?>
    
    <div class="row">
        <div class="col-sm-12">
            <a href="/zolnierze" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
            <a href="/zolnierze/podglad/<?php echo $this->tpl_values['id_soldier']; ?>" class="btn btn-primary mar_button">Podgląd żołnierza</a>
            <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/odznaczenia/dodaj" class="btn btn-primary mar_button pull-right"><span class="glyphicon glyphicon-plus"></span> Dodaj odznaczenie</a>
        </div>
    </div>
</div>
