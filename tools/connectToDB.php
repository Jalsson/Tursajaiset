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
