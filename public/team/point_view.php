<div style="background: #00000087;max-width: 100%;margin: 15px;color: white;padding: 5px 20px;border-radius: 10px;"><h1 style="margin: 0px;">Pisteet</h1>
</div>
<?php
// below  we show team's all points by fetching it from the database
$loginID = $_SESSION['loginID'];
    p_Statement_log("Team_log",3,"Opened the points view",$loginID);
$result = RunSqlQuery("
SELECT Bar.name, Score.score, Score.revealed
FROM Score, Bar
	INNER JOIN
    Score_relation
    	ON Score_relation.team_id = (SELECT Team.id FROM Team WHERE Team.login_id = {$loginID})
WHERE Score_relation.score_id = Score.id AND Score_relation.bar_id = Bar.id AND Score.score > 0;
    ");
    
/*first we check if we can find the userID's score array*/
if ($result->num_rows > 0) {
    echo "<div class='container'>";
            while($row = $result->fetch_assoc()) {

            $barScore = $row['score'];
            if($row['revealed'] == 1){
                $barScore -= 20;
            }

            echo "
            <div class='row hint-row'>
                <div class='col-8 hint-div'>";
                
            echo "<h5 class='hint-text'>{$row['name']}</h5>";
        
            echo "</div><div class='col hint-div'><h5 style='margin-top: 10px;'>{$barScore}</h5><p style='margin: 0;margin-bottom: 5px;'> /100</p> <img class='score-circle-image' src='images/circle-icon.png' alt='circle'></div>
                </div>";
        }
    echo "</div>";
}
else {
    echo "Et ole vielä saanut yhtään pistettä😛";
    return;
}
?>
