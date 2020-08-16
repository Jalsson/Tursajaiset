<style>
    p{
    color: white;
    }
</style>
<br>
<div class="filter-row" style="padding: 20px;"> 
    <h3>Luo uusi tapahtuma</h3>
    <p>Tällä lomakkeella voit automaattisesti luoda tarvittavan määrän lippuja ja jakamaan ne tasaisesti alueiden kesken</p>

    <div style="display: flex;justify-content: center;">
        <form action="" method="post" class="admin-form">
            <h5>Uusi tapahtuma</h5>
            <input type="text" name="eventName"  placeholder="Nimi" required>
            <br>
            <input type="number" name="teamCount"  placeholder="Tiimien määrä" required>
            <br>
            <input type="date" name="eventDate" placeholder="Päivämäärä" required>
            <br /><br />
            <input class="btn btn-primary" type="submit" value="Luo tunnus">
        </form>
    </div>
</div>
<hr>


<div class="filter-row" style="padding: 20px;"> 
    <h3>Lisää uusia Tiimejä</h3>

<p>Näiden kahden lomakkeen avulla voit joko luoda yksittäisiä tunnuksia testi käyttöön.<br>
Tai voit halutetssassi luoda useita tunnuksia tietylle alueelle vielä jälkikäteen.</p>

<div class="flex-container row">
<form action="" method="post" class="admin-form col-auto" id="single">
    <h5>Yksittäinen tunnus</h5>
<input type="number" name="loginID"  placeholder="Tiimi tunnus" required>
<br>

<input type="text" id="singleRegionName" onclick="toggleDropDown(event)" name="regionName" onkeyup="filterFunction(event)"  placeholder="Alue" required>
<div id="singleDropDown" class="dropdown-content">
    <div id="singleRegions">
    </div>
  </div>
<br /><br />
<input class="btn btn-primary" type="submit" value="Luo tunnus">
</form>


<form action="" method="post" class="admin-form col-auto" id="multi">
<h5>Massa luonti</h5>
<input type="number" name="idCount" placeholder="Tunnusten määrä" required >
<br>
<input type="text" id="multiRegionName" onclick="toggleDropDown(event)" onkeyup="filterFunction(event)" name="regionName" placeholder="Alue"  required  >
<div id="multiDropDown" class="dropdown-content">
    <div id="multiRegions">
    </div>
  </div>
<br /><br />
<input class="btn btn-primary" type="submit" value="Luo usea tunnus">
</form>
 </div>
 </div>
 
 <script>
function toggleDropDown(e) {
  document.getElementById(e.target.nextElementSibling.id).classList.toggle("show");
}
 reloadRegions();
      function reloadRegions(){
    $.post(window.location.pathname+'content/ajax/regions.php', {
    }, function(data,status){
        $('#singleRegions').html(data);
        $('#multiRegions').html(data);
    })
}

     function filterFunction(e) {
         console.log(e.target)
  var input, filter, ul, li, a, i;
  input = e.target;
  filter = input.value.toUpperCase();
  div = e.target.parentElement;
  a = div.getElementsByTagName("li");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

function setRegion(e){
        $("#"+e.target.parentElement.parentElement.previousElementSibling.id).val(e.target.textContent.trim())
        document.getElementById(e.target.parentElement.parentElement.id).classList.toggle("show");
}
 </script>
<?php

if (!empty($_POST)) {

    if (isset($_POST['loginID']) && isset($_POST['regionName'])) {
        
        $loginID = $_POST["loginID"];
        $regionName = $_POST['regionName'];
        if(checkForValidity($loginID,$regionName)){
            AddnewTeam($loginID,$regionName);
        notification("Tiimi lisätty", "success");  
        }
    }
    
        if (isset($_POST['teamCount']) && isset($_POST['eventDate']) && isset($_POST['eventName'])) {
        $date = strtotime($_POST["eventDate"]);
        $date = date('Y-m-d', $date);
        
        insertSql("
         INSERT INTO Myevent (time, team_count, name)
        VALUES(DATE '{$date}',{$_POST['teamCount']},'{$_POST['eventName']}');
        ");
        notification("Uusi event lisätty", "success");
        $result = RunSqlQuery("
        SELECT COUNT(id)
        FROM Region;
        ");

        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $regionCount = $row['COUNT(id)'];
            }
        }
        else{
            return;
        }
        $teamsPerRegion = $_POST['teamCount']/$regionCount;
        
        $result = runSqlQuery("
        SELECT name
        FROM Region");
        $teamCount=0;
       if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                for($i = 0; $i < $teamsPerRegion; $i++){
                    $loginID = rand(000,1000);
                    while(checkForValidity($loginID,$row['name']) == false){
                        $loginID = rand(000,1000);
                    }
                    AddnewTeam($loginID,$row['name']);
                    $teamCount++;
                }
            }
        }
        notification("Lisättiin {$teamCount} uutta tiimiä", "success");
    }
    if (! empty( $_POST ) ) {
    $userName = $_SESSION['username'];
    if(isset($_POST['importance']) && isset($_POST['message'])){
        $message = $_POST['message'];
        $importance = $_POST['importance'];
        
        if(!empty($message)){
                if(sanityCheck($message, 'string', 180) == false){
                echo "
                <script>$.notify('Kommentti on liian pitkä(180 merkkiä)', {
                style: 'message',
                className: 'error'
                });</script>
                ";
                $message = NULL;
                }
                else{
                $message = TrimString($message);
                }
            }
                $notificationID = p_Statement_Notification($message,$importance);
                
                require '../tools/dbconfig.php';
                
                // Check connection
                if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                }
                $conn = new mysqli($servername, $username, $password, $dbname);
                $teamID;
                $stmt = $conn->prepare("INSERT INTO Notification_relation (notification_id, team_id) VALUES (?,?)");
                $stmt->bind_param("ii", $notificationID , $teamID);
                
                $result = RunSqlQuery("
                SELECT id FROM Team");

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $teamID = $row['id'];
                        $stmt->execute();
                    }
                }
                
                $stmt->close();
                $conn->close();
                
                
                p_Statement_log("Admin_log",3,$message,$_SESSION['adminID']);
                
                notification("Ilmoitus lisätty!", "success");
            
        }
    }
}
?>



