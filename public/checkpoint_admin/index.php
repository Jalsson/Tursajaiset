 <label for="login_id" class="sr-only">Tiimi tunnus</label>
<input type="number" name="number" id="login_id" class="form-control" style="display: initial;width: auto;" placeholder="Syötä tiimi tunnus" onfocus="this.value=''" required autofocus>
  <button class="btn btn-lg btn btn-success btn-block" type="submit" id="search_team" style="display: initial;width: auto;margin: auto;" ><img class="nav-icon" style="margin: 0px;"  src="images/search-icon.png" alt="search"></button>
<script>
 document.getElementById("search_team").onclick = GetTeamView;

function GetTeamView(){
    $.post(window.location.pathname+'public/checkpoint_admin/team_view.php', {
    teamID: $('#login_id').val(),
    userName: '<?php echo"{$_SESSION['username']}"; ?>',
    userId: '<?php echo"{$_SESSION['userId']}"; ?>'
}, function(data,status){
    $('#result').empty();
    $('#result').html(data);
})
}


</script>

<p id="freshie-result"></p>
<p id="result"></p>

<?php 
$_SESSION['comment'] = NULL;
$_SESSION['score'] = NULL;
if(isset($_GET['recentID'])){
    echo"<script>$(document).ready(function(){
            $('#login_id'). val({$_GET['recentID']});
            GetTeamView()
        });
        </script>";
}
?>
