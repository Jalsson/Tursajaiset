<div style="max-width: 330px;margin: auto; height: 50px;"> 
<button class="loginTab" id="notification-login-button" style="color: white; background: #00000087; border-radius: 10px 0px 0px 10px;">Ilmoitukset
<?php if(0 < $_SESSION['notifications']){echo"<img style='margin-top: 14px;margin-left: -126px;height: 20px;position: absolute;' src='images/notification-icon.png' alt='logout'>";} ?>
</button>
<button class="loginTab" id="info-login-button" style="color: white; background: #00000087; border-radius: 0px 10px 10px 0px;">Info</button>
</div>

<div id="notification-page">
    <div class="container">
    <?php  $loginID = $_SESSION['loginID'];
        $notificationColor = array("#f03131d6","#ffd943d6","#43d3ffd6");
    p_Statement_log("Team_log",3,"Opened the Info view",$loginID);
    $result = RunSqlQuery("
            SELECT Notification.id, Notification.message, Notification.importance
            FROM Notification
            	INNER JOIN Notification_relation
                	ON Notification_relation.team_id = (SELECT id FROM Team WHERE Team.login_id = {$loginID})
            WHERE Notification.id = Notification_relation.notification_id AND Notification_relation.seen = 0;
                ");
                
            //putting all hint links in a nice container
            echo "<div class='container'>";
            $hintCount = 0;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "
                <div class='row hint-row' id='notification-row-{$row['id']}' style='background: {$notificationColor[$row['importance']]}; border-radius: 15px 0px;'>
                    <div class='col-8 hint-div'>
                        <p style='color: black;'> {$row['message']}</p>
                    </div>
                    <div class='col hint-div hint-button-div'>
                        <img id='dim-notification-{$row['id']}' class='hint-reveal-button' value='1' src='images/trash-icon.png' style='height: 50px;'>
                    </div>
                </div>
                
                <script>
        document.getElementById('dim-notification-{$row['id']}').onclick = dimNotification;
    
        function dimNotification() {
                    $.post(window.location.pathname+'tools/dismiss-notification.php', {
                    notificationId: {$row['id']},
                    teamID: {$loginID}
                }, function(data,status){
                    $('#dismissResults').html(data);
                })
        }

    </script>
                
                ";
            }
        }
        else{
            echo "ei uusia ilmoituksia";
        }
        
    ?>
    </div>

</div>
</div>

<div id="info-page">
<div class="row hint-row" style="max-width: 150px; margin:20px auto;">
        <div class="col hint-div">
            <h3> Info</h3>
        </div>
    </div>
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