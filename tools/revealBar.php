<?php 
require 'dbconfig.php';
require_once "connectToDB.php";

$loginID = $_POST["loginID"];
$hintNumber = $_POST["hintNumber"];
   
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $stmt = $conn->prepare("UPDATE Score SET Score.revealed = ? Where Score.id = ?;");
    $stmt->bind_param("ii", $a = 1,$hintNumber);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
            $result = runSqlQuery("
        SELECT Bar.id
        FROM Bar
        INNER JOIN Score_relation
            ON Score_relation.score_id = {$hintNumber}
        WHERE Bar.id = Score_relation.bar_id;
        ");
        
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                p_Statement_log("Team_log",2,"{$row['id']}",$loginID);
                }
            }

    
?>