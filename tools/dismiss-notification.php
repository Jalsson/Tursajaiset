<?php 
require_once "connectToDB.php";
if (isset($_POST['notificationId']) && isset($_POST['teamID'])) {
    
    $notificationID = $_POST['notificationId'];
    $teamID = $_POST['teamID'];
        require 'dbconfig.php';
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $stmt = $conn->prepare("UPDATE Notification_relation SET Notification_relation.seen = ? Where Notification_relation.notification_id = ? AND Notification_relation.team_id = (SELECT id FROM Team WHERE Team.login_id = ?);");
    $stmt->bind_param("iii",$a = 1,$notificationID,$teamID);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
    p_Statement_log("Team_log",6,"poisti ilmoituksen",$teamID);
    echo"
    <script>$('#notification-row-{$notificationID}').remove();</script>
    ";
} 

?>