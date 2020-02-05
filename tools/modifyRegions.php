<?php
require "connectToDB.php";

if (isset($_POST['hintCount']) && isset($_POST['bars']) && isset($_POST['hints'])) {

    $hintCount = $_POST['hintCount'];
    $barsArray = json_decode($_POST['bars']);
    $hintsArray = json_decode($_POST['hints']);

    for ($i = 0; $i < $hintCount; $i++) {
        RunSqlQuery("
    INSERT INTO Bar_hint (bar_hint, bar_name)
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

if (isset($_POST['updatedBarName']) && isset($_POST['updatedBarHint']) && isset($_POST['id'])) {

  $barName = $_POST['updatedBarName'];
  $barHint = $_POST['updatedBarHint'];
  $id = $_POST['id'];
  
  RunSqlQuery("
    UPDATE Bar_hint
    SET Bar_hint.bar_name = '{$barName}' , Bar_hint.bar_hint = '{$barHint}'
    WHERE Bar_hint.id = {$id};
    ");

    echo "
        <script>$.notify('vihje päivitetty', {
          style: 'message',
          className: 'success'
        });</script>
        ";
}

if (isset($_POST['deletedBarName']) && isset($_POST['deletedBarHint']) && isset($_POST['id'])) {

  $id = $_POST['id'];

  RunSqlQuery("
      DELETE FROM Bar_hint
      WHERE Bar_hint.id = {$id};
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

if (isset($_POST['barsArray']) && isset($_POST['regionName'])) {

  $barsArray = $_POST['barsArray'];
  $regionName = $_POST['regionName'];

  RunSqlQuery("
  INSERT INTO Region (region_name, bar_hint_ids)
  VALUES('{$regionName}', '{$barsArray}');
  ");

  session_start();
  $_SESSION['message'] = 'Uusi alue lisätty';
  $_SESSION['messageType'] = 'success';
  echo " 
      <script>
      location.reload();
      </script>
      ";
}

if (isset($_POST['deletedRegion']) && isset($_POST['id'])) {

  $id = $_POST['id'];

  RunSqlQuery("
      DELETE FROM Region
      WHERE Region.id = {$id};
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