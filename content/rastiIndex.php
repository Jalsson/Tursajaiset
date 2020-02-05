 <input type="number" id="login-id"placeholder="Syötä tiimi tunnus">
 <button id="search-team">Hae</button>

<script>

 document.getElementById("search-team").onclick = GetTeamView;

function GetTeamView(){
    
    $.post('/kisapanel/content/rastiTeamview.php', {
    teamID: $('#login-id').val(),
}, function(data,status){
    $('#result').html(data);
})
}
</script>

<?php

if (! empty( $_POST ) ) {

    if(isset($_POST['login_id'])){

        $login_id = $_POST["login_id"];
        
        $result = SearchWithID("Team", $login_id, "login_id, team_name");
        
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                
                $loginID = $row["login_id"];

                //here we are attaching the team id to client session 
                //so we can access the database information later
                $_SESSION['loginID'] = $loginID;
                
                //loggin user in to main page
                header("LOCATION: ?page=teamview");
            }
        }
        else {
            echo "wrong tunnus";
        }
    }
}
?>

<p id="result"></p>