<?php

if (!empty($_POST)) {

    if (isset($_POST['idCount']) && isset($_POST['regionName'])) {
        
        $regionName = $_POST['regionName'];
        if(checkForValidity($loginID,$regionName)){
            for($i = 0; $i < $_POST['idCount']; $i++){
                $loginID = rand(000,1000);
                while(checkForValidity($loginID,$regionName) == false){
                    $loginID = rand(000,1000);
                }
                AddnewTeam($loginID,$regionName);
            }
            notification("Tiimit lisätty", "success");
        }
    }
}

function checkForValidity($loginID,$regionName){
        //this checks if the login id already exists in database and returns false if so
        $result = RunSqlQuery("
        SELECT id
        FROM Team
        WHERE login_id = {$loginID};
        ");

        if ($result->num_rows > 0) {
            notification("Tiimi tunnus tai nimi on jo olemassa", "error");
            return false;
        }
        
        // this checks if region we are trying to add team exists, return false if not 
        $result = RunSqlQuery("
        SELECT id
        FROM Region
        WHERE name = '{$regionName}';
        ");

        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $regionID = $row['id'];
            }
        }
        else{
            notification("Aluetta ei ole olemassa", "error");
            return false;
        }
        return true;
}

function AddnewTeam($loginID,$regionName){
        $regionID;
        
        $result = RunSqlQuery("
        SELECT id
        FROM Region
        WHERE name = '{$regionName}';
        ");
        
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $regionID = $row['id'];
            }
        }
        else{
            notification("Aluetta ei ole olemassa", "error");
            return false;
        }
        
        //first we insert the new team to Team table and take the id in variable.
        $team_id = InsertSql("
                INSERT INTO Team (login_id, region)
                VALUES({$loginID},{$regionID});
                ");

        //then we select all bars that are related to wanted region
        $result = runSqlQuery("
        SELECT bar_id
        FROM Region_relation
        WHERE Region_relation.region_id = {$regionID}");

    // here we loop all bars and create new score, then we create relation between new hint, bar and the team that ownes it
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
        $score_id = InsertSql("
                 INSERT INTO Score (revealed)
                VALUES(0);
                ");
                
        InsertSql("
                 INSERT INTO Score_relation (team_id, score_id, bar_id)
                VALUES({$team_id},{$score_id},{$row['bar_id']});
                ");
        }
    }
    return true;
}

?>
<hr>
<div class="filter-row" style="padding: 20px;"> 
    <h3>Työkalut</h3>
    
    <p>Tästä voi lähettää ilmoituksen tiimeille. <br> Ilmoituksen näkevät kaikki tiimit.</p>
<form action="" method="post" id="usrform">
 <label for="score_field" class="sr-only">Pisteet</label>
 
<style>
.blueText{ background-color:green; }

.yellowText{ background-color:blue; }

.redText{ background-color:red; }
</style>


<textarea class="form-control notification-input" style="color: black;"id="message-text" rows="5" cols="50" name="message" form="usrform" placeholder="Ilmoituksen sisältö"><?php echo $_SESSION['comment'] ?>

</textarea>
<p style="margin: 0px;" id="remaining">180</p>
<select onchange="this.className=this.options[this.selectedIndex].className" type="number" name="importance" id="importance-select" class="form-control form-control-sm non-important-notification" style="display: initial;width: auto;border-width: 0px;" value="<?php echo $_SESSION['score'] ?>" placeholder="Syötä pisteet" required>
     <option class="form-control form-control-sm non-important-notification" value="2" >Ei tärkeä</option>
    <option class="form-control form-control-sm important-notification"   value="1" >Tärkeä</option>
    <option class="form-control form-control-sm very-important-notification" value="0" >Hyvin tärkeä</option>
</select>
 <button class="btn btn btn-primary " type="submit">Lisää</button>   
<script>
let Colors = ["#f03131d6","#ffd943d6","#43d3ffd6"]
$("#message-text").css("background",Colors[2])
    $('select').on('change', function() {
        $("#message-text").css("background",Colors[this.value])
    });
    
$('textarea').keypress(function(e) {
    var tval = $('textarea').val(),
        tlength = tval.length,
        set = 180,
        remain = parseInt(set - tlength);
    $('#remaining').text(remain+"");
    if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
        $('textarea').val((tval).substring(0, tlength - 1));
        return false;
    }
})
</script>

