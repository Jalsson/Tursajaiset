<div style="max-width: 330px;margin: auto; height: 50px;"> 
<button class="loginTab" id="notification-login-button" style="color: white; background: #00000087; border-radius: 10px 0px 0px 10px;">Ilmoitukset
<?php if(0 < $_SESSION['notifications']){echo"<img style='margin-top: 14px;margin-left: -126px;height: 20px;position: absolute;' src='images/notification-icon.png' alt='logout'>";} ?>
</button>
<button class="loginTab" id="info-login-button" style="color: white; background: #00000087; border-radius: 0px 10px 10px 0px;">Info</button>
</div>

<div id="notification-page">
<p>Tästä voitte lähettää ilmoituksia teidän rastille tuleville tai käyneille tiimeille. <br> Ilmoituksen näkevät vain ne tiimit joilla on teidän vihjeenne.</p>
<form action="" method="post" id="usrform">
 <label for="score_field" class="sr-only">Pisteet</label>
 
<style>
.blueText{ background-color:green; }

.yellowText{ background-color:blue; }

.redText{ background-color:red; }
</style>


<textarea class="form-control notification-input" style="color: black;"id="message-text" rows="5" cols="50" name="message" form="usrform" placeholder="Ilmoituksen sisältö"><?php echo $_SESSION['comment'] ?>

</textarea>
<p style="margin: 0px;" id="remaining">180</p>
<select onchange="this.className=this.options[this.selectedIndex].className" type="number" name="importance" id="importance-select" class="form-control form-control-sm non-important-notification" style="display: initial;width: auto;border-width: 0px;" value="<?php echo $_SESSION['score'] ?>" placeholder="Syötä pisteet" required>
     <option class="form-control form-control-sm non-important-notification" value="2" >Ei tärkeä</option>
    <option class="form-control form-control-sm important-notification"   value="1" >Tärkeä</option>
    <option class="form-control form-control-sm very-important-notification" value="0" >Hyvin tärkeä</option>
</select>
<select  type="number" name="whoToSend" class="form-control form-control-sm " style="display: initial;width: auto;border-width: 0px;" value="<?php echo $_SESSION['score'] ?>" placeholder="Syötä pisteet" required>
     <option value=">" >Käyneet</option>
    <option value="=" >Ei käyneet</option>
    <option value=">=" >Kummatkin</option>
</select>
 <button class="btn btn btn-primary " type="submit">Lisää</button>   
<script>
let Colors = ["#f03131d6","#ffd943d6","#43d3ffd6"]
$("#message-text").css("background",Colors[2])
    $('select').on('change', function() {
        $("#message-text").css("background",Colors[this.value])
    });
    
$('textarea').keypress(function(e) {
    var tval = $('textarea').val(),
        tlength = tval.length,
        set = 180,
        remain = parseInt(set - tlength);
    $('#remaining').text(remain+"");
    if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
        $('textarea').val((tval).substring(0, tlength - 1));
        return false;
    }
})
</script>

</form>


</div>

<div id="info-page">

     <?php

$result = RunSqlQuery("SELECT name FROM Bar WHERE Bar.id = -1");
    $barName;
if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
        $barName = $row["name"];
    }
}
    echo"<h3>Jatkopaikkana toimii:<br> <u>{$barName}</u></h3>";
            
?>
    <p>Rastit sulkeutuvat tähän kellon aikaan</p>
     <button class="btn btn btn-primary " id="toFeedback" type="submit"  >Anna palautetta</button> 
     
</div>

<script>
    $('#toFeedback').click(function() {
    window.location = '?page=feedback';
    });

var toggleColor = "#0aa405"
var normalColor = $(".loginTab").css("background-color");

$(document).ready(function(){
    changeToNotification();
    $("#info-page").hide();
    $("#notification-login-button").click(function(){
        $("#notification-page").show();
        $("#info-page").hide();
       changeToNotification();
        
    });
    $("#info-login-button").click(function(){
        $("#notification-page").hide();
        $("#info-page").show();
        changeToInfo();
    });
})

function changeToNotification(){
     $("#notification-login-button").css('box-shadow',"0px 6px transparent");
     $("#info-login-button").css('box-shadow',"0px 6px #064b00 ");
          $("#info-login-button").css('background-color',normalColor);
      $("#notification-login-button").css('background-color',toggleColor);
}
function changeToInfo(){
     $("#notification-login-button").css('box-shadow',"0px 6px #064b00");
     $("#info-login-button").css('box-shadow',"0px 6px transparent ");
               $("#info-login-button").css('background-color',toggleColor);
      $("#notification-login-button").css('background-color',normalColor);
}
</script>

<div id="dismissResults"></div>

<?php
if (! empty( $_POST ) ) {
    $userName = $_SESSION['username'];
    if(isset($_POST['importance']) && isset($_POST['message'])){
        $message = $_POST['message'];
        $importance = $_POST['importance'];
        
        if(!empty($message)){
                if(sanityCheck($message, 'string', 180) == false){
                echo "
                <script>$.notify('Kommentti on liian pitkä(180 merkkiä)', {
                style: 'message',
                className: 'error'
                });</script>
                ";
                $message = NULL;
                }
                else{
                $message = TrimString($message);
                }
            }
                $notificationID = p_Statement_Notification($message,$importance);
                
                require './tools/dbconfig.php';
                
                // Check connection
                if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                }
                $conn = new mysqli($servername, $username, $password, $dbname);
                $teamID;
                $stmt = $conn->prepare("INSERT INTO Notification_relation (notification_id, team_id) VALUES (?,?)");
                $stmt->bind_param("ii", $notificationID , $teamID);
                
                $result = RunSqlQuery("
                SELECT Team.id
                FROM Team 
                INNER JOIN Score_relation ON Score_relation.bar_id = (SELECT id FROM Bar WHERE Bar.admin = (SELECT id FROM Admin WHERE Admin.username = '{$_SESSION['username']}')) 
                INNER JOIN Score ON Score.id = Score_relation.score_id
                WHERE Team.id = Score_relation.team_id AND Score.score {$_POST['whoToSend']} 0");

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $teamID = $row['id'];
                        $stmt->execute();
                    }
                }
                
                $stmt->close();
                $conn->close();
                
                
                p_Statement_log("Admin_log",3,$message,$_SESSION['userId']);
                
                $_SESSION['message'] = "Pisteet lisätty onnistuneesti!";
            
            
        }
    }
?>

