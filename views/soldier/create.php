<!-- Header -->
<?php require('./views/partial/header.php'); ?>
<!-- End Header -->

<!-- Top Nav -->
<?php require('./views/partial/top-nav.php');?>
<!-- End Top Nav -->


<div class="container">
  <div class="jumbotron">
    <?php
    if (isset($this)) {
        if ($this->errors) {
            foreach ($this->errors as $error) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        }
        if ($this->messages) {
            foreach ($this->messages as $message) {
                echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
            }
        }
    }
    ?>

    <h5>Dodaj Żołnierza</h5>
    <form method="post" action="soldier.php?action=create" name="soldier-create" class="form-horizontal"><h6>

        <div class="form-group">
            <label for="login_input_username" class="col-sm-2 control-label">Imie *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="login_input_username" class="form-control login_input input-sm" type="text" placeholder="Imie Żołnierza"  name="soldierName" required/>
              </div>
            </div>
        </div>
        <div class="form-group">
            <label for="login_input_email" class="col-sm-2 control-label">Nazwisko *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="login_input_email" class="form-control login_input input-sm" type="text" placeholder="Nzwisko Żołnierza" name="soldierSurname" required/>
              </div>
            </div>
        </div>
        <div class="form-group">
            <label for="birthday" class="col-sm-2 control-label">Data *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="birthday" class="form-control login_input input-sm" type="text" placeholder="Data Urodzenia Żołnierza" name="birthday" required/>
            </div>
            <script src="js/jquery-1.9.1.min.js"></script>
            <script src="js/bootstrap-datepicker.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#birthday').datepicker({
                        format: "dd/mm/yyyy"
                    });
                });
            </script>
          </div>
        </div>
        <div class="form-group">
            <label for="sex" class="col-sm-2 control-label" name="sex" required>Płeć *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <select class="form-control input-sm" id="sex" name="sex" required>
                <option>-------------------------------</option>
                <option>K</option>
                <option>M</option>
              </select>
            </div>
            </div>
        </div>
        <div class="form-group">
            <label for="phone" class="col-sm-2 control-label" name="phone" required>Telefon *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="phone" class="form-control login_input input-sm" type="text" placeholder="Numer Kontaktowy Żołnierza" maxlength="9" name="phone" required/>
            </div>
          </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label" name="email" required>Mail *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="email" class="form-control login_input input-sm" type="text" placeholder="Poczta@Żołnierza" name="email" required/>
            </div>
          </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Adres *</label>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <input id="code" class="form-control input-sm" type="text"  placeholder="Kod Pocztowy" name="code" maxlength="5" required/>
            </div>
          </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <input id="city" class="form-control input-sm" type="text" placeholder="Miasto" name="city" required/>
            </div>
          </div>
            <div class="col-sm-2">
                <input id="street" class="form-control input-sm" type="text" placeholder="Ulica" name="street" required/>
            </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="numberHouse" class="form-control input-sm" type="text" placeholder="Numer" name="numberHouse" required/>
            </div>
          </div>
        </div>
        <div class="form-group">
            <label for="militaryRank" class="col-sm-2 control-label" name="militaryRank" required>Stopień *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <select class="form-control input-sm" id="militaryRank" name="militaryRank" required>
                <option>-------------------------------</option>
                <option>szeregowy</option>
                <option>st. szeregowy</option>
                <option>kapral</option>
                <option>st. kapral</option>
                <option>plutonowy</option>
                <option>mł. sierżant</option>
                <option>sierżant</option>
                <option>st. sierżant</option>
                <option>mł. sierżant sztabowy</option>
                <option>sierżant sztabowy</option>
                <option>st. sierżant sztabowy</option>
                <option>mł. chorąży</option>
                <option>chorąży</option>
                <option>st. chorąży</option>
                <option>mł. chorąży sztabowy</option>
                <option>chorąży sztabowy</option>
                <option>st. chorąży sztabowy</option>
                <option>podporucznik</option>
                <option>porucznik</option>
              </select>
            </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">JW *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="jwNumber" class="form-control input-sm" type="text" placeholder="Numer Jednostki Wojskowej"  name="jwNumber" required/>
            </div>
        </div>
      </div>
        <div class="form-group">
            <label for="missions" class="col-sm-2 control-label" name="missions" required>Misje *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <select class="form-control input-sm" id="missions" name="missions" required>
                <option>-------------------------------</option>
                <option>Brak</option>
                <option>Misje doraźne</option>
                <option>Misje stałe</option>
              </select>
            </div>
        </div>
      </div>
        <div class="form-group">
            <label for="training" class="col-sm-2 control-label" name="training" required>Szkolenia *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <select class="form-control input-sm" id="training" name="training" required>
                 <option>-------------------------------</option>
                 <option>Brak</option>
                 <option>Sprawnościowe</option>
                 <option>Techniczne</option>
                 <option>Specjalistyczne</option>
              </select>
            </div>
        </div>
      </div>
        <div class="form-group">
          <label for="equipment" class="col-sm-2 control-label" name="equipment" required>Ekwipunek *</label>
           <div class="col-sm-2">
             <div class="input-group">
               <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
             <select class="form-control input-sm" id="weapon" name="weapon" required>
                <option>Broń</option>
                <option>Brak</option>
                <option>Pistolet</option>
                <option>Pistolet maszynowy</option>
                <option>Karabin maszynowy</option>
             </select>
           </div>
           </div>
           <div class="col-sm-2">
             <div class="input-group">
               <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
             <input id="weaponsNumber" class="form-control input-sm" type="text" name="weaponsNumber" required placeholder="Numer broni"/>
           </div>
         </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <select class="form-control input-sm" id="equipmentSoldier" name="equipmentSoldier" required>
                <option>Wyposażenie</option>
                <option>Brak</option>
                <option>Pełne</option>
                <option>Częściowe </option>
              </select>
            </div>
             </div>
             <div class="col-sm-2">
               <div class="input-group">
                 <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="someText" class="form-control input-sm" type="text" name="someText" required placeholder="Opis"/>
             </div>
           </div>
        </div>






        <div class="form-group">
            <div class="col-sm-offset-2  control-label col-sm-8">
                <a href="soldier.php?action=list"><span class="btn   btn-info"/>Listuj</a>&emsp;<input type="submit" class="btn   btn-success" name="register" value="Zapisz"/>
            </div>
        </div></h6>
    </form>
          <script type="text/javascript">
          $(function () {
              $('#datetimepicker1').datetimepicker();
          });
          </script>
  </div>
</div>


<!-- Footer -->
<?php require('./views/partial/footer.php');?>
<!-- End Footer -->
