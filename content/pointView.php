<h1>Point view page</h1>
<p>here you can see all of your points</p>

<?php

$loginID = $_SESSION['loginID'];

$result = RunSqlQuery("
    SELECT bar_scores_ids
    FROM Team
    WHERE Team.login_id = $loginID;
    ");
if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {

        $scoresIDArray = json_decode($row['bar_scores_ids']);

        for ($i = 0; $i < count($scoresIDArray); $i++) {
            $result = RunSqlQuery("
            SELECT bar_hint_rev, bar_score
            FROM Bar_score
            WHERE Bar_score.id = {$scoresIDArray[$i]};
        ");
            if ($result->num_rows > 0) {
                if ($row = $result->fetch_assoc()) {
                    $isRevealed = $row['bar_hint_rev'];
                    $barScore = $row['bar_score'];
                    if ($barScore == 0) {
                        echo "no points set yet <br>";

                    } else {
                        if ($isRevealed == 1) {
                            $barScore -= 20;
                        }
                        echo $barScore;
                        echo "<br>";

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
                                SELECT bar_name
                                FROM Bar_hint
                                WHERE Bar_hint.id = {$hintArray[$i]};
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
                }
            }
        }
    }
} else {
    echo "there should not be no result search!";
    return;
}
?>
<br>
<a href="?page=teamview">
Back</a>