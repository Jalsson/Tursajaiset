<h1>Muokkaa käyttäjiä</h1>
<br>
<h3>Lisää uusi käyttäjä</h3>

<form action="" method="post">
<p>Käyttäjä nimi:</p>
<input type="text" name="username" placeholder="Käyttäjä nimi" required>
<p>Salasana:</p>
<input name="password" required="required" type="password" id="password" />
<p>Salasanan uudestaan:</p>
<input name="password_confirm" required="required" type="password" id="password_confirm" oninput="check(this)" />
<script language='javascript' type='text/javascript'>
    function check(input) {
        if (input.value != document.getElementById('password').value) {
            input.setCustomValidity('Password Must be Matching.');
        } else {
            // input is valid -- reset the error message
            input.setCustomValidity('');
        }
    }
</script>
<br>
<input type="checkbox" name="adminUser" value="Yes"> admin user<br>
<br /><br />
<input type="submit" value="Luo tunnus">
</form>
<?php


if (! empty( $_POST ) ) {

    if(isset($_POST['username']) && isset($_POST['password'])){

        $username = $_POST["username"];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $adminUser;
        if( $_POST['adminUser'] == 'Yes'){
            $adminUser = 1;
        }
        else{
            $adminUser = 0;
        }
        
        RunSqlQuery("
        INSERT INTO Admin_users (username, password, maintenance_account)
        VALUES('{$username}', '{$password}', {$adminUser});
        ");
            echo "
            <script>$.notify('uusi tunnus luoto', {
              style: 'message',
              className: 'success'
            });</script>
            ";
        }
    }
?>