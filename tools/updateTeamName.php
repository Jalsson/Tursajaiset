<?php
require_once "connectToDB.php";
require_once "inputChecks.php";

$loginID = $_POST['loginID'];

/* We get user input for their name, here  we do variour sanity checks for input before we even consider
storing it inside our DB*/

if (isset($_POST['name'])) {
    if(sanityCheck($_POST['name'], 'string', 25) != false && !OnlyWhiteSpaces($_POST['name'])){
            // After initial checks we are sure that string is somewhat valid
    // Just to be sure here we trim it from white spaces and prevent any html and sql injections
    $nameToUpdate = TrimString($_POST['name']);
    p_Statement_log("Team_log",1,"successfully updated name: {$nameToUpdate}",$loginID);
    
    //after that we update our database with user input
    p_Statement_teamName($nameToUpdate,$loginID);
    echo "<h2>{$nameToUpdate}</h2>";
    }
    else {
    $name = TrimString($_POST['name']);
    p_Statement_log("Team_log",1,"failed to update name {$nameToUpdate}",$loginID);
    echo "
        <script>$.notify('liian pitkä(25 merkkiä) tai virheellinen nimi!', {
          style: 'message',
          className: 'error'
        });</script>
        ";
    }
} 

if (isset($_POST['freshie'])){
    //after that we update our database with user input
    p_Statement_log("Admin_log",2,"Setted freshie status to {$_POST['freshie']}, for team {$loginID}",$_POST['userId']);
    RunSqlQuery("UPDATE Team
        SET Team.is_new_students = {$_POST['freshie']}
        Where Team.login_id = {$loginID};");
        
    echo "
        <script>$.notify('Status päivitetty', {
          style: 'message',
          className: 'success'
        });</script>
        ";
}