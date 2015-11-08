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

    <h5>Dodaj Szkolenie</h5>
    <form method="post" action="training.php?action=create" name="training-create" class="form-horizontal"><h6>

        <div class="form-group">
           <!-- <label for="login_input_username" class="col-sm-2 control-label">Kryptonim Szkolenia *</label> -->

            <div class="col-sm-8">
               <!-- <input id="trainingNumber" class="form-control login_input input-sm" type="text" placeholder="Akronim Szkolenia"  name="trainingNumber" required/> -->
            </div>
        </div>
        <div class="form-group">
            <label for="trainingName" class="col-sm-2 control-label" name="trainingName" required>Rodzaj Szkolenia *</label>

            <div class="col-sm-8">
              <select class="form-control input-sm" id="trainingName" name="trainingName" required>
                <option>-------------------------------</option>
                <option>Szkolenie Wstepne</option>
                <option>Szkolenie BHP </option>
                <option>Szkolenie Specjalne SZ2354</option>
                 <option>Szkolenie Specjalne SZ2444</option>
                  <option>Szkolenie Specjalne SZ2774</option>
                   <option>Szkolenie Specjalne SZ2544</option>
                <option>Szkolenie Bojowe SZB4356</option>
                   <option>Szkolenie Bojowe SZB3456</option>
                      <option>Szkolenie Bojowe SZB43436</option>
                         <option>Szkolenie Bojowe SZB4446</option>
                            <option>Szkolenie Bojowe SZB4116</option>
              </select>
            </div>
        </div>
        <div class="form-group">
            <label for="trainingLocation" class="col-sm-2 control-label" name="trainingLocation" required>Lokalizacja Szkolenia *</label>

            <div class="col-sm-8">
              <select class="form-control input-sm" id="trainingLocation" name="trainingLocation" required>
                <option>-------------------------------</option>
                <option>Osrodek 123</option>
                <option>Osrodek 555</option>
                <option>Osrodek 8732T</option>
                <option>Osrodek 995A</option>
              </select>
            </div>
        </div>




        <div class="form-group">
            <label for="trainingDate" class="col-sm-2 control-label">Data Rozpoczęcia *</label>

            <div class="col-sm-8">
                <input id="trainingDate" class="form-control login_input input-sm" type="text" placeholder="Data Rozpoczęcia Szkolenia" name="trainingDate" required/>
            </div>
            <script src="js/jquery-1.9.1.min.js"></script>
            <script src="js/bootstrap-datepicker.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#trainingDate').datepicker({
                        format: "dd/mm/yyyy"
                    });
                });
            </script>

        </div>
        <div class="form-group">
            <label for="trainingDate1" class="col-sm-2 control-label">Data Zakończenia *</label>

            <div class="col-sm-8">
                <input id="trainingDate1" class="form-control login_input input-sm" type="text" placeholder="Data Rozpoczęcia Szkolenia" name="trainingDate1" required/>
            </div>
            <script src="js/jquery-1.9.1.min.js"></script>
            <script src="js/bootstrap-datepicker.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#trainingDate1').datepicker({
                        format: "dd/mm/yyyy"
                    });
                });
            </script>

        </div>



        <div class="form-group">
            <div class="col-sm-offset-2  control-label col-sm-8">
                <a href="training.php?action=list"><span class="btn   btn-info"/>Listuj</a>&emsp;<input type="submit" class="btn   btn-success" name="register" value="Zapisz"/>
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