</form>
    
<h3>Arkistointi ja tulostus</h3>
<p>Tästä voit hakea kaikki tämän hetkiset tiimi tunnukset jos haluat esim tulostaa ne ilman kuvaa<br>
Myöskin tapahtuman jälkeen voit käydä arkistoimassa tiimi tunnukset omaan arkisto tietokantaan</p>
    <button id="get-teams" class="btn btn-primary"> Hae tunnukset</button>
    <button id="archive-teams" class="btn btn-primary"> Arkistoi nykyiset teamit</button>
<p id="teamIDsResult"></p>

<input type=number name=fontSize value=32 id="teamNunmberFontSize">
<select id="color" name="väri">
<option value="white">white</option>
<option value"black">black</option>
<option value"green">green</option>
<option value"royalblue">royalblue</option>
<option value"darkred">darkred</option>
</select>
<input type="file" name="filePhoto" value="" id="filePhoto" class="required borrowerImageFile" data-errormsg="PhotoUploadErrorMsg">
<button id="btndownload">Lataa kaikki</button>
<br/><br/>


<canvas width="500w" height="500" id="passporCanvas"></canvas>





</div>

<style>
    img:not(#imagem-principal) {
    position: absolute;
}
</style>

<script>
$(document).ready(function(){
    // Loads all teams and adds them  to <p> teamIDsResult
        $.post(window.location.pathname+'content/ajax/load_teamIDs.php', {
        }, function(data,status){
            $('#teamIDsResult').html(data);
        })
    // archives all the teams for later stats use
    $("#archive-teams").click(function(){
        let confirmed = confirm("Haluatko varmasti arkistoida kaikki teemit?");
        if(confirmed == false){
        return;
        }
        $.post(window.location.pathname+'content/ajax/archive_teams.php', {
        }, function(data,status){
            $('#teamIDsResult').html(data);
        })
    })
})

let img = new Image;
let canvas;
let ctx;
let x;
let y;
let fontSize;
function readURL(input) {
  if (input.files && input.files[0]) {

    var reader = new FileReader();
    reader.onload = function(e) {
                  
        canvas = document.getElementById('passporCanvas')
        ctx = canvas.getContext("2d");
        var dwn = document.getElementById('btndownload')
            dwn.onclick = function(){
            if(x === undefined || y === undefined){
                alert("text position not defined")
                return
            }
            for(let i = 0; i < teamIds.length; i++){
                drawText(teamIds[i])
                download("rastipassi"+teamIds[i])
            }
        }
    
        img.src = e.target.result;
        
        img.onload = function() {
            canvas.width = img.width
            canvas.height = img.height
            ctx.drawImage(this, 0, 0);
            
            canvas.addEventListener('mousedown', function(e) {
                getCursorPosition(canvas, e)
            })
        };
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$("#filePhoto").change(function() {
  readURL(this);
});

function getCursorPosition(canvas, event) {
    fontSize = document.getElementById('teamNunmberFontSize').value
    const rect = canvas.getBoundingClientRect()
    x = event.clientX - rect.left
    y = event.clientY - rect.top
    
    drawText("1234")
}

function drawText(text){
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    ctx.drawImage(img, 0, 0);
    ctx.fillStyle = document.getElementById("color").options[document.getElementById("color").selectedIndex].value;
    ctx.font = `bold ${fontSize.toString()}px Arial`;
    ctx.fillText(text, x, y);
}

function download(filename) {
  /// create an "off-screen" anchor tag
  var lnk = document.createElement('a'), e;

  /// the key here is to set the download attribute of the a tag
  lnk.download = filename;

  /// convert canvas content to data-uri for link. When download
  /// attribute is set the content pointed to by link will be
  /// pushed as "download" in HTML5 capable browsers
  lnk.href = canvas.toDataURL("image/png;base64");

  /// create a "fake" click-event to trigger the download
  if (document.createEvent) {
    e = document.createEvent("MouseEvents");
    e.initMouseEvent("click", true, true, window,
                     0, 0, 0, 0, 0, false, false, false,
                     false, 0, null);

    lnk.dispatchEvent(e);
  } else if (lnk.fireEvent) {
    lnk.fireEvent("onclick");
  }
}
</script>


