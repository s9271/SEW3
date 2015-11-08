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

    <h5>Dodaj Użytkownika</h5>
    <form method="post" action="user.php?action=create" name="user-create" class="form-horizontal"><h6>

    <div class="form-group">
      <label for="login_input_username" class="col-sm-2 control-label">Kryptonim *</label>
    <div class="col-sm-8">
      <input id="login_input_username" class="form-control login_input input-sm" type="text" pattern="[a-zA-Z0-9]{2,64}[0123a-ce-jyz\^\-]" placeholder="Kryptonim Użytkownika" name="user_name" required />
      </div>
    </div>
    <div class="form-group">
      <label for="login_input_username" class="col-sm-2 control-label">Imie *</label>
    <div class="col-sm-8">
      <input id="first_name" class="form-control login_input input-sm" type="text" pattern="[a-zA-Z0-9]{2,64}" placeholder="Imie Użytkownika" name="first_name" required />
      </div>
    </div>
    <div class="form-group">
      <label for="login_input_username" class="col-sm-2 control-label">Nazwisko *</label>
    <div class="col-sm-8">
      <input id="second_name" class="form-control login_input input-sm" type="text" pattern="[a-zA-Z0-9]{2,64}" placeholder="Nazwisko Użytkownika" name="second_name" required />
      </div>
    </div>
    <div class="form-group">
        <label for="birthday" class="col-sm-2 control-label">Data *</label>

        <div class="col-sm-8">
            <input id="birthday" class="form-control login_input input-sm" type="text" placeholder="Data Urodzenia Użytkownika" name="birthday" required/>
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
    <div class="form-group">
    <label for="login_input_password_new" class="col-sm-2 control-label">Hasło *</label>
    <div class="col-sm-8">
    <input id="login_input_password_new" class="form-control login_input input-sm" type="password" placeholder="Hasło Użytkownika" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
      </div>
    </div>
    <div class="form-group">
    <label for="login_input_password_repeat" class="col-sm-2 control-label">Powtórz hasło *</label>
    <div class="col-sm-8">
    <input id="login_input_password_repeat" class="form-control login_input input-sm" type="password" placeholder="Powtórz Hasło Użytkownika" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
      </div>
    </div>
    <div class="form-group">
      <label for="login_input_email" class="col-sm-2 control-label">Email *</label>
    <div class="col-sm-8">
      <input id="user_email" class="form-control login_input input-sm" type="email" placeholder="Email Użytkownika" name="user_email" required />
      </div>
    </div>
    <div class="form-group">
      <label for="login_input_email" class="col-sm-2 control-label">Telefon *</label>
    <div class="col-sm-8">
      <input id="phone" class="form-control login_input input-sm" type="text" placeholder="Telefon Użytkownika" name="phone" required />
      </div>
    </div>
    <div class="form-group">
        <label for="militaryRank" class="col-sm-2 control-label" name="militaryRank" required>Stopień *</label>

        <div class="col-sm-8">
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
    <div class="form-group">
        <label class="col-sm-2 control-label">Adres *</label>
        <div class="col-sm-2">
          <input id="code" class="form-control input-sm" type="text"  placeholder="Kod Pocztowy" name="code" maxlength="5" required/>
        </div>

        <div class="col-sm-2">
          <input id="city" class="form-control input-sm" type="text" placeholder="Miasto" name="city" required/>
        </div>
        <div class="col-sm-2">
            <input id="street" class="form-control input-sm" type="text" placeholder="Ulica" name="street" required/>
        </div>
        <div class="col-sm-2">
            <input id="numberHouse" class="form-control input-sm" type="text" placeholder="Numer" name="numberHouse" required/>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">JW *</label>

        <div class="col-sm-8">
            <input id="jwNumber" class="form-control input-sm" type="text" placeholder="Numer Jednostki Wojskowej"  name="jwNumber" required/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2  control-label col-sm-8">
            <a href="user.php?action=list"><span class="btn   btn-info"/>Listuj</a>&emsp;<input type="submit" class="btn   btn-success" name="user" value="Zapisz"/>
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
