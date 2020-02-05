<?php
$hintNumber = $_GET["hint"];
$loginID = $_SESSION["loginID"];
$hintArray;
// Getting Hint number from database with GET from url
$result = RunSqlQuery("
        SELECT Region.bar_hint_ids
        FROM Region
        INNER JOIN 
        Team
        ON Region.id = Team.region_id
        WHERE Team.login_id = {$loginID};

    ");
if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        $hintArray = json_decode($row['bar_hint_ids']);
        
        $result = RunSqlQuery("
                SELECT bar_hint
                FROM Bar_hint
                WHERE Bar_hint.id = {$hintArray[$hintNumber]};
            ");
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                echo $row['bar_hint'];
                echo "<br>";
            }
        }
    }
}

// getting info from DB if team has revealed their bar
$result = RunSqlQuery("
    SELECT bar_scores_ids
    FROM Team
    WHERE Team.login_id = $loginID;
    ");
if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        
        $scoresIDArray = json_decode($row['bar_scores_ids']);
        
        $result = RunSqlQuery("
                SELECT bar_hint_rev
                FROM Bar_score
                WHERE Bar_score.id = {$scoresIDArray[$hintNumber]};
            ");
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $isRevealed = $row['bar_hint_rev'];
            }
        }
                        //IF isVealed is 0, user has not revealed 
        //the bar yet so we give him a button to do so if the team so wishes
        if ($isRevealed == 0) {
            include "reveal_button.php";

        } 
        //if it's 1 it has been revealed and we get the propriet bar name from database and echo it
        else if($isRevealed == 1){
                $result = RunSqlQuery("
                            SELECT bar_name
                            FROM Bar_hint
                            WHERE Bar_hint.id = {$hintArray[$hintNumber]};
                        ");
                    if ($result->num_rows > 0) {
                        if ($row = $result->fetch_assoc()) {
                            echo $row['bar_name'];
                            echo "<br>";
                    }
                    }
            }

    }
}
