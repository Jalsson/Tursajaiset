<?php
if(isset($_POST['barID']) && isset($_POST['teamID'])){
    $_SESSION['barID'] = $_POST["barID"];
    $_SESSION['teamID'] = $_POST["teamID"];   
}
?>

<?php
if (! empty( $_POST ) ) {
    console_log($_SESSION['barID']);
    if(isset($_POST['comment']) && isset($_POST['score']) && isset($_SESSION['barID']) && isset($_SESSION['teamID'])){
        $comment = $_POST['comment'];
        $score = $_POST['score'];
        if(!empty($comment)){
                if(sanityCheck($comment, 'string', 300) == false){
                echo "your comment is either too long or it's not valid :/";
                $comment = NULL;
                }
                else{
                $comment = TrimString($comment);
                }
            }
            
            if($score <= 100 && $score > 0){
                        RunSqlQuery("
                        UPDATE Bar_scores
                        SET Bar_scores.bar_{$_SESSION['barID']}_score = {$_POST['score']}, Bar_scores.bar_{$_SESSION['barID']}_comment = '{$comment}'
                        WHERE Bar_scores.id = (SELECT Team.bar_scores_id FROM Team WHERE login_id = {$_SESSION['teamID']});
                ");
                header("LOCATION: ?page=rastiIndex");
                $_SESSION['barID'] = NULL;
                $_SESSION['teamID'] = NULL;
                $_SESSION['message'] = "tallennettu onnistuneesti!";
            }
            else{
            echo "
            <script>$.notify('pisteet väliltä 0-100!', {
              style: 'message',
              className: 'error'
            });</script>
            ";
            }
        }
    }
?>


<form action="" method="post" id="usrform">
  Pisteet: <input type="number" name="score">
  <input type="submit">
</form>
vapaa sana:
<br>
<textarea rows="4" cols="50" name="comment" form="usrform"></textarea>

