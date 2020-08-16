<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="CSS/bootstrap.min.css">
<link rel="stylesheet" href="CSS/style.css">

<script src="JS/jquery-3.4.1.min.js"></script>
<script src="JS/notify.js"></script>
<script src="JS/bootstrap.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<div id="notifications"></div>
<script>

$.notify.addStyle('message', {
  html: "<div><span data-notify-text/></div>",
  classes: {
    base: {
      "white-space": "nowrap",
      "background-color": "#56FF4F",
      "padding": "5px"
      
    },
    warning: {
      "background-color": "#FFE128"
    },
    error: {
      "color": "white",
      "background-color": "#FF342E"
    }
  }
});

</script>
<body class="text-center">

<?php

// Script start
$rustart = getrusage();

//index file is on top and it's serving everything necesary to client

//starting session so that we can check if user is allready logged in
session_set_cookie_params(
    $cookie_timeout,
    '/; samesite=Strict',
    $cookie_domain,
    true,
    $cookie_httponly
);
session_start();

// console logging tool use with: console_log($data)
require "tools/console.php";
require "tools/connectToDB.php";
require "tools/inputChecks.php";
require "tools/dataLoggin.php";

include "header.html";
// setting all pages that can be accessed
$publicPages = array (
    "login" => "public/login.php",
    "adminLogin" => "public/admin_login.php"
    );

$userPages = array(
    "" => "public/login.php",
    "home" => "public/team/index.php",
    "barhint" => "public/team/hint_view.php",
    "pointview" => "public/team/point_view.php",
    "info" => "public/team/info.php",
    "feedback" => "public/feedBack.php"
);

$rastiPages = array(
    "adminLogin" => "public/admin_login.php",
    "home" => "public/checkpoint_admin/index.php",
    "rastiTeamview" => "public/checkpoint_admin/team_view.php",
    "rastiSetScore" => "public/checkpoint_admin/set_score.php",
    "stats" => "public/checkpoint_admin/stats.php",
    "info" => "public/checkpoint_admin/info.php",
    "feedback" => "public/feedBack.php"
);

//getting included page from url
$pageToLoad = $_GET["page"];
//if there is teamid on $_SESSION then user has singend in using team account
if(isset($_SESSION['message'])){
    echo "
    <script>$.notify('{$_SESSION['message']}', {
      style: 'message',
      className: 'success'
    });</script>
    ";
    $_SESSION['message'] = NULL;
}
if(isset($publicPages["$pageToLoad"])){
    include $publicPages[$pageToLoad];
}
else if (isset($_SESSION['loginID'])) {
    
    //here we get the notification count and store that inside a session.
    $result = runSqlQuery("
            SELECT COUNT(id)
            FROM Notification
            	INNER JOIN Notification_relation
                	ON Notification_relation.team_id = (SELECT id FROM Team WHERE Team.login_id = {$_SESSION['loginID']})
            WHERE Notification.id = Notification_relation.notification_id AND Notification_relation.seen = 0;
            ");
    
        if($result->num_rows > 0) {
            if($row = $result->fetch_assoc()) {
            $_SESSION['notifications'] = $row['COUNT(id)'];
            }
        }
    
    if (isset($userPages["$pageToLoad"])) {
        include $userPages[$pageToLoad];
        include "public/team/team_footer.php";
    }
}
else if(isset($_SESSION["username"])) {
        if (isset($rastiPages["$pageToLoad"])) {
        include $rastiPages[$pageToLoad];
        include "public/checkpoint_admin/admin_footer.php";
    }
}
//else if client is trying to access pages without log in we redirect him to loggin page
else {
    include "public/login.php";
}
if(!isset($_COOKIE["iAcceptCookies"])){
    include "public/cookie.php";
}
include "footer.html";



// Code ...

// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
console_log( "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n");
console_log( "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n");


?>
</body>
<script>
    if(window.location.href == "https://htory.fi/tursajaiset/vihjeet/"){
    window.location.replace("https://htory.fi/tursajaiset/vihjeet/?page=home");
}
</script>
