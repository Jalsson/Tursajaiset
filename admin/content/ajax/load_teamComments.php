<?php
require "../../../tools/connectToDB.php";

    $ID = $_POST['ID'];
    
            echo"<div class='row' style='border: solid;border-width: 1px;'>
            <div class='col-2 width-col'><input class='form-control subtitle black-text' type='text' value='Baari' readonly></input> </div>
            <div class='col-2'><input class='form-control subtitle black-text' type='text' value='Pisteet' readonly></input> </div>
            <div class='col'><input class='form-control subtitle black-text' type='text' value='Kommentti' readonly></input> </div>
            <div class='col-2 width-col'><input class='form-control subtitle black-text' type='text' value='Kirjoittaja' readonly></input> </div>
            <div class='col-2'><input class='form-control subtitle black-text' type='text' value='Paljastettu' readonly></input> </div>
            <div class='w-100' style='border: solid;border-width: 1px;color: #036f00;'></div>";
          
          
          
//  Here we load all comments from selected team and display them in grid view
        $result = RunSqlQuery("
        SELECT Bar.name, Score.score, Bar.hint, Score.comment, Admin.username,Score.revealed
        FROM Score 
        	INNER JOIN Score_relation ON Score_relation.team_id = {$ID}
            INNER JOIN Bar ON Bar.id = Score_relation.bar_id
            INNER JOIN Admin ON Admin.id = Score.comment_writer
        WHERE Score.id = Score_relation.score_id;
        ");
        
if ($result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {

        $barName = $row['name'];
        $isRevealed = $row['revealed'];
        $barScore = $row['score'];
        $barComment = $row['comment'];
        $commentWriter = $row['username'];

        
        $barRevealStatus;
            switch($isRevealed){
            case 0:
                $barRevealStatus = "Ei";
            break;
            case 1:
                $barRevealStatus = "kyllä";
            break;
            default:
                $barRevealStatus = "ERROR 1003";
        }
        
        echo"<div class='col-2 width-col'><input class='form-control subcontext black-text' type='text' value='$barName' readonly></input> </div>
            <div class='col-2'><input class='form-control subcontext black-text' type='text' value='$barScore' readonly></input> </div>
            <div class='col'><input class='form-control subcontext black-text' type='text' value='$barComment' readonly></input> </div>
            <div class='col-2 width-col'><input class='form-control subcontext black-text' type='text' value='$commentWriter' readonly></input> </div>
            <div class='col-2'><input class='form-control subcontext black-text' type='text' value='$barRevealStatus' readonly></input> </div>
            <div class='w-100' style='border: solid;border-width: 1px;color: #036f00;'></div>";
        
    }
}
    echo"</div>";