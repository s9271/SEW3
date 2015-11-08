<!-- Header -->
<?php require('views/partial/header.php');?>
<!-- End Header -->

<div class="container">
    <div class="alert alert-success" role="alert" ><span class="glyphicon glyphicon-star"</span><h4>S.E.W.</h4>Brak dostępu. Proszę się zalogować !</div>
    <?php
    if (isset($login)) {
        if ($login->errors) {
            foreach ($login->errors as $error) {
                echo '<div class="alert alert-success" role="alert">' . $error .'</div>';
            }
        }
        if ($login->messages) {
            foreach ($login->messages as $message) {
                echo '<div class="alert alert-success" role="alert">' . $message .'</div>';
            }
        }
    }
    ?>
    <div class="alert alert-success" role="alert">
      <form method="post" action="index.php" name="loginform" class="form-horizontal">

          <div class="form-group">

            <label class="col-sm-2 control-label" for="login_input_username" id="login_input"><h6>Użytkownik</h6></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-user"></span></span>
              <input id="login_input" class="form-control" type="user_name reset" aria-describedby="basic-addon1" name="user_name" autocomplete="off" required/>
            </div>
          </div>
        </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="login_input_password"><h6>Hasło</h6></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-log-in"></span></span>
              <input id="login_input_password" class="form-control" type="password" aria-describedby="basic-addon1" name="user_password" autocomplete="off" required />
            </div>
          </div>
        </div>
          <div class="form-group">
            <div class="col-sm-offset-2  control-label col-sm-10">
          <input type="submit" class="btn btn-success" name="login" value="Zaloguj" />
        </div>
      </div>
      </form>
  </div>

  <div class="container">
    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Zapomniałem hasła</button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>

          </div>
          <div class="modal-body"></br>
            <?php
            if (isset($login)) {
                if ($login->errors) {
                    foreach ($login->errors as $error) {
                        echo '<div class="alert alert-success" role="alert">' . $error .'</div>';
                    }
                }
                if ($login->messages) {
                    foreach ($login->messages as $message) {
                        echo '<div class="alert alert-success" role="alert">' . $message .'</div>';
                    }
                }
            }
            ?>
            <form class="form-horizontal" role="form">
              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Login:</label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-user"></span></span>
                  <input type="email" class="form-control" id="user_name" placeholder="Wpisz swój login">
                </div>
              </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Email:</label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-envelope"></span></span>
                  <input type="email" class="form-control" id="email" placeholder="Wpisz swój adres email">
                </div>
              </div>
              </div>
              <fieldset disabled>
              <div class="form-group">
                <label class="control-label col-sm-2" for="disabledTextInput">Temat:</label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-question-sign"></span></span>
                  <input type="password" class="form-control" id="pwd" placeholder="Zapomniałem hasła, proszę o zresetowanie.">
                </div>
              </div>
              </div>
              </fieldset>
            </form>

          </div>
          <div class="modal-footer"><p><center><h6><font color="red"><b>Uwaga!&nbsp;</b></font><font color="gray">Administrator skontaktuję się z Tobą !</font></h6></center></p>
            <!--<button type="submit" class="btn btn-warning"><a href="index.php">Wyślij</a></button>-->
            <input type="submit" class="btn btn-success" name="login" value="Wyślij" />
          </div>
        </div>

      </div>
    </div>

  </div>

</div>
<!-- End Body -->
