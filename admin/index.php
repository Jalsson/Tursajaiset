<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="../bootstrap.min.css">
<link rel="stylesheet" href="../style.css">

<script src="../JS/jquery-3.4.1.min.js"></script>
<script src="../JS/notify.js"></script>
<script src="../JS/popper.min.js"></script>
<script src="../JS/bootstrap.min.js"></script>
<script src="../JS/custom-notify.js"></script>

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


<?php

//index file is on top and it's serving everything necesary to client

//starting session so that we can check if user is allready logged in
session_start();
// console logging tool use with: console_log($data)
require "../tools/console.php";
require "../tools/connectToDB.php";
require "../tools/inputChecks.php";

include "../header.html";
// setting all pages that can be accessed
$adminPages = array(
    "" => "/admin/content/login.php",
    "edituser" => "content/editUsers.php",
    "editteam" => "content/editTeams.php",
    "editregions" => "content/editRegions.php"
);

//getting included page from url
$pageToLoad = $_GET["page"];
//if there is teamid on $_SESSION then user has singend in using team account

if(isset($_SESSION['message'])){
    
    if(isset($_SESSION['messageType'])){
        $messageType = $_SESSION['messageType'];
    }
    else{
        $messageType = "success";
    }
    echo "
    <script>$.notify('{$_SESSION['message']}', {
      style: 'message',
      className: '{$messageType}'
    });</script>
    ";
    $_SESSION['message'] = NULL;
    $_SESSION['messageType'] = NULL;
}

if($_SESSION["admin"] == 1) {
        if (isset($adminPages["$pageToLoad"])) {
        include "content/navigation.php";
        include $adminPages[$pageToLoad];
    }
}
//else if client is trying to access pages without log in we redirect him to loggin page
else {
    include "content/login.php";
}

include "../footer.html";

?>

