<?php 
require_once "connectToDB.php";

$loginID = $_POST["loginID"];
$hintNumber = $_POST["hintNumber"];
$scoresIDArray;
$result = RunSqlQuery("
    SELECT bar_scores_ids
    FROM Team
    WHERE Team.login_id = $loginID;
    ");
if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        
        $scoresIDArray = json_decode($row['bar_scores_ids']);
        
        RunSqlQuery("
            UPDATE Bar_score
            SET Bar_score.bar_hint_rev = 1
            WHERE Bar_score.id = {$scoresIDArray[$hintNumber]};
            ");
    }
}
?>