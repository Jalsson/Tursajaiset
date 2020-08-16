<?php
require "connectToDB.php";

// if these values are posted. we add new hints with for loop
if (isset($_POST['hintCount']) && isset($_POST['bars']) && isset($_POST['hints'])) {

    $hintCount = $_POST['hintCount'];
    $barsArray = json_decode($_POST['bars']);
    $hintsArray = json_decode($_POST['hints']);

    for ($i = 0; $i < $hintCount; $i++) {
        RunSqlQuery("
    INSERT INTO Bar (hint, name)
    VALUES('{$hintsArray[$i]}', '{$barsArray[$i]}');
    ");
    }
    
    session_start();
    $_SESSION['message'] = 'Vihjeet lisätty';
    $_SESSION['messageType'] = 'success';
    echo " 
        <script>
        location.reload();
        </script>
        ";
}

//this is responsible for updating given bar id with posted name and hint
if (isset($_POST['updatedBarName']) && isset($_POST['updatedBarHint']) && isset($_POST['id'])) {

  $barName = $_POST['updatedBarName'];
  $barHint = $_POST['updatedBarHint'];
  $barAdmin = str_replace(' ', '', $_POST['updatedBarOwner']);
  $id = $_POST['id'];
  
  RunSqlQuery("
    UPDATE Bar
    SET Bar.name = '{$barName}' , Bar.hint = '{$barHint}', Bar.admin = (SELECT id FROM Admin WHERE username='$barAdmin')
    WHERE Bar.id = {$id};
    ");

    echo "
        <script>$.notify('vihje päivitetty', {
          style: 'message',
          className: 'success'
        });</script>
        ";
}

//this is run if we want to delete a hint
if (isset($_POST['deletedBarName']) && isset($_POST['deletedBarHint']) && isset($_POST['id'])) {

  $id = $_POST['id'];

  RunSqlQuery("
      DELETE FROM Bar
      WHERE Bar.id = {$id};
    ");
    session_start();
    $_SESSION['message'] = 'vihje poistettu';
    $_SESSION['messageType'] = 'warning';
    echo " 
        <script>
        location.reload();
        </script>
        ";
}

//this query is run if we want to create new region
if (isset($_POST['barsArray']) && isset($_POST['regionName'])) {

  $barsArray = json_decode($_POST['barsArray']);
  $regionName = $_POST['regionName'];

    // first we insert new region to Region table
  RunSqlQuery("
  INSERT INTO Region (name)
  VALUES('{$regionName}');
  ");

// then we insert all needed relations to Region_relation table
for($i = 0; $i < count($barsArray); $i++){
    RunSqlQuery("
  INSERT INTO Region_relation (region_id, bar_id)
  VALUES((SELECT id FROM Region WHERE Region.name = '{$regionName}'), {$barsArray[$i]});
  ");
}
  session_start();
  $_SESSION['message'] = 'Uusi alue lisätty';
  $_SESSION['messageType'] = 'success';
  echo " 
      <script>
      location.reload();
      </script>
      ";
}

//this query deletes the given region and all relations to that region
if (isset($_POST['deletedRegion']) && isset($_POST['id'])) {

  $id = $_POST['id'];

  RunSqlQuery("
      DELETE FROM Region
      WHERE Region.id = {$id};
    ");
  RunSqlQuery("
      DELETE FROM Region_relation
      WHERE Region_relation.region_id = {$id};
    ");
    session_start();
    $_SESSION['message'] = 'Alue poistettu';
    $_SESSION['messageType'] = 'warning';
    echo " 
        <script>
        location.reload();
        </script>
        ";
}