<div class="nav-bar">
    <div class="row">
        <div id="nav-home" class="col nav-col">
            <img class="nav-icon" src="images/home-icon.png" alt="home">
            <p class="nav-text" >Vihjeet</p>
        </div>
        <div id="nav-pointview" class="col nav-col">
            <img class="nav-icon" src="images/scoreboard-icon.png" alt="scoreboard">
            <p class="nav-text" >Pisteet</p>
        </div>
        <div id="nav-info" class="col nav-col">
            <?php if(0 < $_SESSION['notifications'] && $_GET["page"] != "info"){echo"<img style='margin-top: 0px;margin-left: 20px;height: 15px;position: absolute;' src='images/notification-icon.png' alt='logout'>";} ?>
            <img class="nav-icon" src="images/info-icon.png" alt="info">
            <p class="nav-text" >Info</p>
        </div>
        <div id="nav-logout" class="col nav-col" >
            <img class="nav-icon" src="images/logout-icon.png" alt="logout">
            <p class="nav-text" >Poistu</p>
        </div>
        
    </div>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const site = urlParams.get('page')
    
    $("#nav-"+site).css("opacity","1");
    
    $('#nav-home').click(function() {
        disableButtons()
    $("#nav-home").css("opacity","1");
    window.location = '?page=home';
    });
    
    $('#nav-pointview').click(function() {
        disableButtons()
    $("#nav-pointview").css("opacity","1");
    window.location = '?page=pointview';
    });
    
    $('#nav-info').click(function() {
        disableButtons()
    $("#nav-info").css("opacity","1");
    window.location = '?page=info';
    });
    
    $('#nav-logout').click(function() {
        disableButtons()
     $("#nav-logout").css("opacity","1");
     window.location = window.location.pathname+"/logout.php"
    });

    function disableButtons(){
        $("#nav-teamview").css("opacity","0.5");
        $("#nav-pointview").css("opacity","0.5");
        $("#nav-info").css("opacity","0.5");
        $("#nav-logout").css("opacity","0.5");
    }
</script>