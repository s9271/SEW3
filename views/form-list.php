<!-- Header -->
<?php require('views/partial/header.php');?>
<!-- End Header -->


<!-- Top Nav -->
<?php require('views/partial/top-nav.php');?>
<!-- End Top Nav -->


<!-- Body -->
<div class="container">
  <h3>Lista</h3>

<?php

if (isset($FormAdd)) {
    if ($FormAdd->errors) {
        foreach ($FormAdd->errors as $error) {
            echo $error;
        }
    }
    if ($FormAdd->messages) {
        foreach ($FormAdd->messages as $message) {
            echo $message;
        }
    }
}
?>

  <table class="table">
    <thead>
      <tr class="info">
        <th>id</th>
        <th>Imie</th>
        <th>Nazwisko</th>
        <th>Data/Ur</th>
        <th>Płeć</th>
        <th>Tele</th>
        <th>Email</th>
        <th>Kod</th>
        <th>Miasto</th>
        <th>Ulica</th>
        <th>Nr/lok</th>
        <th>JW/nazwa</th>
        <th>Misje</th>
        <th>Szkolenie</th>
        <th>Broń</th>
        <th>Nr/broni</th>
        <th>Wypos</th>
        <th>Info</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($form->items as $item): ?>
      <tr class="success">
        <th scope="row"><?php echo $item['id'] ?></th>
        <td class="danger"><?php echo $item['nick'] ?></td>
        <td><?php echo $item['street'] ?></td>
        <td class="danger"><?php echo $item['street_number'] ?></td>
        <td><?php echo $item['created_at'] ?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>

</div>



<!-- End Body -->

<!-- Footer -->
<?php require('views/partial/footer.php');?>
<!-- End Footer -->
