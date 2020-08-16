<form action="" method="post">
        <input type="text" name="username" placeholder="Käyttäjä" required>
        <input type="password" name="password" placeholder="Salasana" required>
        <div class="flex-container">
             <div class="g-recaptcha" 
             data-sitekey="6LeMvuMUAAAAACBeMK_QohUFSdc7nih6n_dWCT2Q"
             data-callback="recaptcha_callback"></div>
        </div>
        <input  class="btn btn-primary" type="submit" id="login-btn" value="Kirjaudu" disabled>
        <input type="checkbox" name="remember-admin-login">Muista minut
</form>

<script>
    function recaptcha_callback(){
        let registerBtn = document.getElementById("login-btn")
        registerBtn.removeAttribute("disabled");
    }
    
</script>

<?php

if(isset($_COOKIE["superAdminName"])){
    $credentials = explode(",", $_COOKIE["superAdminName"]);
    $_POST["username"] = $credentials[0];
    $_POST["password"] = $credentials[1];
}



if (! empty( $_POST ) ) {

    if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {


    // if repatcha is somehow not posted we throw this error to user
    if(empty($_POST['g-recaptcha-response'])&& !isset($_COOKIE["superAdminName"])){
    echo "
    <script>$.notify('reCAPTCHA varmennus error !', {
      style: 'message',
      className: 'error'
    });</script>
    ";
    exit();
}
// if it is set we are going to make request to google server to verify it with our secret key
else if(!isset($_COOKIE["superAdminName"])){
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
        //after succesfull bot validation we start validating user data from database and check if data is matching
        $username = $_POST["username"];
        $password = $_POST["password"];

        if(isset($_POST["remember-admin-login"])){
            setcookie("superAdminName",  $_POST["username"] . "," . $_POST["password"], time() + (86400 * 30 * 365), '/; samesite=strict',$cookie_domain,true,false);
        }

        $result = RunSqlQuery(
            "Select password, maintenance
                FROM Admin
                WHERE username = '{$username}' AND maintenance = 1;"            
            );
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $hash = $row["password"];
                if (password_verify($password, $hash)) {
                                
                    if(isset($_POST["remember-admin-login"])){
                        setcookie("superAdminName",  $_POST["username"] . "," . $_POST["password"], time() + (86400 * 30 * 365));
                    }
                    
                    $_SESSION['username'] = $username;
                    $_SESSION['admin'] = $row["maintenance"];
                    header("LOCATION: ?page=edituser");
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
            echo "
            <script>$.notify('Väärä tunnus tai salasana!', {
              style: 'message',
              className: 'error'
            });</script>
            ";
        }
    
    }
}
?>