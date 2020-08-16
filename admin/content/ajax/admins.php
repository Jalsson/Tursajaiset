<?php 

    $adminResult = runSqlQuery("
            SELECT  username
            FROM Admin
        ");
        
        // echoing list of users that were found
    if ($adminResult->num_rows > 0) {
        while($row = $adminResult->fetch_assoc()) {
            echo "<li onclick='setAdmin(event)'> {$row['username']} </li>";
        }
    }
?>