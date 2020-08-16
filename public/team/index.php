<?php
$loginID = $_SESSION['loginID'];
?>

<p id='result'></p>

<?php
p_Statement_log("Team_log",3,"Opened the home view",$loginID);
// Checking if team has already set it's name, if not we ask for it
$result = RunSqlQuery("SELECT name FROM Team WHERE login_id = {$loginID}");

if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        $teamName = $row["name"];
    }
    //here we echo the flip card which holds the team name and the 
    echo "<div class='flip-card'>
  <div class='flip-card-inner'>
    <div class='flip-card-front'>
    <img class='id-rotate-image' src='images/ID-swap-icon.svg' alt='home'>
      <h2 id='update-name-result'>{$teamName}</h2>
    </div>
    <div class='flip-card-back'>
      <h1 style='font-size: 4.5rem;'>{$_SESSION['loginID']}</h1> 
    </div>
  </div>
</div>
<script>
    
    $('.flip-card .flip-card-inner').click(function() {
    $(this).closest('.flip-card').toggleClass('rotate');
    $(this).css('transform, rotateY(180deg)');
    });
</script>
";
}
// Here we ask for team name with prompt window and send jQuery post to server
if ($teamName == null) {
    echo "
        <script>
        let teamName=prompt('Plase enter your team name');

        if(teamName != null){
            $.post(window.location.pathname+'tools/updateTeamName.php', {
            name: teamName,
            loginID: $loginID
            }, function(data,status){
                $('#update-name-result').html(data);
            })
        }
        </script>
            ";
}

// running sql query that selects team's bar hints, names and if the player has revealed them.
// if bar.id is less than 1 it means that is either bonus bar or ending place
$result = RunSqlQuery("
SELECT Bar.hint, Bar.name, Score.revealed, Score.id, Score.score
FROM Bar
	INNER JOIN
    Team
    	ON Team.login_id = {$loginID}
        	INNER JOIN
    	Score_relation
        	ON Score_relation.team_id = (SELECT Team.id FROM Team WHERE Team.login_id = {$loginID})
            INNER JOIN
            Score
            ON Score.id = Score_relation.score_id
WHERE Bar.id = Score_relation.bar_id AND Bar.id > 1
            ");
            
        //putting all hint links in a nice container
        echo "<div class='container'>";
        $hintCount = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row['score'] > 0){
                continue;
            }
            $hintCount++;
            $text; $width;
           // checking if player has revealed the bar if so. the text is bar name.
           if($row['revealed'] == 0){
              $text = $row['hint'];
              $width = "8";
           }else{$text = $row['name']; $width = "12";}
           
           // here we are pushing the 
            echo"<div class='row hint-row'>
            <div class='col-{$width} hint-div'>
            <p class='hint-text'>{$text}</p> </div><div class='col hint-div hint-button-div'>";
            
            if($row['revealed'] == 0){
            include "reveal_button.php";
            }
        echo "</div></div>";
    }
}

// if we didin't create any hint's that mean team is ready to headup to final checkpoint here we print
// a small concratz message and tell them where it is located
if($hintCount == 0){
    $result = RunSqlQuery("SELECT name FROM Bar WHERE Bar.id = -1");
    $barName;
if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        $barName = $row["name"];
    }
}
    echo"<div class='row hint-row'>
            <div class='col-12 hint-div'>
            <h3>Kaikki baarit suoritettu! <br>Löysitkö yhtään bonus baaria? <br><br> Jatkopaikkana toimii:<br> <u>{$barName}</u></h3> </div><div class='col hint-div hint-button-div'>";
            
    echo "</div></div>";
}
echo"</div>";
?>