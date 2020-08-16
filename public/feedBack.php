<p>Kiitos että olet päättänyt osallistua tursajaisiin.<br> Me HTO:lla rakastamme kaikkea palautetta ja otamme sen mielellämme vastaan</p>
<form action="" method="post" id="usrform">

<textarea class="form-control" maxlength="400" id="feedback" rows="5" cols="50" name="feedback" form="usrform" placeholder="Kaikkea palautetta käsitellään EU tietosuoja direktiivin mukaan."><?php echo $_SESSION['comment'] ?></textarea>
<p style="margin: 0px;" id="remaining">400</p>
<br>
    <p>Jos haluat että sinuun ollaan yhteydessä palautteesi suhteen. Lisää yhteystietosi palautteeseen. </p>
 <button class="btn btn btn-primary " type="submit" >Lähetä</button>   
</form>
<script>

$('textarea').keypress(function(e) {
    var tval = $('textarea').val(),
        tlength = tval.length,
        set = 400,
        remain = parseInt(set - tlength);
    $('#remaining').text(remain+"");
    if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
        $('textarea').val((tval).substring(0, tlength - 1));
        return false;
    }
})
</script>

<?php

if (! empty( $_POST ) ) {
    $userName;
    if(isset($_SESSION['loginID'])){
        $userName = $_SESSION['loginID'];
        p_Statement_log("Team_log",5, $_POST['feedback'], $_SESSION['loginID']);
    }
    if(isset($_SESSION['username'])){
        $userName = $_SESSION['username'];
       p_Statement_log("Admin_log",4, $_POST['feedback'], $_SESSION['userId']);
    }
    if(isset($_POST['feedback'])){
        $feedback = $_POST['feedback'];
        
        if(!empty($feedback)){
                if(sanityCheck($feedback, 'string', 400) == false){
                echo "
                <script>$.notify('Teksti on liian pitkä(400 merkkiä)', {
                style: 'message',
                className: 'error'
                });</script>
                ";
                $feedback = NULL;
                }
                else{
                $feedback = TrimString($feedback);
                }
            }
                p_Statement_Feedback($feedback,$userName);
                header("LOCATION: ?page=home");
                $_SESSION['message'] = "Kiitos palautteesta!";
        }
    }
?>





