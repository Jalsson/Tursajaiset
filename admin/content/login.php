<form action="" method="post">
    <input type="text" name="username" placeholder="Käyttäjä" required>
    <input type="password" name="password" placeholder="Salasana" required>
    <input type="submit" value="Kirjaudu">
</form>

<?php
if (! empty( $_POST ) ) {

    if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {

        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = RunSqlQuery(
            "Select password, maintenance_account
                FROM Admin_users
                WHERE username = '{$username}';"            
            );
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $hash = $row["password"];
                if (password_verify($password, $hash)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['admin'] = $row["maintenance_account"];
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