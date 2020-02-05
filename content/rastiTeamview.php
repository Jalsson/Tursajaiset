<?php

require "../tools/connectToDB.php";

$loginID = $_POST['teamID'];

$result = SearchWithID("Team", $loginID, "team_name");

if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        $teamName = $row["team_name"];
    }
    echo "<h1> $teamName </h1>";
}

for ($i = 1; $i < 5; $i++) {

    $result = RunSqlQuery("
        SELECT bn.bar_name_{$i}
                FROM Team as t
                INNER JOIN
                Region as r
                ON t.region_id = r.id
                INNER JOIN
                Bar_names AS bn
                ON r.bar_hints_id = bn.id
                WHERE t.login_id = {$loginID};
        ");
    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            $barName = $row["bar_name_{$i}"];
            echo "
                <form action='https://htory.fi/kisapanel/?page=rastiSetScore' method='post' style='margin-bottom: 0px;'>
                <input type='hidden' id='barName{$i}' name='barID' value='{$i}'>
                <input type='hidden' id='teamID' name='teamID' value='{$loginID}'>
                    <button type='submit' name='your_name' value='your_value' class='btn-link'>lisää {$barName} pisteet</button>
                </form>
                ";
        }
    }

    $result = RunSqlQuery("
        SELECT bs.bar_{$i}_score
            FROM Team as t
            INNER JOIN
            Bar_scores AS bs
            ON t.bar_scores_id = bs.id
            WHERE t.login_id = {$loginID};
        ");

    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            $barScore = $row["bar_{$i}_score"];
            if ($barScore == null) {
                echo "Ei lisätty pisteitä <br>";
            } else {
                $result = RunSqlQuery("
                        SELECT hr.bar_hint_{$i}_rev
                    FROM Team as t
                    INNER JOIN
                    Hint_reveals AS hr
                    ON t.hint_reveals_id = hr.id
                    WHERE t.login_id = $loginID;

                    ");

                if ($result->num_rows > 0) {
                    if ($row = $result->fetch_assoc()) {
                        $isRevealed = $row["bar_hint_{$i}_rev"];

                        if ($isRevealed == 1) {
                            echo "vihje paljastettu, pisteet: ";
                            $barScore -= 20;
                            echo $barScore;
                            echo "<br>";
                        }
                        else{
                            echo "pisteet: $barScore";
                            echo "<br>";
                        }
                    }
                }

            }
        }
    } else {
        echo "
            <script>$.notify('Väärä tiimi tunnus!', {
              style: 'message',
              className: 'error'
            });</script>
            ";
        return;
    }
    echo "<br>";
}
