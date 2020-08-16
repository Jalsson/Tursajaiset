<?php
function SearchWithID($tableToFind, $loginID, $itemsToSelect)
{

    require 'dbconfig.php';

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //sql command for getting team info
    $sql = "Select $itemsToSelect
        FROM $tableToFind
        WHERE login_id = $loginID";

    $result = $conn->query($sql);

    //always close connection
    $conn->close();

    return $result;
}

function RunSqlQuery($sqlCmd)
{

    require 'dbconfig.php';

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //sql command for getting team info
    $sql = $sqlCmd;

    $result = $conn->query($sql);

    //always close connection
    $conn->close();

    return $result;
}

function p_Statement_log($logName,$actionType,$actionMessage,$user){
    
    require 'dbconfig.php';
    require_once 'dataLoggin.php';
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $stmt = $conn->prepare("INSERT INTO {$logName} (time_10, action_type, action_message, user) VALUES (?,?,?,?)");
    $stmt->bind_param("iisi", get10MinuteTime(), $actionType, $actionMessage, $user);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
}

function p_Statement_Feedback($feedback,$user){
    
    require 'dbconfig.php';
    require_once 'dataLoggin.php';
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $stmt = $conn->prepare("INSERT INTO Feedback(name,comment) VALUES (?,?)");
    $stmt->bind_param("ss",$user,$feedback);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
}

function p_Statement_teamName($nameToUpdate,$loginID){
    
require 'dbconfig.php';
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $stmt = $conn->prepare("UPDATE Team SET Team.name = ? Where Team.login_id = ?;");
    $stmt->bind_param("si", $nameToUpdate,$loginID);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
}

function p_Statement_Score($score,$comment,$userName,$scoreID){
    
require 'dbconfig.php';
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    $stmt = $conn->prepare("UPDATE Score
                        SET Score.score = ?, Score.comment = ?, Score.comment_writer = (SELECT id FROM Admin WHERE username = ?)
                        WHERE Score.id = ?;");
    $stmt->bind_param("issi", $score,$comment,$userName,$scoreID);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
}

function p_Statement_Notification($message,$importance){
    require 'dbconfig.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn = new mysqli($servername, $username, $password, $dbname);

$stmt = $conn->prepare("INSERT INTO Notification (message, importance) VALUES (?,?)");
$stmt->bind_param("si", $message,$importance);
$stmt->execute();
 $insertedID =  $stmt->insert_id;
$stmt->close();
$conn->close();
return $insertedID;
}

function InsertSql($sqlCmd)
{

    require 'dbconfig.php';

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = $sqlCmd;

    if ($conn->query($sql) === true) {
        $last_id = $conn->insert_id;
        return $last_id;
    } else {
        return null;
    }

    //always close connection
    $conn->close();
}



function getNumberOfRows($table)
{
    require 'dbconfig.php';

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT COUNT(*) FROM {$table};";

    $result = $conn->query($sql);
    
    //always close connection
    $conn->close();
    
    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            return $row['COUNT(*)'];
        }
    }

}
?>