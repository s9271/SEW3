<div id="user_list" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Lista Użytkowników</h1>
        
        <?php $this->getAlerts(false); ?>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_name">Imię</th>
                    <th class="table_surname">Nazwisko</th>
                    <th class="table_mail">Adres e-mail</th>
                    <th class="table_permission">Profil</th>
                    <th class="table_status">Status</th>
                    <th class="table_guard">Guard</th>
                    <th class="table_akcje">Akcje</th>
                </tr>
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
                    <td class="table_mail"><?php echo $item['mail']; ?></td>
                    <td class="table_permission"><?php echo $item['name_permission']; ?></td>
                    <td class="table_status"><?php echo $item['name_status']; ?></td>
                    <td class="table_guard"><?php echo $item['name_guard']; ?></td>
                    <td class="table_akcje">
                        <form method="post" action="">
                            <input type="hidden" name="id_user" value="<?php echo $item['id_user']; ?>" />
                            
                            <div class="btn-group btn-group-sm">
                                <a href="/uzytkownicy/edytuj/<?php echo $item['id_user']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name'].' '.$item['surname']; ?>: Czy na pewno chcesz usunąć uzytkownika?" type="submit" name="form_action" value="user_delete">Usuń</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                
                <tr>
                    <td class="table_null" colspan="8">Brak użytkowników do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
                
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
        
        <a href="/uzytkownicy/dodaj" class="btn btn-info mar_button">Dodaj użytkownika</a>
    </div>
</div>
