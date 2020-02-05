<?php
require_once "connectToDB.php";
require_once "inputChecks.php";

$loginID = $_POST['loginID'];

/* We get user input for their name, here  we do variour sanity checks for input before we even consider
storing it inside our DB*/
if (isset($_POST['name']) && sanityCheck($_POST['name'], 'string', 15) != false && !OnlyWhiteSpaces($_POST['name'])) {

    // After initial checks we are sure that string is somewhat valid
    // Just to be sure here we trim it from white spaces and prevent any html injections
    $nameToUpdate = TrimString($_POST['name']);

    //after that we update our database with user input
    RunSqlQuery("UPDATE Team
        SET Team.team_name = '$nameToUpdate'
        Where Team.login_id = '$loginID';");
    echo $nameToUpdate;
} else {
    echo "could not update your team name, check the lenght(15 character) and its content(no whitespace only allowed)";
}
