<?php
if(isset($_POST['barID']) && isset($_POST['teamID'])){
    $_SESSION['scoreID'] = $_POST["barID"];
    $_SESSION['teamID'] = $_POST["teamID"];   
    
    $result = RunSqlQuery("
    SELECT comment, score
    FROM Score
        INNER JOIN Score_relation ON Score_relation.score_id = {$_SESSION['scoreID']}
        INNER JOIN Bar ON Bar.id = Score_relation.bar_id
    WHERE Score.id = {$_SESSION['scoreID']} AND Bar.admin = (SELECT id FROM Admin WHERE username = '{$_SESSION['username']}')
    ");
    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            $_SESSION['comment'] = $row['comment'];
            $_SESSION['score'] = $row['score'];
        }
    }
}
?>

<form action="" method="post" id="usrform">
 <label for="score_field" class="sr-only">Pisteet</label>
<input type="number" name="score" id="score_field" class="form-control" style="display: initial;width: auto;" value="<?php echo $_SESSION['score'] ?>" placeholder="Syötä pisteet" required autofocus>

<textarea class="form-control" maxlength="300" id="feedback" rows="5" cols="50" name="comment" form="usrform" placeholder="Vapaa palaute esim. joukkueen yleinen fiilis"><?php echo $_SESSION['comment'] ?></textarea>
<br>


<div style="height: 50px;">
 <button class="btn btn btn-primary " type="submit" style="float: right; margin-right: 10px;" >Lähetä</button>   
    <a href=<?php echo "?page=home&recentID={$_SESSION['teamID']}"; ?> class="btn btn-primary warning-color" role="button" style="margin-left: 10px; float: left;">Takaisin</a>
</div>

</form>

<?php
if (! empty( $_POST ) ) {
    $userName = $_SESSION['username'];
    if(isset($_POST['comment']) && isset($_POST['score']) && isset($_SESSION['scoreID']) && isset($_SESSION['teamID'])){
        $comment = $_POST['comment'];
        $_SESSION['comment'] = $comment;
        $score = $_POST['score'];
        
        if(!empty($comment)){
                if(sanityCheck($comment, 'string', 300) == false){
                echo "
                <script>$.notify('Kommentti on liian pitkä(300 merkkiä)', {
                style: 'message',
                className: 'error'
                });</script>
                ";
                $comment = NULL;
                }
                else{
                $comment = TrimString($comment);
                }
            }
            if($score <= 1000 && $score > 0){
                p_Statement_log("Admin_log",1,"{$score};{$comment};{$_SESSION['teamID']}",$_SESSION['userId']);
                p_Statement_log("Team_log",4,"{$score};{$comment};{$_SESSION['username']}",$_SESSION['teamID']);
                p_Statement_Score($score,$comment,$userName,$_SESSION['scoreID']);
                header("LOCATION: ?page=home&recentID={$_SESSION['teamID']}");
                $_SESSION['scoreID'] = NULL;
                $_SESSION['teamID'] = NULL;
                $_SESSION['comment'] = NULL;
                $_SESSION['message'] = "Pisteet lisätty onnistuneesti!";
            }
            else{
            echo "
            <script>$.notify('pisteet väliltä 1-1000!', {
              style: 'message',
              className: 'error'
            });</script>
            ";
            }
        }
    }
?>





