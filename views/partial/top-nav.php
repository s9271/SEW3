<nav class="navbar navbar-inverse">
  <div class="container-fluid">

    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="#"><span class="glyphicon glyphicon-home"></span>&emsp;S.E.W&emsp;|</a></li>
        <li><a href="soldier.php"><span class="glyphicon glyphicon-plus"></span>&emsp;Żołnierz&emsp;|</a></li>
        <li><a href="user.php" class="btn disabled"><span class="glyphicon glyphicon-plus"></span>&emsp;Użytkownik&emsp;|</a></li>
        <li><a href="military.php"><span class="glyphicon glyphicon-star-empty"></span>&emsp;Jednostka&emsp;|</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-search"></span>&emsp;Znajdź&emsp;|</a></li>
        <li><a href="#" class="btn" data-toggle="modal" data-target="#largeModal"><span class="glyphicon glyphicon-book"></span>&emsp;Pomoc&emsp;|</a></li>
          <div class="modal fade" id="largeModal" role="dialog">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Pomoc</h4>
                </div>
                <div class="modal-body">
                  instrukcja ....
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        <!--<li><a href="#"><span class="glyphicon glyphicon-user"></span>&emsp;<?php echo $_SESSION['user_name'] ?></a></li>-->
        <li><a href="index.php?logout"><span class="glyphicon glyphicon-log-out"></span>&emsp;Wyloguj</a></li>
      </ul>
    </div>
  </div>
</nav>
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
      <div id="sidebar-wrapper">
          <ul class="sidebar-nav">
              <li>
                  <span>---------------------------------------------</span>
              </li>
              <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="./user.php"><span class="glyphicon glyphicon-edit"></span>&emsp;&emsp;Użytkownicy<i class="caret"></i></a>
                  <ul class="dropdown-menu ">
                    <li><a href="user.php" class="btn disabled"><span class="glyphicon glyphicon-plus"></span>&emsp;&emsp;Dodaj Użytkownika</a></li>
                    <li><a href="user.php?action=list" class="btn disabled"><span class="glyphicon glyphicon-list-alt"></span>&emsp;&emsp;Pokaż Użytkowników</a></li>
                  </ul>
              </li>
              <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="military.php"><span class="glyphicon glyphicon-star-empty"></span>&emsp;&emsp;Jednostki Wojskowe<span class="caret"></span></a>
                  <ul class="dropdown-menu sidebar-nav">
                    <li><a href="military.php"><span class="glyphicon glyphicon-plus"></span>&emsp;&emsp;Dodaj Jednostkę</a></li>
                    <li><a href="military.php?action=list"><span class="glyphicon glyphicon-list-alt"></span>&emsp;&emsp;Pokaż Jednostki</a></li>
                  </ul>
              </li>
              <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="soldier.php"><span class="glyphicon glyphicon-user"></span>&emsp;&emsp;Żołnierze<span class="caret"></span></a>
                  <ul class="dropdown-menu sidebar-nav">
                    <li><a href="soldier.php"><span class="glyphicon glyphicon-plus"></span>&emsp;&emsp;Dodaj Żołnierza</a></li>
                    <li><a href="soldier.php?action=list"><span class="glyphicon glyphicon-list-alt"></span>&emsp;&emsp;Pokaż Żołnierzy</a></li>
                  </ul>
              </li>
              <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="equipment.php"><span class="glyphicon glyphicon-screenshot"></span>&emsp;&emsp;Uzbrojenie<span class="caret"></span></a>
                  <ul class="dropdown-menu sidebar-nav">
                    <li><a href="equipment.php"><span class="glyphicon glyphicon-plus"></span>&emsp;&emsp;Dodaj Uzbrojenie</a></li>
                    <li><a href="equipment.php?action=list"><span class="glyphicon glyphicon-list-alt"></span>&emsp;&emsp;Pokaż Uzbrojenie</a></li>
                  </ul>

              </li>
              <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="mission.php"><span class="glyphicon glyphicon-move"></span>&emsp;&emsp;Misje<span class="caret"></span></a>
                  <ul class="dropdown-menu sidebar-nav">
                    <li><a href="mission.php"><span class="glyphicon glyphicon-plus"></span>&emsp;&emsp;Dodaj Misje</a></li>
                    <li><a href="mission.php?action=list"><span class="glyphicon glyphicon-list-alt"></span>&emsp;&emsp;Pokaż Misje</a></li>
                  </ul>
              </li>
              
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="/misje">
                        <span class="glyphicon glyphicon-move"></span>&emsp;&emsp;Misje 2<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu sidebar-nav">
                        <li><a href="/misje/dodaj"><span class="glyphicon glyphicon-plus"></span>&emsp;&emsp;Dodaj Misje</a></li>
                        <li><a href="/misje"><span class="glyphicon glyphicon-list-alt"></span>&emsp;&emsp;Pokaż Misje</a></li>
                    </ul>
                </li>
              
              <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="training.php"><span class="glyphicon glyphicon-check"></span>&emsp;&emsp;Szkolenia<span class="caret"></span></a>
                  <ul class="dropdown-menu sidebar-nav">
                    <li><a href="training.php"><span class="glyphicon glyphicon-plus"></span>&emsp;&emsp;Dodaj Szkolenie</a></li>
                    <li><a href="training.php?action=list"><span class="glyphicon glyphicon-list-alt"></span>&emsp;&emsp;Pokaż Szkolenia</a></li>
                  </ul>
              </li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="training.php"><span class="glyphicon glyphicon-tags"></span>&emsp;&emsp;Odznaczenia<span class="caret"></span></a>
                <ul class="dropdown-menu sidebar-nav">
                  <li><a href="orders.php"><span class="glyphicon glyphicon-plus"></span>&emsp;&emsp;Dodaj Odznaczenie</a></li>
                  <li><a href="orders.php?action=list"><span class="glyphicon glyphicon-list-alt"></span>&emsp;&emsp;Pokaż Odznaczenia</a></li>
                </ul>
              </li>
              <li>
                  <span>---------------------------------------------</span>
              </li>
              <li>
                  <a href="#" data-toggle="modal" data-target="#largeModal"><span class="glyphicon glyphicon-book"></span>&emsp;&emsp;Pomoc</a>
              </li>
          </ul>
      </div>
    </ul>
</div>
