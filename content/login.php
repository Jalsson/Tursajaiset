
<form action="" method="post">
    <input type="number" name="login_id" placeholder="Tiimi tunnus" required>
    <input type="submit" value="Kirjaudu">
</form>


<form action="" method="post">
    <input type="text" name="username" placeholder="Käyttäjä" required>
    <input type="password" name="password" placeholder="Salasana" required>
    <input type="submit" value="Kirjaudu">
</form>

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
            echo "
            <script>$.notify('Väärä tiimi tunnus!', {
              style: 'message',
              className: 'error'
            });</script>
            ";
        }
    }

    else if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {

        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = RunSqlQuery(
            "Select password, maintenance_account
                FROM Admin_users
                WHERE username = '{$username}'"            
            );

        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $hash = $row["password"];
                
                if (password_verify($password, $hash)) {
                    $_SESSION['username'] = $username;
                    $_Session['admin'] = $row["maintenance_account"];
                    header("LOCATION: ?page=rastiIndex");
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