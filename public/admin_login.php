<div style="max-width: 330px;margin: auto; height: 50px;"> 
<button class="loginTab" id="team-login-button">Kilpailija</button>
<button class="loginTab" id="admin-login-button">Rastinpitäjä</button>
</div>

<form class="form-signin" action="" method="post" id="admin-login">
    <h1 class="h3 mb-3 font-weight-normal">Kirjaudu sisään</h1>
        <label for="username" class="sr-only">Käyttäjätunnus</label>
    <input type="text" name="username" id="username" class="form-control" placeholder="Käyttäjätunnus" required>
        <label for="password" class="sr-only">Salasana</label>
    <input type="password" name="password" id="password" class="form-control" placeholder="Salasana" required>
    
    <div  class="g-recaptcha" 
        data-sitekey="6LeMvuMUAAAAACBeMK_QohUFSdc7nih6n_dWCT2Q"
        data-callback="recaptcha_callback_admin"></div>
    <div class='custom-control custom-checkbox' style="color: white;">
        <input type='checkbox' class='custom-control-input' id='super-admin-checkbox' name="remember-admin-login" value="Yes">
        <label class='custom-control-label' for='super-admin-checkbox' style='margin-top: 3px;'></label>Muista minut
    </div>
    <button id="admin-login-btn" class="btn btn-lg btn-success btn-block" type="submit" disabled>Kirjaudu</button>
</form>


<script>
var toggleColor = "#67cc4c"
var normalColor = $(".loginTab").css("background-color");

$(document).ready(function(){
    changeToAdmin();
    $("#admin-login").show();
    $("#team-login-button").click(function(){
        window.location.href = "https://"+window.location.hostname+window.location.pathname
       changeToTeam();
        
    });
    $("#admin-login-button").click(function(){
        window.location.href = "https://"+window.location.hostname+window.location.pathname+"?page=adminLogin"
        changeToAdmin();
    });
})
        function recaptcha_callback_admin(){
        let registerBtn = document.getElementById("admin-login-btn")
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

if(isset($_COOKIE["adminName"])){
    $credentials = explode(",", $_COOKIE["adminName"]);
        $_POST["username"] = $credentials[0];
        $_POST["password"] = $credentials[1];
}

if (! empty( $_POST ) ) {

/*if username and password are filled, we check for those inside a database. password is hashed with php recommend
default hash and if we found the match for username and password give username variable to SESSION */
    if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
        if(!isset($_COOKIE["adminName"])){
            checkCaptcha();
        }
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $result = RunSqlQuery(
            "Select password, maintenance, id
                FROM Admin
                WHERE username = '{$username}'"            
            );

        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $hash = $row["password"];
                //here is if check for the password match
                if (password_verify($password, $hash)) {
                    
                    if(isset($_POST["remember-admin-login"])){
                        
                        setcookie("adminName",  $_POST["username"] . "," . $_POST["password"], time() + (86400 * 30), '/; samesite=strict');
                    }
                    session_destroy();
                    session_start();
                    p_Statement_log("Admin_log",0,"logged in",$row['id']);
                    $_SESSION['username'] = $username;
                    $_SESSION['userId'] = $row['id'];
                    $_SESSION['admin'] = $row["maintenance_account"];
                    header("LOCATION: ?page=home");
                } else {
                    echo "
                    <script>$.notify('Väärä tunnus tai salasana!', {
                      style: 'message',
                      className: 'error'
                    });</script>
                    ";
                }
            }
         }else {
            p_Statement_log("Admin_log",0,"failed to login as: {$username}",-1);
            echo "
            <script>$.notify('Väärä tunnus tai salasana!', {
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