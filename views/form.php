<!-- Header -->
<?php require('views/partial/header.php');
?>

<!-- End Header -->


<!-- Top Nav -->
<?php require('views/partial/top-nav.php');
?>
<!-- End Top Nav -->



<?php

if (isset($FormAdd)) {
    if ($FormAdd->errors) {
        foreach ($FormAdd->errors as $error) {

        }
    }
    if ($FormAdd->messages) {
        foreach ($FormAdd->messages as $message) {
            echo $message;
        }
    }
}
?>

<div class="container" >
  <div class="jumbotron">
    <div class="alert alert-success fade in">
      <form method="post" action="FormAdd.php" name="form" class="form-horizontal">
        <span><h4>Nowy żołnierz</h4></span>
        <div class="form-group">
          <label for="id" class="col-sm-2 control-label">Id</label>
            <div class="col-sm-8">
              <input disabled="disabled" id="id" class="form-control input-sm" type="text" name="id"/>
            </div>
          </div>

        <div class="form-group">
          <label for="soldierName" class="col-sm-2 control-label">Imie</label>
        <div class="col-sm-8">
          <input id="soldierName" class="form-control input-sm" type="text" pattern="[a-zA-Z0-9]{2,64}" name="soldierName" required  placeholder="Imie żołnierza"/>
          </div>
        </div>

        <div class="form-group">
          <label for="soldierSurname" class="col-sm-2 control-label">Nazwisko</label>
        <div class="col-sm-8">
          <input id="soldierSurname" class="form-control input-sm" type="text" pattern="[a-zA-Z0-9]{2,64}" name="soldierSurname" required placeholder="Nazwisko żołnierza"/>
          </div>
        </div>

        <div class="form-group">
            <label for="soldierSurname" class="col-sm-2 control-label">Data</label>
              <div class="col-sm-8">
                <input  type="text" class="form-control input-sm" placeholder="Data urodzenia"  id="calendar">
             </div>
                <script src="js/jquery-1.9.1.min.js"></script>
                <script src="js/bootstrap-datepicker.js"></script>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#calendar').datepicker({
                            format: "dd/mm/yyyy"
                        });
                    });
                </script>
        </div>

        <div class="form-group">
          <label for="sex" class="col-sm-2 control-label">Płeć</label>
          <div class="col-sm-8">
            <select class="form-control input-sm" id="sex">
              <option>-------------------------------</option>
              <option>Kobieta</option>
              <option>Mężczyzna</option>
            </select>
          </div>
        </div>

         <div class="form-group">
           <label for="phone" class="col-sm-2 control-label">Telefon</label>
         <div class="col-sm-8">
           <input type="tel" class="form-control input-sm" name="phone" id="phone" required placeholder="600-000-000"/>
           </div>
         </div>

          <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-8">
            <input id="email" class="form-control input-sm" type="email" name="email" required placeholder="Email@żołnierza"/>
            </div>
          </div>

          <div class="form-group">
            <label for="address" class="col-sm-2 control-label">Adres</label>
            <div class="col-sm-2">
              <input id="code" class="form-control input-sm" type="text" name="code" maxlength="5" required placeholder="Kod Pocztowy"/>
            </div>

            <div class="col-sm-2">
              <input id="city" class="form-control input-sm" type="text" name="city" required placeholder="Miasto"/>
            </div>
            <div class="col-sm-2">
                <input id="street" class="form-control input-sm" type="text" name="street" required placeholder="Ulica"/>
            </div>
            <div class="col-sm-2">
                <input id="numberHouse" class="form-control input-sm" type="text" name="numberHouse" required placeholder="Numer"/>
            </div>
          </div>

          <div class="form-group">
           <label for="militaryRank" class="col-sm-2 control-label">Stopień</label>
           <div class="col-sm-8">
             <select class="form-control input-sm" id="militaryRank">
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
           <label for="jwNumber" class="col-sm-2 control-label">JW numer</label>
         <div class="col-sm-8">
           <input id="jwNumber" class="form-control input-sm" type="text" pattern="[a-zA-Z0-9]{2,64}" name="jwNumber" required  placeholder="Numer Jednostki Wojskowej"/>
           </div>
         </div>

          <div class="form-group">
              <label for="missions" class="col-sm-2 control-label">Misje</label>
            <div class="col-sm-8">
              <select class="form-control input-sm" id="missions">
                <option>-------------------------------</option>
                <option>Brak</option>
                <option>Misje doraźne</option>
                <option>Misje stałe</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="training" class="col-sm-2 control-label">Szkolenia</label>
            <div class="col-sm-8">
              <select class="form-control input-sm" id="training">
                 <option>-------------------------------</option>
                 <option>Brak</option>
                 <option>Sprawnościowe</option>
                 <option>Techniczne</option>
                 <option>Specjalistyczne</option>
              </select>
            </div>
          </div>

           <div class="form-group">
             <label for="equipment" class="col-sm-2 control-label">Ekwipunek</label>
              <div class="col-sm-2">
                <select class="form-control input-sm" id="weapon">
                   <option>Broń</option>
                   <option>Brak</option>
                   <option>Pistolet</option>
                   <option>Pistolet maszynowy</option>
                   <option>Karabin maszynowy</option>
                </select>
              </div>
              <div class="col-sm-2">
                <input id="weaponsNumber" class="form-control input-sm" type="text" name="weaponsNumber" required placeholder="Numer broni"/>
              </div>
               <div class="col-sm-2">
                 <select class="form-control" id="equipmentSoldier">
                   <option>Wyposażenie</option>
                   <option>Brak</option>
                   <option>Pełne</option>
                   <option>Częściowe </option>
                 </select>
                </div>
                <div class="col-sm-2">
                   <input id="someText" class="form-control input-sm" type="text" name="someText" required placeholder="Opis"/>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-2  control-label col-sm-8">
                  <input type="submit" class="btn-sm  btn-success" name="register" value="Zapisz" />
                  <form method="post" action="form.php">
                  <input type="reset" class="btn-sm  btn-info" name="register" value="Resetuj" /></form>
                </div>
              </div>
            </form>
            <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
        </script>
          </div>
        </div>
      </div>
