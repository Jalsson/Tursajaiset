<?php 
require "../../../tools/connectToDB.php";

    $result = runSqlQuery("
            SELECT  name
            FROM Region
        ");
        
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li onclick='setRegion(event)'> {$row['name']} </li>";
        }
    }
?>
