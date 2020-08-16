<div style="max-width: 330px;margin: auto; height: 50px;"> 
    <button class="loginTab" id="team-login-button">Kilpailija</button>
    <button class="loginTab" id="admin-login-button">Rastinpitäjä</button>
</div>

<form class="form-signin" action="" method="post" id="team-login">
    <h1 class="h3 mb-3 font-weight-normal">Kirjaudu sisään</h1>
    <label for="login_id" class="sr-only">Tiimi tunnus</label>
    <input type="number" name="login_id" id="login_id" class="form-control" placeholder="Tiimi tunnus" required autofocus>
    <div class="g-recaptcha" 
        data-sitekey="6LeMvuMUAAAAACBeMK_QohUFSdc7nih6n_dWCT2Q"
        data-callback="recaptcha_callback_team">
    </div>
    
    <div class='custom-control custom-checkbox' style="color: white;">
        <input type='checkbox' class='custom-control-input' id='super-admin-checkbox' name="remember-team-login" value="Yes">
        <label class='custom-control-label' for='super-admin-checkbox' style='margin-top: 3px;'></label>Muista minut
    </div>
    <button id="team-login-btn" class="btn btn-lg btn btn-success btn-block" type="submit" disabled>Kirjaudu</button>
</form>

<script>
var toggleColor = "#67cc4c"
var normalColor = $(".loginTab").css("background-color");
console.log("https://"+window.location.hostname+window.location.pathname+"?page=adminLogin")
$(document).ready(function(){
    changeToTeam();
    $("#team-login-button").click(function(){
        window.location.href = "https://"+window.location.hostname+window.location.pathname
       changeToTeam();
        
    });
    $("#admin-login-button").click(function(){
        window.location.href = "https://"+window.location.hostname+window.location.pathname+"?page=adminLogin"
        changeToAdmin();
    });
})

    function recaptcha_callback_team(){
        let registerBtn = document.getElementById("team-login-btn")
        registerBtn.removeAttribute("disabled");
    }


function changeToTeam(){
     $("#team-login-button").css('background-color',toggleColor);
     $("#team-login-button").css('border-bottom-style',"solid");
      $("#admin-login-button").css('background-color',normalColor);
      $("#admin-login-button").css('border-bottom-style',"none");
}
function changeToAdmin(){
     $("#team-login-button").css('background-color',normalColor);
     $("#team-login-button").css('border-bottom-style',"none");
      $("#admin-login-button").css('background-color',toggleColor);
      $("#admin-login-button").css('border-bottom-style',"solid");
}
</script>

<?php
/* handles user loggin if login id form is filled post contains "login_id". In this case we ask from team table
if there is such user we start SESSION and let user forward to view his status, this SESSION key is required to
view any other pages*/
if(isset($_COOKIE["teamID"])){
    $_POST["login_id"] = $_COOKIE["teamID"];
}

if(isset($_COOKIE["adminName"])){
    header("LOCATION: ?page=adminLogin");
}

if (! empty( $_POST ) ) {
    if(isset($_POST['login_id'])){
        if(!isset($_COOKIE["teamID"])){
            checkCaptcha();
        }
        $login_id = $_POST["login_id"];
        
        $result = RunSqlQuery("SELECT login_id FROM Team WHERE login_id = {$login_id}");
    
    
    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            
            //setting cookie if user has checked the remember me
            if(isset($_POST["remember-team-login"])){
                setcookie("teamID", $login_id, time() + (86400 * 30), '/; samesite=strict',$cookie_domain,true,false);
            }
            $loginID = $row["login_id"];
            
            //making a prepared statement and inserting data to log
            p_Statement_log("Team_log",0,"successful login",$login_id);
            
            session_destroy();
            session_start();
            //here we are attaching the team id to client session 
            //so we can access the database information later
            $_SESSION['loginID'] = $loginID;
            //loggin user in to main page
            header("LOCATION: ?page=home");
        }
    }
    
    else {
        p_Statement_log("Team_log",0,"failed login",$login_id);
        echo "
        <script>$.notify('Väärä tiimi tunnus!', {
          style: 'message',
          className: 'error'
        });</script>
        ";
    }
    }
}

function checkCaptcha(){
        // if repatcha is somehow not posted we throw this error to user
    if(empty($_POST['g-recaptcha-response'])){
    echo "
    <script>$.notify('reCAPTCHA varmennus error !', {
      style: 'message',
      className: 'error'
    });</script>
    ";
    exit();
}
// if it is set we are going to make request to google server to verify it with our secret key
    else{
        $secretKey = '6LeMvuMUAAAAAJ-FGyUi1MXlrv7wmGzRKXFXeudU';
        
        $post_data = http_build_query(
            array(
                'secret' => '6LeMvuMUAAAAAJ-FGyUi1MXlrv7wmGzRKXFXeudU',
                'response' => $_POST['g-recaptcha-response'],
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );
        $context  = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);
        if (!$result->success) {
        echo "
        <script>$.notify('reCaptcha error after verification', {
          style: 'message',
          className: 'error'
        });</script>
        ";
        exit();
        }
    }
}

?>