<?php
require "../../../tools/connectToDB.php";

    $result = runSqlQuery("
        SELECT login_id
        FROM Team
        ");
    $idList;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $idList[] = $row['login_id'];
        }
    }
    $idString = implode(' ',$idList);
    echo "<textarea rows='4' cols='50' type='text' name='teamNames'  >{$idString} </textarea>";
    echo "<script>let teamIds = ".json_encode($idList)."</script>";
