<p id="result"></p>
<?php
$loginID = $_SESSION['loginID'];
?>

<?php

// Checking if team has already set it's name, if not we ask for it
$result = SearchWithID("Team", $loginID, "team_name");

if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        $teamName = $row["team_name"];
    }
    echo "<h1> $teamName </h1>";
}
// Here we ask for team name with prompt window and send jQuery post to server
if ($teamName == null) {
    echo "
        <script>
        var teamName=prompt('Plase enter your team name');

        $.post('/kisapanel/tools/updateTeamName.php', {
            name: teamName,
            loginID: $loginID
        }, function(data,status){
            $('#result').html(data);
        })
        </script>
            ";
            //here we also insert a empty score board with their id to DB for later use
    RunSqlQuery("
INSERT INTO Bar_scores
        VALUES((SELECT id FROM Team WHERE Team.login_id = {$loginID})
        ,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    ");
}

?>

<!-- links to all hints and link to view teams score -->
<a href="?page=barhint&hint=0">
Vihje 1..</a>
<br>
<a href="?page=barhint&hint=1">
Vihje 2..</a>
<br>
<a href="?page=barhint&hint=2">
Vihje 3..</a>
<br>
<a href="?page=barhint&hint=3">
Vihje 4..</a>
<br>
<a href="?page=pointview">
Pisteet</a>

