<div id="user_list" class="new_admin_tpl m_table_list">
        
    <?php $this->getAlerts(false); ?>
    
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="table_id">ID</th>
                        <th class="table_name">Imię</th>
                        <th class="table_surname">Nazwisko</th>
                        <th class="table_login">Login</th>
                        <th class="table_mail">Adres e-mail</th>
                        <th class="table_permission">Profil</th>
                        <th class="table_status">Status</th>
                        <th class="table_guard">Guard</th>
                        <th class="table_akcje">Akcje</th>
                    </tr>
                    
                    <?php echo $this->getSearchForm(); ?>
                    
                </thead>
                <tbody>
                
                    <?php 
                        if(isset($this->tpl_values['users']) && $this->tpl_values['users']):
                        foreach ($this->tpl_values['users'] as $key => $item):
                    ?>
                    
                    <tr>
                        <td class="table_id"><?php echo $item['id_user']; ?></td>
                        <td class="table_name"><?php echo $item['name']; ?></td>
                        <td class="table_surname"><?php echo $item['surname']; ?></td>
                        <td class="table_login"><?php echo $item['login']; ?></td>
                        <td class="table_mail"><?php echo $item['mail']; ?></td>
                        <td class="table_permission"><?php echo $item['name_permission']; ?></td>
                        <td class="table_status"><?php echo $item['name_status']; ?></td>
                        <td class="table_guard"><?php echo $item['name_guard']; ?></td>
                        <td class="table_akcje">
                            
                            <div class="btn-group-action view_dropdown">
                                <div class="btn-group">
                                    <a href="/uzytkownicy/podglad/<?php echo $item['id_user']; ?>" class="edit btn btn-primary" title="Podgląd">
                                        <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                    </a>
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="/uzytkownicy/edytuj/<?php echo $item['id_user']; ?>" title="Edytuj">
                                                <span class="glyphicon glyphicon-pencil"></span>Edytuj
                                            </a>
                                        </li>
                                        <li class="divider"> </li>
                                        <li>
                                            <form method="post">
                                                <input type="hidden" name="id_user" value="<?php echo $item['id_user']; ?>" />
                                                <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo $item['name'].' '.$item['surname']; ?>: Czy na pewno chcesz usunąć użytkownika?" type="submit" name="form_action" value="user_delete">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                    Usuń
                                                </button>
                                            </form>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                            
                        </td>
                    </tr>
                    <?php
                        endforeach;
                        else:
                    ?>
                    
                    <tr>
                        <td class="table_null" colspan="9">Brak użytkowników do wyświetlenia</td>
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
            <a href="/uzytkownicy/dodaj" class="btn btn-info pull-right mar_button">
                <i class="fa fa-plus"></i>
                Dodaj użytkownika
            </a>
        </div>
    </div>
</div>
