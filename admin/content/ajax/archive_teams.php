<?php 
require "../../../tools/connectToDB.php";

// moving all stuff from teams, and scores to the 
    runSqlQuery("
        INSERT INTO Arch_team
        SELECT * FROM Team;
        ");
        
    runSqlQuery("
         INSERT INTO Arch_Score
        SELECT * FROM Score;
        ");
    runSqlQuery("
        INSERT INTO Arch_score_relation
        SELECT * FROM Score_relation;
        ");
        
// deleting all stuf from actual tables after archive
    runSqlQuery("
        DELETE FROM Team;
        ");
    runSqlQuery("
        DELETE FROM Score;
        ");
    runSqlQuery("
        DELETE FROM Score_relation;
        ");
        
        echo "
    <script>$.notify('tiimit arkistoitu', {
      style: 'message',
      className: 'warning'
    });</script>
    ";
?>