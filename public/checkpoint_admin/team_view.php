<?php
// this file gets the given team with it's id
require "../../tools/connectToDB.php";

//assigning variable names
$loginID = $_POST['teamID'];
$userName = $_POST['userName'];
$adminId= $_POST['userId'];
// here we get everything needen from Team table
$result = RunSqlQuery("
    SELECT id, name, is_new_students
    FROM Team
    WHERE Team.login_id = {$loginID};
    ");
if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {

    //here those variables are declared, you can use these to echo what you need
        $teamName = $row['name'];
        $ID = $row['id'];
        $freshie = $row['is_new_students'];
    }
    echo "
    <h2 style='word-wrap: break-word;'>{$teamName} </h2>
    <div class='row mb-1'>
        <div class='col-7'>";
    
    if($freshie == 2){
        echo "<div class='info-container freshie-notification-info'>
        <h3 style='margin-top: 5px;font-size: 1.6rem;'>On fuksijoukkue</h3>";
    }else if($freshie == 1){
        echo "<div class='info-container freshie-notification-info' style='background: #138193;'>
        <h3 style='margin-top: 5px;font-size: 1.6rem;'>Ei fuksijoukkue</h3>";
    }else{
        //this is echoed if the freshie status has not been set(only freshies can win the competition)
        echo"<div class='info-container freshie-notification-warning'>
        <h5 style='color: black;'>Fuksi status ei määritetty!</h5>";
    }
        ?>
    </div>
        </div>
            <div class='col-5'>
        <div id='update-freshie' class="info-container freshie-notification-button"><h5>Päivitä fuksi status tästä.</h5></div>
        </div>
    </div>
    <script>
    document.getElementById('update-freshie').onclick = UpdateFreshie;
    
        function UpdateFreshie() {
            var confirmed = confirm('Koostuuko joukkue uusista opiskelijoista. Paina "OK" jos kyllä, "Cancel" jos ei');
            if(confirmed == true){
                Post(2);
            }else{
                Post(1);
            }
        }
    function Post(freshie){
        $.post(window.location.pathname+'tools/updateTeamName.php', {
            freshie: freshie,
            loginID: <?php echo"$loginID"; ?>,
            userName: '<?php echo"{$userName}"; ?>',
            userId: '<?php echo"{$adminId}";?>'
        }, function(data,status){
            $('#vihje-result').html(data);
            GetTeamView();
        })
    }
    </script><?php
}
//if we cannot find the team we return error and notify user with script
else{ echo "<script>$.notify('Väärä tiimi tunnus!', {
              style: 'message',
              className: 'error'
            });</script>
            "; 
             return;
}
$ownedBar = NULL;
$result = RunSqlQuery("
SELECT Score.id 
FROM Score
	INNER JOIN Bar ON Bar.admin = (SELECT id from Admin WHERE username = '{$userName}')
	INNER JOIN Score_relation ON Score_relation.bar_id = Bar.id AND Score_relation.team_id = (SELECT id FROM Team WHERE login_id = {$loginID})
WHERE Score.id = Score_relation.score_id;
    ");
    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            $ownedBar = $row['id'];
        }
    }

$result = RunSqlQuery("
        SELECT Score.id ,score, revealed, Bar.name, Bar.id AS bar_id
        FROM Score, Bar
            INNER JOIN Score_relation ON Score_relation.team_id = {$ID}
        WHERE Score.id = Score_relation.score_id AND Bar.id = Score_relation.bar_id
        ORDER BY Bar.id DESC
    ");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    $scoreID = $row['id'];
    $isRevealed = $row['revealed'];
    $teamScore = $row['score'];
    $barName = $row['name'];
    $barID = $row['bar_id'];
    // here are all the if statements for defining if the player has score allready.
    if($teamScore > 0){
        if($isRevealed == 1){
            $teamScore -= 20;
        }
        $scoreText = "<h5><span class='bigger-font'>{$teamScore}</span></h5><p style='margin: 0;margin-bottom: 5px;'> /1000</p> <img class='score-circle-image' src='images/circle-icon.png' alt='circle'>"; 
    }
    else {
         $scoreText = "<h5><span class='bigger-font'>0</span></h5><p style='margin: 0;margin-bottom: 5px;'> /1000</p> <img class='score-circle-image' src='images/circle-icon.png' alt='circle'>"; 
         $grey= "empty-color";
    }

    $grey;
    echo "
    <div class='info-container teamview-container {$grey}'>
        <div class='row'>
            <div class='col' style='margin-bottom: 10px;'>
                <h3> $barName </h3>
            </div>
        </div>
        <div class='row'>
            <div class='col'"; if($ownedBar != $scoreID && $barID != 1){echo "style='margin-left: 33%;'";}echo">
                {$scoreText}
            </div>
            <div class='col'>
            <script>var form{$scoreID} = document.getElementById('form{$scoreID}'); form{$scoreID}.action = 'https://htory.fi'+window.location.pathname+'?page=rastiSetScore'</script>
                <form id='form{$scoreID}' method='post' style='margin: auto;'>
                <input type='hidden' id='barName{$scoreID}' name='barID' value='{$scoreID}'>
                <input type='hidden' id='teamID{$scoreID}' name='teamID' value='{$loginID}'>";
                 if($ownedBar == $scoreID || $barID == 1){echo "<button type='submit' name='your_name' value='your_value' style='border-radius: 40px; border-color: #007bff; border-width: 2px' class='btn btn-light'><img class='nav-icon' style='margin: 0px; height: 40px;'  src='images/plus-icon.png' alt='Lisää tai päivitä pisteet'></button>";}
                echo "</form>
            </div>
        </div>";
    if($isRevealed == 1){
   echo"<div class='row'>
            <div class='col'>
                <h5 style='margin-top: 0.5rem;font-size: 1rem;'>Tiimi on paljastanut vihjeen(-20P)</h5>
            </div>
         </div>";
    }
   echo "</div>
    ";
    $grey = NULL;
    }
}
else{ echo "<script>$.notify('Alue on poistettu tietokannasta, ota yhteyttä HTO:n hallitukseen!', {
              style: 'message',
              className: 'error'
            });</script>
            "; 
             return;
}
?>

<p id="vihje-result"></p>