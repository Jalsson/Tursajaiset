<br>
<style>
    li{
        color: white;
    }
</style>
<div class="filter-row" style="padding: 20px;"> 
<div class="flex-container" style="margin: 10px 0px;">
    <h3>Lisää uusi käyttäjä</h3>
</div>

<ul><li> Tässä voi lisätä uusia admin sekä rastipitäjien käyttäjiä.</li><li> Salasanaa ei voi uusia vielä joten muista ottaa se talteen.</li> <li>Jos haluat että käyttäjällä on pääsy näille sivuille
niin muista checkata  super admin päälle</li></ul>
<div class="flex-container">
<form action="" method="post" class="admin-form">
    <h5>Luo käyttäjä</h5>
<input type="text" name="username" placeholder="Käyttäjänimi" required><br><br>
<input name="password" required="required" type="password" id="password" placeholder="salasana"/><br>
<input name="password_confirm" required="required" type="password" id="password_confirm" oninput="check(this)" placeholder="salasana uudestaan"/>
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
<div class='custom-control custom-checkbox' style="color: white;">
    <input type='checkbox' class='custom-control-input' id='super-admin-checkbox' name="adminUser" value="Yes">
    <label class='custom-control-label' for='super-admin-checkbox' style='margin-top: 3px;'></label>Super admin user
</div>
<br />
<input  class="btn btn-primary" type="submit" value="Luo tunnus">
</form>
</div>
</div>
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
        INSERT INTO Admin (username, password, maintenance)
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