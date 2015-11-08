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

    <h5>Dodaj Jednostkę Wojskową</h5>
    <form method="post" action="military.php?action=create" name="military-create" class="form-horizontal"><h6>

        <div class="form-group">
            <label for="login_input_username" class="col-sm-2 control-label">Nazwa Jednostki *</label>

            <div class="col-sm-8">
                <input id="login_input_username" class="form-control login_input input-sm" type="text" placeholder="Pełna nazwa "  name="militaryName" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="login_input_email" class="col-sm-2 control-label">Numer Jednostki *</label>

            <div class="col-sm-8">
                <input id="login_input_email" class="form-control login_input input-sm" type="text" placeholder="Numer jednostki" name="militaryNumber" required/>
            </div>
        </div>
        <div class="form-group">
          <label for="equipment" class="col-sm-2 control-label" name="equipment" required>Rodzaj Jednostki *</label>

           <div class="col-sm-8">
             <select class="form-control input-sm" id="militarySelect" name="militarySelect" required>
                <option>Wybierz</option>
                <option>Wojska Lądowe</option>
                <option>Wojska Lotnicze</option>
                <option>Marynarka Wojenna</option>
             </select>
           </div>
        </div>
        <div class="form-group">
          <label for="equipment" class="col-sm-2 control-label" name="equipment" required>Dowódca Jednostki *</label>
          <div class="col-sm-4">
            <input id="militaryCommander" class="form-control input-sm" type="text" name="militaryCommander" required placeholder="Imię Nazwisko"/>
          </div>
           <div class="col-sm-4">
             <select class="form-control input-sm" id="militaryCommander1" name="militaryCommander1" required>
                <option>Stopień</option>
                <option>podporucznik</option>
                <option>porucznik</option>
                <option>kapitan</option>
                <option>major</option>
                <option>podpułkownik</option>
                <option>pułkownik</option>
                <option>generał</option>
             </select>
           </div>
        </div>

        <div class="form-group">
            <label for="phone" class="col-sm-2 control-label" name="phone" required>Telefon *</label>

            <div class="col-sm-8">
                <input id="phone" class="form-control login_input input-sm" type="text" placeholder="Numer Kontaktowy Jednostki" maxlength="9" name="phone" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label" name="email" required>Mail *</label>

            <div class="col-sm-8">
                <input id="email" class="form-control login_input input-sm" type="text" placeholder="jw@jednostka" name="email" required/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Adres *</label>
            <div class="col-sm-2">
              <input id="code" class="form-control input-sm" type="text"  placeholder="Kod Pocztowy" name="code" required/>
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
            <div class="col-sm-offset-2  control-label col-sm-8">
                <a href="military.php?action=list"><span class="btn   btn-info"/>Listuj</a>&emsp;<input type="submit" class="btn   btn-success" name="register" value="Zapisz"/>
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
