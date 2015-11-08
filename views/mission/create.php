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

    <h5>Dodaj Misje</h5>
    <form method="post" action="mission.php?action=create" name="mission-create" class="form-horizontal"><h6>

        <div class="form-group">
            <label for="login_input_username" class="col-sm-2 control-label">Kryptonim Misji *</label>

            <div class="col-sm-8">
                <input id="missionNumber" class="form-control login_input input-sm" type="text" placeholder="Akronim Misji"  name="missionNumber" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="missionName" class="col-sm-2 control-label" name="missionName" required>Rodzaj Misji *</label>

            <div class="col-sm-8">
              <select class="form-control input-sm" id="missionName" name="missionName" required>
                <option>-------------------------------</option>
                <option>Misja Doraźna</option>
                <option>Misja Stała</option>
                <option>Misja Specjalna</option>
              </select>
            </div>
        </div>
        <div class="form-group">
            <label for="missionLocation" class="col-sm-2 control-label" name="missionLocation" required>Lokalizacja Misji *</label>

            <div class="col-sm-8">
              <select class="form-control input-sm" id="missionLocation" name="missionLocation" required>
                <option>-------------------------------</option>
                <option>Irak</option>
                <option>Syria</option>
                <option>Ukraina</option>
                <option>Afganistan</option>
              </select>
            </div>
        </div>




        <div class="form-group">
            <label for="missionDate" class="col-sm-2 control-label">Data Rozpoczęcia *</label>

            <div class="col-sm-8">
                <input id="missionDate" class="form-control login_input input-sm" type="text" placeholder="Data Rozpoczęcia Misji" name="missionDate" required/>
            </div>
            <script src="js/jquery-1.9.1.min.js"></script>
            <script src="js/bootstrap-datepicker.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#missionDate').datepicker({
                        format: "dd/mm/yyyy"
                    });
                });
            </script>

        </div>
        <div class="form-group">
            <label for="missionDate1" class="col-sm-2 control-label">Data Zakończenia *</label>

            <div class="col-sm-8">
                <input id="missionDate1" class="form-control login_input input-sm" type="text" placeholder="Data Rozpoczęcia Misji" name="missionDate1" required/>
            </div>
            <script src="js/jquery-1.9.1.min.js"></script>
            <script src="js/bootstrap-datepicker.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#missionDate1').datepicker({
                        format: "dd/mm/yyyy"
                    });
                });
            </script>

        </div>



        <div class="form-group">
            <div class="col-sm-offset-2  control-label col-sm-8">
                <a href="mission.php?action=list"><span class="btn   btn-info"/>Listuj</a>&emsp;<input type="submit" class="btn   btn-success" name="register" value="Zapisz"/>
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
