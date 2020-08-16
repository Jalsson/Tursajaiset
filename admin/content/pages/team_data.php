<?php

class Team{
    public $ID;
    public $loginID;
    public $teamName = "defaultName";
    public $freshie;
    public $region;
    public $score;
    
    function teamName(){
        echo $this->teamName;
    }
}

$TeamsArray;

    $result = runSqlQuery("
        SELECT id, login_id, name, is_new_students, region
        FROM Team
        ");

// getting all teams from Team table
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // saving these values to object MyTeam
        $myTeam = new Team;
        $myTeam->ID = $row['id']; $myTeam->loginID = $row['login_id']; $myTeam->teamName = $row['name'];
        $myTeam->freshie = $row['is_new_students'];
        
        $regionID = $row['region'];
        $barScore = 0;
        $regionName;

        

        // getting all scores from that theam with the ID
        $scoreResult = RunSqlQuery("
        SELECT revealed, score
        FROM Score
        	INNER JOIN
            Score_relation ON Score_relation.team_id = {$row['id']}
        WHERE Score_relation.score_id = Score.id
        ");
        
        if ($scoreResult->num_rows > 0) {
            while($row = $scoreResult->fetch_assoc()) {
                
                // calculating and adding score to total. If the score is above 100 we will ignore that and just save 100
                $isRevealed = $row['revealed'];
                if($row['score'] > 100){
                    $row['score'] = 100;
                }
                if($isRevealed == 1)
                {
                     $row['score'] -= 20;
                }
                $barScore += $row['score'];
            }
        }
        
        // getting region name for with region ID
         $regionResult = RunSqlQuery("
            SELECT name
            FROM Region
            WHERE id = {$regionID};
            ");
            
            if ($regionResult->num_rows > 0) {
                if ($row = $regionResult->fetch_assoc()) {
                    $regionName = $row['name'];
            }
            }
            $myTeam->region = $regionName;
            $myTeam->score = $barScore;
            

        
            $TeamsArray[] = $myTeam;
    
    }
}
?>
<br>
<p>Selaa tämän hetkisiä tiimejä ja heidän pisteitään. Voit asettaa tiimit järjestykseen pisteiden, alueen ja fuksi statuksen perusteella.<br>
Voit näyttää tiimin yksittäiset tiedot painamalla haluamasi tiimin ID:tä. Voit poistaa sarakkeen painamalla ylintä <u>id</u> saraketta</u></p>

<div class='row'>
    <div class='col-2'><input id='ID' type='text' value='id' style='width: 100%;' readonly></input> </div>
    <div class='col width-col'><input id='loginID' type='text' value='Tunnus' style='width: 100%;' readonly></input> </div>
    <div class='col'><input id='teamName' type='text' value='Nimi' style='width: 100%;' readonly></input> </div>
    <div class='col'><input id='freshie' type='text' value='fuksi tiimi' style='width: 100%;' readonly></input> </div>
    <div class='col width-col'><input id='region' type='text' value='alueen nimi' style='width: 100%;' readonly></input> </div>
    <div class='col'><input id='score' type='text' value='pisteet' style='width: 100%;' readonly></input> </div>
    <div class='w-100'></div>
    <div id="team-list" class="row">
    </div>
</div>
<style>
.width-col{
    
}
    @media only screen and (max-width: 400px) {
  /* For mobile phones: */
    [class*="width-col"] {
    display: none;
    }
    }
.no-border{
    border-radius: 0px;
}
.small-padding{
    padding: 1px 0px;
}
</style>
<script>
// if ID button is clicked we call fill team which take the team data and rebuilds all the charts
$('#ID').click(function(){
    Fill(Team);
})

//here is all the data that was wetched from database
let Team = JSON.parse('<?php echo json_encode($TeamsArray);?>');


Fill(Team);
function Fill(Team){
     $("#team-list").empty();
    
    for (i = 0; i < Team.length; i++) {
        
        Team[i].freshie = parseInt(Team[i].freshie,10);
        let fuksiStatus;
            switch(Team[i].freshie){
            case 0:
                fuksiStatus = "Ei määritetty";
            break;
            case 1:
                fuksiStatus = "Ei fuksi";
            break;
            case 2:
                fuksiStatus = "On fuksi";
            break;
            default:
                fuksiStatus = "error fuksi statuksessa";
        }
         $("#team-list").append(" <div class='col-2 small-padding'><input class='form-control white-color no-border' id='ID"+i+"' type='text' value='"+ Team[i].ID+"' readonly></input> </div>");
          $("#team-list").append(" <div class='col width-col small-padding'><input class='form-control white-color no-border' id='loginID"+i+"' type='text' value='"+ Team[i].loginID+"' readonly></input> </div>");
          $("#team-list").append(" <div class='col small-padding'><input class='form-control white-color no-border' id='teamName"+i+"' type='text' value='"+ Team[i].teamName+"' readonly></input> </div>");
          $("#team-list").append(" <div class='col small-padding'><input class='form-control white-color no-border' id='freshie"+i+"' type='text' value='"+ fuksiStatus+"' readonly></input> </div>");
          $("#team-list").append(" <div class='col width-col small-padding'><input class='form-control white-color no-border' id='region"+i+"' type='text' value='"+ Team[i].region+"' readonly></input> </div>");
          $("#team-list").append(" <div class='col small-padding'><input class='form-control white-color no-border' id='score"+i+"' type='text' value='"+ Team[i].score+"' readonly></input> </div>");
           $("#team-list").append("<div class='w-100'></div>");
           $("#team-list").append("<div id='"+Team[i].ID+"-info'> </div>");
           $("#team-list").append("<div class='w-100'></div>");
           
           
        $("#ID"+i+"").on('click', function (e) {
        clearComments();
         let id = $(this).val()
            $.post(window.location.pathname+'content/ajax/load_teamComments.php', {
                ID: id
            }, function(data,status){
                $("#"+id+"-info").html(data);
            })
        }); 
    }
        
}

function clearComments(){
    for (i = 0; i < Team.length; i++) {
        $("#"+Team[i].ID+"-info").empty();
    }
}

var byScore = false;
var byFreshie = false;
var byName = false;
var byRegion = false;

// Here are all the sorting methods

$("#teamName").click(function() {
    
    if(!byName){
        Team.sort((a, b) => a.teamName.localeCompare(b.teamName))
        disable("byName");
    }
    
    Team.reverse();
    Fill(Team);
});

$("#score").click(function() {
    
    if(!byScore){
        Team.sort(function(a, b){
            return a.score-b.score
        })
         disable("byScore");
    }
    
    Team.reverse();
    Fill(Team);
});

$("#freshie").click(function() {
    
    if(!byFreshie){
        Team.sort(function(a, b){
            return a.freshie-b.freshie
        })
    disable("byFreshie");
    }
    
    Team.reverse();
    Fill(Team);
});

$("#region").click(function() {
    
    if(!byRegion){
        Team.sort((a, b) => a.region.localeCompare(b.region))
        disable("byRegion");
    }
    
    Team.reverse();
    Fill(Team);
});

// disables all sortings 
function disable(execpt){
byScore = false;
byFreshie = false;
byName = false;
byRegion = false;
    switch(execpt){
        case "byScore":
            byScore = true;
        break;
        case "byFreshie":
            byFreshie = true;
        break;
        case "byName":
        byName = true;
        break;
        case "byRegion":
        byRegion = true;
        break;
        }
}



</script>

