<!-- Header -->
<?php require('./views/partial/header.php'); ?>
<!-- End Header -->

<!-- Top Nav -->
<?php require('./views/partial/top-nav.php');?>
<!-- End Top Nav -->

<div class="container">
  <div class="jumbotron">
    <?php
    if (isset($registration)) {
        if ($registration->errors) {
            foreach ($registration->errors as $error) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        }
        if ($registration->messages) {
            foreach ($registration->messages as $message) {
                echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
            }
        }
    }
    ?>

    <h5>Edycja Żołnierza</h5>
    <form method="post" action="soldier.php?action=update" name="soldier-update" class="form-horizontal"><h6>

        <div class="form-group">
            <label for="login_input_username" class="col-sm-2 control-label">Imie *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="soldierName" name="soldierName" class="form-control login_input input-sm" value="<? echo $soldierName; ?>"/>
              </div>
            </div>
        </div>
        <div class="form-group">
            <label for="login_input_email" class="col-sm-2 control-label">Nazwisko *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="soldierSurname" name="soldierSurname" class="form-control login_input input-sm" value="<? echo $soldierSurname; ?>"/>
              </div>
            </div>
        </div>
        <div class="form-group">
            <label for="birthday" class="col-sm-2 control-label">Data *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="birthday" name="birthday" class="form-control login_input input-sm" value="<? echo $birthday; ?>"/>
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
              <select class="form-control input-sm" id="sex" name="sex" value="<? echo $sex; ?>">
                <option></option>
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
                <input id="phone" name="phone" class="form-control login_input input-sm" maxlength="9" value="<? echo $phone; ?>"/>
            </div>
          </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label" name="email" required>Mail *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="email" name="email" class="form-control login_input input-sm" value="<? echo $email; ?>"/>
            </div>
          </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Adres *</label>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="code" name="code" class="form-control login_input input-sm" maxlength="5" value="<? echo $code; ?>"/>
            </div>
          </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="city" name="city" class="form-control login_input input-sm" value="<? echo $city; ?>"/>
            </div>
          </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <input id="street" name="street" class="form-control login_input input-sm" value="<? echo $street; ?>"/>
            </div>
          </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                <input id="numberHouse" name="numberHouse" class="form-control login_input input-sm" value="<? echo $numberHouse; ?>"/>
            </div>
          </div>
        </div>
        <div class="form-group">
            <label for="militaryRank" class="col-sm-2 control-label" name="militaryRank" required>Stopień *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <select class="form-control input-sm" id="militaryRank" name="militaryRank" value="<? echo $militaryRank; ?>">
                <option></option>
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
                <input id="jwNumber" name="jwNumber" class="form-control login_input input-sm" value="<? echo $jwNumber; ?>"/>
            </div>
        </div>
      </div>
        <div class="form-group">
            <label for="missions" class="col-sm-2 control-label" name="missions" required>Misje *</label>

            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <select class="form-control input-sm" id="missions" name="missions" required>
                <option></option>
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
                 <option></option>
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
                <option></option>
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
               <input id="weaponsNumber" name="weaponsNumber" class="form-control login_input input-sm" value="<? echo $weaponsNumber; ?>"/>
           </div>
         </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
              <select class="form-control input-sm" id="equipmentSoldier" name="equipmentSoldier" required>
                <option></option>
                <option>Brak</option>
                <option>Pełne</option>
                <option>Częściowe </option>
              </select>
            </div>
             </div>
             <div class="col-sm-2">
               <div class="input-group">
                 <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-menu-right"></span></span>
                 <input id="someText" name="someText" class="form-control login_input input-sm" value="<? echo $someText; ?>"/>
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


  </div>
</div>
