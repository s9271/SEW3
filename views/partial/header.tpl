<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>SEW - System Ewidencji Wojskowej<?php echo $this->tpl_title ? " - {$this->tpl_title}" : ''; ?></title>
            
            <?php if($this->scripts){ foreach($this->scripts as $script){ ?>
<?php echo $script."\n"; ?>
            <?php }} ?>
<!-- Custom -->
            <link href="/asset/css/custom.css" rel="stylesheet" />
            <link href="/css/simple-sidebar.css" rel="stylesheet" />
            <link href="/asset/css/admin.css" rel="stylesheet" />
            <link href="/asset/css/mariusz.css" rel="stylesheet" />
        </head>
        <body>

            <header id="header">
                <nav id="header_infos" role="navigation" class="clearfix">
                    <div id="menu_logo">
                        <span class="glyphicon glyphicon-home"></span>
                        SEW - System Ewidencji Wojskowej
                    </div>
                    
                    <div id="header_profile" class="dropdown">
                        <a class="profile_name dropdown-toggle clearfix" data-toggle="dropdown" href="/moje-konto">
                            <span class="profile_avatar_small">
                                <img src="/asset/images/profile.jpg" alt="">
                            </span>
                            <span class="profile_name_surname"><?php echo $user_name_surname; ?></span>
                            <i class="caret"></i>
                        </a>
                        
                        <ul id="profile_menu" class="dropdown-menu">
                            <li>
                                <span class="profile_avatar">
                                    <img src="/asset/images/profile.jpg" alt="">
                                </span>
                            </li>
                            <li class="text-center"><?php echo $user_name_surname; ?></li>
                            <li class="divider"></li>
                            <li>
                                <a href="/moje-konto">
                                    <span class="glyphicon glyphicon-wrench"></span>
                                    Moje ustawienia
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a id="profile_logout" href="/logout">
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    Wyloguj się
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            
            <!-- start page -->
            <div id="page">
            
                <!-- start left_column -->
                <div id="left_column">
                    <nav id="left_menu" role="navigation">
                        <ul class="menu">
                            <li class="has_submenu">
                                <a class="title" href="/zolnierze">
                                    <i class="fa fa-male"></i>
                                    <span>Żołnierze</span>
                                </a>
                                
                                <ul class="submenu">
                                    <li>
                                        <a href="/zolnierze">Pokaż Żołnierzy</a>
                                    </li>
                                    <li>
                                        <a href="/zolnierze/dodaj">Dodaj Żołnierza</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <?php if($login->auth_user['id_permission'] == '1'){ ?>
                            
                            <li class="has_submenu">
                                <a class="title" href="/uzytkownicy">
                                    <i class="fa fa-users"></i>
                                    <span>Użytkownicy</span>
                                </a>
                                
                                <ul class="submenu">
                                    <li>
                                        <a href="/uzytkownicy">Pokaż Użytkowników</a>
                                    </li>
                                    <li>
                                        <a href="/uzytkownicy/dodaj">Dodaj Użytkownika</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="has_submenu">
                                <a class="title" href="/jednostki">
                                    <i class="fa fa-map-marker"></i>
                                    <span>Jednostki Wojskowe</span>
                                </a>
                                
                                <ul class="submenu">
                                    <li>
                                        <a href="/jednostki">Pokaż Jednostki Wojskowe</a>
                                    </li>
                                    <li>
                                        <a href="/jednostki/dodaj">Dodaj Jednostkę Wojskową</a>
                                    </li>
                                    <li>
                                        <a href="/rodzaje-jednostek">Rodzaje Jednostek Wojskowych</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="has_submenu">
                                <a class="title" href="/wyposazenie">
                                    <i class="fa fa-shield"></i>
                                    <span>Wyposażenie</span>
                                </a>
                                
                                <ul class="submenu">
                                    <li>
                                        <a href="/wyposazenie">Pokaż Wyposażenia</a>
                                    </li>
                                    <li>
                                        <a href="/wyposazenie/dodaj">Dodaj Wyposażenie</a>
                                    </li>
                                    <li>
                                        <a href="/typy-wyposazenia">Typy wyposażenia</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="has_submenu">
                                <a class="title" href="/misje">
                                    <i class="fa fa-question"></i>
                                    <span>Misje</span>
                                </a>
                                
                                <ul class="submenu">
                                    <li>
                                        <a href="/misje">Pokaż Misje</a>
                                    </li>
                                    <li>
                                        <a href="/misje/dodaj">Dodaj Misje</a>
                                    </li>
                                    <li>
                                        <a href="/rodzaje-misji">Rodzaje Misji</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="has_submenu">
                                <a class="title" href="/szkolenia">
                                    <i class="fa fa-graduation-cap"></i>
                                    <span>Szkolenia</span>
                                </a>
                                
                                <ul class="submenu">
                                    <li>
                                        <a href="/szkolenia">Pokaż Szkolenia</a>
                                    </li>
                                    <li>
                                        <a href="/szkolenia/dodaj">Dodaj Szkolenie</a>
                                    </li>
                                    <li>
                                        <a href="/centra-szkolen">Centra Szkoleń</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="has_submenu">
                                <a class="title" href="/odznaczenia">
                                    <i class="fa fa-trophy"></i>
                                    <span>Odznaczenia</span>
                                </a>
                                
                                <ul class="submenu">
                                    <li>
                                        <a href="/odznaczenia">Pokaż Odznaczenia</a>
                                    </li>
                                    <li>
                                        <a href="/odznaczenia/dodaj">Dodaj Odznaczenie</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
                <!-- end left_column -->
                
                <!-- start center_column -->
                <div id="center_column">
                            
                <?php if($this->using_top_title){ ?>
                
                    <div class="top_center_column">
                        <div class="center_column_head brown">
                            <div class="page_top clearfix<?php echo $this->top_ico ? ' has_ico' : ''; ?>">
                            
                                <?php echo $this->top_ico ? '<i class="fa fa-'.$this->top_ico.'"></i>' : ''; ?>
                                
                                <?php echo $this->getBreadcrumb(); ?>
                                
                                <?php echo $this->top_title ? '<h1 class="top_title">'.$this->top_title.'</h1>' : ''; ?>
                                
                                <?php if($this->top_add_button){ ?>
                                
                                <a href="<?php echo $this->top_add_button['link']; ?>" class="add_button">
                                    <i class="fa fa-plus-square"></i>
                                    <?php echo $this->top_add_button['name']; ?>
                                </a>
                                
                                <?php } ?>
                                
                            </div>
                        </div>
                    </div>
                    
                <?php } ?>
                    
