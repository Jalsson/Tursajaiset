<h1>Muokkaa käyttäjiä</h1>
<br>
<h3>Lisää uusi Tiimi</h3>

<form action="" method="post">
<p>Tiimi tunnus:</p>
<input type="number" name="loginID"  required>
<p>Tiimi nimi:</p>
<input type="text" name="teamName"required>
<br /><br />
Max baarit: <input type="number" name="scoreCount"  required> Alue: <input type="text" name="regionName"  required>
<br /><br />
<input type="submit" value="Luo tunnus">
</form>
<?php

if (!empty($_POST)) {

    if (isset($_POST['teamName']) && isset($_POST['loginID']) && isset($_POST['scoreCount']) && isset($_POST['regionName'])) {
        
        $loginID = $_POST["loginID"];
        $teamName = $_POST['teamName'];
        $scoreCount = $_POST['scoreCount'];
        $regionName = $_POST['regionName'];
        $regionID;

        $result = RunSqlQuery("
        SELECT id
        FROM Team
        WHERE login_id = $loginID OR team_name = '{$teamName}';
        ");

        if ($result->num_rows > 0) {
            notification("Tiimi tunnus tai nimi on jo olemassa", "error");
            return;
        }
        
        $result = RunSqlQuery("
        SELECT id
        FROM Region
        WHERE region_name = '{$regionName}';
        ");

        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $regionID = $row['id'];
            }
        }
        else{
            notification("Aluetta ei ole olemassa", "error");
            return;
        }
        
        $bar_scoresArray;
        for ($i = 0; $i < $scoreCount; $i++) {
            $bar_scoresArray[] = insertSql("
                INSERT INTO Bar_score (bar_score, bar_comment,bar_hint_rev)
                VALUES(0,NULL,0);
                ");
        }
        $bar_scoresArray = json_encode($bar_scoresArray);
        InsertSql("
                INSERT INTO Team (login_id, team_name, is_octopus, region_id, bar_scores_ids)
                VALUES ({$loginID}, '{$teamName}', 0, {$regionID}, '{$bar_scoresArray}');
                ");

        notification("Tiimi lisätty", "success");
    }
}

?>

