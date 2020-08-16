<div style="background: #00000087;max-width: 100%;margin: 15px;color: white;padding: 5px 20px;border-radius: 10px;"><h1 style="margin: 0px;">Pisteet</h1>
</div>
<?php
// bonus visitor has 1 in it because thats the bonus bar id
$result = RunSqlQuery("SELECT 
(SELECT COUNT(id)
FROM Score
	INNER JOIN Score_relation ON Score_relation.bar_id = 1
    WHERE Score.id = Score_relation.score_id AND Score.comment_writer = (SELECT id FROM Admin WHERE Admin.username='{$_SESSION['username']}')) AS bonus_visitors, 
(SELECT COUNT(id) 
FROM Score
	INNER JOIN Score_relation ON Score_relation.bar_id = (SELECT id FROM Bar WHERE Bar.admin = (SELECT id FROM Admin WHERE Admin.username = '{$_SESSION['username']}'))
WHERE Score.id = Score_relation.score_id AND Score.score > 0) AS visitors,
(SELECT COUNT(id) 
FROM Score
	INNER JOIN Score_relation ON Score_relation.bar_id = (SELECT id FROM Bar WHERE Bar.admin = (SELECT id FROM Admin WHERE Admin.username = '{$_SESSION['username']}'))
WHERE Score.id = Score_relation.score_id) AS allUsers,
(SELECT ROUND(AVG(score))
FROM Score
	INNER JOIN Score_relation ON Score_relation.bar_id = (SELECT id FROM Bar WHERE Bar.admin = (SELECT id FROM Admin WHERE Admin.username = '{$_SESSION['username']}'))
WHERE Score.id = Score_relation.score_id AND Score.score > 0) AS yourAVG, 
(SELECT ROUND(AVG(score))
FROM Score
WHERE  Score.score > 0) AS AVG;");

if ($result->num_rows > 0) {
    echo "<div class='container'>";
        if($row = $result->fetch_assoc()) {
    

            echo "
            <div class='row hint-row'>
                <div class='col '>
                    <h3 class='hint-text' style='margin-bottom: 0px;'>Kävijät</h5>
                </div>    
                <div class='col'>
                    <h3 class='hint-text' style='margin-bottom: 0px;'>{$row['bonus_visitors']} + {$row['visitors']} /{$row['allUsers']} </h5>
                </div>
                <div class='w-100'></div>
                <div class='col '>
                    <h10 class='hint-text' style='font-size: 11px;color: white;'>* bonusrasti + vihje suorittaneet/ mahdolliset kävijät <br>kokonais määrä joilla on teidän vihje lasketaan aktiivisista käyttäjistä </h10>
                </div>
                <div class='w-100'></div>
                <div class='col-7'>
                    <h5 class='hint-text'>Rastin keskiarvo pisteet</h5>
                </div>    
                <div class='col'>
                    <h3 class='hint-text'>{$row['yourAVG']}p </h3>
                </div>
                
                <div class='w-100'></div>
                
                <div class='col-7'>
                    <h5 class='hint-text'>Yhteinen piste keskiarvo</h5>
                </div>    
                <div class='col '>
                    <h3 class='hint-text'>{$row['AVG']}p </h3>
                </div>
            </div>
        </div>";
        }

}
else {
    echo "Tilastoissa on menny joku vikaan, ota yhteys HTOn ict vastaavaan👀";
    return;
}
?>