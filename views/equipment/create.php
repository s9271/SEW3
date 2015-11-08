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

    <h5>Dodaj Uzbrojenie</h5>
    
<form method="post" action="equipment.php?action=create" name="equipment-create" class="form-horizontal"><h6>
    <!--<form method="post" action="equipment.php?action=create" name="equipment-create" class="form-horizontal"><h6>

        <div class="form-group">
            <label for="login_input_username" class="col-sm-2 control-label"> *</label>

            <div class="col-sm-8">
                <input id="equipmentNumber" class="form-control login_input input-sm" type="text" placeholder="Akronim Misji"  name="equipmentNumber" required/>
            </div>
        </div>
    -->
        <div class="form-group">
            <label for="equipmentType" class="col-sm-2 control-label" name="equipmentType" required>Rodzaj Broni *</label>

            <div class="col-sm-8">
              <select class="form-control input-sm" id="equipment" name="equipmentType" required>
                <option>-------------------------------</option>
                <option>Bron dluga</option>
                <option>Bron krotka </option>
                
              </select>
            </div>
            <br />
         </div>
         <div class="form-group">
            <label for="equipmentMark" class="col-sm-2 control-label" name="equipmentMark" required>Typ Broni *</label>

            <div class="col-sm-8">
              <select class="form-control input-sm" id="equipment" name="equipmentMark" required>
                <option>-------------------------------</option>
                <option>AK47</option>
                <option>FN P90 </option>
                 <option>MP5 </option>
                  <option>PM98 </option>
                    <option>SIG P226 </option>
                      <option>HK USP</option>
                        <option>SPP-1</option>
                          <option>Berrette 92</option>
                            <option>Glock 17</option>
                           
              </select>
            </div>
 </div>
 <div class="form-group">

            <label for="login_input_username" class="col-sm-2 control-label">Numer seryjny broni *</label>

            <div class="col-sm-8">
                <input id="equipmentSerial" class="form-control login_input input-sm" type="text" placeholder="tutaj wpisz sn broni"  name="equipmentSerial" required/>
            </div>


                
              </select>
            </div>
        



    


        <div class="form-group">
            <div class="col-sm-offset-2  control-label col-sm-8">
                <a href="equipment.php?action=list"><span class="btn   btn-info"/>Listuj</a>&emsp;<input type="submit" class="btn   btn-success" name="register" value="Zapisz"/>
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
