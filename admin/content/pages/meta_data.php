<p id="dataResult"></p>
    <!-- general filters -->
    <div class="filter-row">
        <h3>Aktiivisuus kaavio ja suodattimet</h3>

<form action="" style="margin: auto;width: 250px;" class="admin-form col-auto" id="single">
<input type="text" id="eventName" onclick="toggleDropDown(event)" name="regionName" onkeyup="filterFunction(event)"  placeholder="Tapahtuman nimi" required>
<div id="singleDropDown" class="dropdown-content">
    <div id="events"></div>
</div>
 <input type="text" id="name-filter" placeholder="käyttäjä">
  <input type="time" id="time1-filter" placeholder="aika">--
  <input type="time" id="time2-filter" placeholder="aika">
</form>
 <script>
function toggleDropDown(e) {
  document.getElementById(e.target.nextElementSibling.id).classList.toggle("show");
   reloadRegions();
}

      function reloadRegions(){
    $.post(window.location.pathname+'content/ajax/load_eventList.php', {
    }, function(data,status){
        $('#events').html(data);
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
        loadData()
}
function loadData(){
        $.post(window.location.pathname+'content/ajax/load_logData.php', {
        eventName: $('#eventName').val()
    }, function(data,status){
        $('#dataResult').html(data);
        logs = new Array();
        updateLogs()
    })
}

 </script>

        <div class="row">
            <div class="col-sm filter-container">
                <div class="filter-element">
                    Aktiiviset tunnit <label class="switch">
                        <input type="checkbox" id="activeHours" onclick=setFilters() checked>
                        <span class="slider yellow"></span>
                    </label>
                </div>
                <div class="filter-element">
                    Kilpailijat <label class="switch">
                        <input type="checkbox" id="showTeams" onclick=setTeamFilters() checked>
                        <span class="slider yellow"></span>
                    </label>
                </div>
                <div class="filter-element">
                    Rastinpitäjät <label class="switch">
                        <input type="checkbox" id="showAdmin" onclick=setAdminFilters() checked>
                        <span class="slider yellow"></span>
                    </label>
                </div>
            </div>
            <div class="col-sm filter-container">
                <!---- All of the team filters ---->
                <div class="filter-element">
                    Kirjautui <label class="switch">
                        <input type="checkbox" id="userLogin" onclick=setFilters() checked>
                        <span class="slider red"></span>
                    </label>
                </div>
                
                <div class="filter-element">
                    Nimen rekisteröinti <label class="switch">
                        <input type="checkbox" id="nameRegister" onclick=setFilters() checked>
                        <span class="slider red"></span>
                    </label>
                </div>
                
                <div class="filter-element">
                    Avaa sivun <label class="switch">
                        <input type="checkbox" id="viewPage" onclick=setFilters() checked>
                        <span class="slider red"></span>
                    </label>
                </div>
                
                <div class="filter-element">
                    Vihjeiden paljastus <label class="switch">
                        <input type="checkbox" id="reveals" onclick=setFilters() checked>
                        <span class="slider red"></span>
                    </label>
                </div>
                
                <div class="filter-element">
                    Sai pisteitä <label class="switch">
                        <input type="checkbox" id="getScore" onclick=setFilters() checked>
                        <span class="slider red"></span>
                    </label>
                </div>
                
               <div class="filter-element">
                    Antoi palautetta <label class="switch">
                        <input type="checkbox" id="giveFeedbackTeam" onclick=setFilters() checked>
                       <span class="slider red"></span>
                    </label>
                </div>

               <div class="filter-element">
                    Poisti ilmoituksen <label class="switch">
                        <input type="checkbox" id="removesNotification" onclick=setFilters() checked>
                       <span class="slider red"></span>
                    </label>
                </div>
            </div>
       
        <!---- All of the admin filters ---->
            <div class="col-sm filter-container">
                <div class="filter-element">
                    Kirjautui <label class="switch">
                        <input type="checkbox" id="adminLogin" onclick=setFilters() checked>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="filter-element">
                    Fuksi statuksen asetus <label class="switch">
                        <input type="checkbox" id="freshieSet" onclick=setFilters() checked>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="filter-element">
                    Antoi pisteitä <label class="switch">
                        <input type="checkbox" id="giveScore" onclick=setFilters() checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="filter-element">
                    Lisäsi notification <label class="switch">
                        <input type="checkbox" id="addNotification" onclick=setFilters() checked>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="filter-element">
                    Antoi palautetta <label class="switch">
                        <input type="checkbox" id="giveFeedbackAdmin" onclick=setFilters() checked>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>
       
        <button class="loginTab" id="log-button" style="color: white; background: #00000087; border-radius: 10px 0px 0px 10px;">Logit</button>
        <button class="loginTab" id="chart-button" style="color: white; background: #00000087; border-radius: 0px 10px 10px 0px;">Kaaviot</button>
        <button class="btn btn-primary" onclick=renderActivityChart() style="opacity: 0;">Päivitä kaavio</button>
    </div>
<div id="log-page">
    <div class='row'>
    <div class='col'><input id='teamName' type='text' value='Nimi' style='width: 100%;' readonly></input> </div>
    <div class='col'><input id='freshie' type='text' value='Kirjaus' style='width: 100%;' readonly></input> </div>
    <div class='col'><input id='region' type='text' value='Aika' style='width: 100%;' readonly></input> </div>
    <div class='w-100'></div>
    <div id="log-list" class="row">
    </div>
</div>
</div>
<div id="chart-page">
    <div id="activityChart" style="height: 500px"></div>
    <div class="filter-row">
        <h3>Vihjeiden katsomiset</h3>
    </div>
<div id="hintViewChart" style="height: 800px;"></div>
   <div class="filter-row">
        <h3>Rastinpitäjien tilastot</h3>
    </div>
    <div id="scoreViewChart" style="height: 550px;"></div>
</div>

<script>
var toggleColor = "#0aa405"
var normalColor = $(".loginTab").css("background-color");

    function changeToLogs(){
         $("#log-button").css('box-shadow',"0px 6px transparent");
         $("#chart-button").css('box-shadow',"0px 6px #064b00 ");
              $("#chart-button").css('background-color',normalColor);
          $("#log-button").css('background-color',toggleColor);
    }
    function changeToCharts(){
         $("#log-button").css('box-shadow',"0px 6px #064b00");
         $("#chart-button").css('box-shadow',"0px 6px transparent ");
                   $("#chart-button").css('background-color',toggleColor);
          $("#log-button").css('background-color',normalColor);
    }

function updateLogs(){
    if(teamData == null || adminData == null){
        $.notify('päivämäärältä ei löytynyt yhtään dataa', {
          style: 'message',
          className: 'error'
        });
        return
    }
     fillLogs(teamData,"team")
     fillLogs(adminData,"admin")
     appendLogs()
}

function fillLogs(dataRow,type){
    
    for (let i = 0; i < dataRow.length; i++) {
        
        let actionString;
        if(type == "team"){
            switch(dataRow[i].actionType){
                case "0":
                    actionString = dataRow[i].message
                break;
                case "1":
                    actionString = dataRow[i].message
                break;
                case "2":
                    for (let x = 0; x < hints.length; x++) {
                        if(hints[x].ID === dataRow[i].message){
                         actionString = "paljasti " + hints[x].bar + " vihjeen"
                         break
                        }
                    }
                break;
                case "3":
                    actionString = dataRow[i].message
                break;
                case "4":
                    let messages = dataRow[i].message.split(';');
                    actionString = "Sai "+messages[0]+ ' pistettä käyttäjältä: "'+messages[2]+'" ja kommentin "'+messages[1]+'"';
                break;
                case "5":
                    actionString = 'Antoi palautetta: "'+dataRow[i].message+'"'
                break;
                case "6":
                    actionString = dataRow[i].message
                break;
                default:
                    actionString = "tuntematon action tyyppi";
            }
        }else if(type == "admin"){
            switch(dataRow[i].actionType){
                case "0":
                    actionString = dataRow[i].message
                break;
                case "1":
                    let messages = dataRow[i].message.split(';');
                    actionString = "antoi "+messages[0]+ ' pistettä ja kommentoi "'+messages[1]+'"';
                break;
                case "2":
                    actionString = dataRow[i].message
                break;
                case "3":
                    actionString = 'lisäsi ilmoituksen "'+ dataRow[i].message+ '"';
                break;
                case "4":
                    actionString = 'Antoi palautetta: "'+dataRow[i].message+'"'
                break;
                default:
                    actionString = "tuntematon action tyyppi"
            }
        }
        let date = dataRow[i].date.split(' ')[1]
          logs.push({
              "name": dataRow[i].user,
              "log" : actionString,
              "time10": dataRow[i].time,
              "date": date,
              "type": type,
              "actionNum": parseInt(dataRow[i].actionType)
          })
    }
    //arrange logs into time order
        logs.sort(function(a, b){
            return a.date.localeCompare(b.date)
        })
        logs.reverse()
    
}
function appendLogs(){
    $("#log-list").empty();
    let nameFilter = $("#name-filter").val()
    let time1Filter = $("#time1-filter").val()
    let time2Filter = $("#time2-filter").val()
    
        for (let i = 0; i < logs.length; i++) {
            if(nameFilter != ""){
                if(logs[i].name != nameFilter)
                continue;
            }
            if(time1Filter != ""){
                let times1 = time1Filter.split(':');
                time1 = parseInt(times1[0]*6)+parseInt(times1[1]/10)
                if(logs[i].time10 < time1)
                    continue
            }
            if(time2Filter != ""){
                let times2 = time2Filter.split(':');
                let time2 = parseInt(times2[0]*6)+parseInt(times2[1]/10)
                if(logs[i].time10 > time2)
                    continue
            }
            
            let color;
            if(logs[i].type === "team"){
                color = "#F3213F"
                if(teamFilterArray[logs[i].actionNum] == false){
                    continue;
                }
            }
            else if(logs[i].type === "admin"){
                color = "#2196F3"
                if(adminFilterArray[logs[i].actionNum] == false){
                    continue;
                }
            }
            
            $("#log-list").append(" <div class='col small-padding'><input class='form-control  no-border' style='background-color: "+color+"; color: black;' id='teamName"+i+"' type='text' value='"+ logs[i].name+"' readonly></input> </div>");
            $("#log-list").append(" <div class='col small-padding'><input class='form-control  no-border' style='background-color: "+color+"; color: black;' id='freshie"+i+"' type='text' value='"+ logs[i].log+"' readonly></input> </div>");
            $("#log-list").append(" <div class='col small-padding'><input class='form-control  no-border' style='background-color: "+color+"; color: black;' id='score"+i+"' type='text' value='"+ logs[i].date+"' readonly></input> </div>");
            $("#log-list").append("<div class='w-100'></div>");
        }
}

    google.charts.load('current', {packages: ['corechart']});
    function renderActivityChart() {
        filterActivityArrays();
        google.charts.setOnLoadCallback(drawActivityChart);
    }
    let hints
    let teamData
    let adminData
    let logs = new Array();
    let scoreData = new Array();
    let teamDataRow = new Array(144).fill(0);
    let adminDataRow = new Array(144).fill(0);
    let hintDataRows = new Array(100).fill(0);
    let teamFilterArray = new Array(4).fill(true)
    let adminFilterArray = new Array(4).fill(true)
    let activeHours = true;
    
function setFilters(){
    activeHours = document.getElementById("activeHours").checked;
    
    teamFilterArray[0] = document.getElementById("userLogin").checked;
    teamFilterArray[1] = document.getElementById("nameRegister").checked;
    teamFilterArray[2] = document.getElementById("reveals").checked;
    teamFilterArray[3] = document.getElementById("viewPage").checked;
    teamFilterArray[4] = document.getElementById("getScore").checked;
    teamFilterArray[5] = document.getElementById("giveFeedbackTeam").checked;
    teamFilterArray[6] = document.getElementById("removesNotification").checked;
    
    adminFilterArray[0] = document.getElementById("adminLogin").checked;
    adminFilterArray[1] = document.getElementById("giveScore").checked;
    adminFilterArray[2] = document.getElementById("freshieSet").checked;
    adminFilterArray[3] = document.getElementById("addNotification").checked;
    adminFilterArray[4] = document.getElementById("giveFeedbackAdmin").checked;
    
    appendLogs()
    renderActivityChart()
}

function setAdminFilters(){
    document.getElementById("adminLogin").checked = document.getElementById("showAdmin").checked;
    document.getElementById("freshieSet").checked = document.getElementById("showAdmin").checked;
    document.getElementById("giveScore").checked = document.getElementById("showAdmin").checked;
    document.getElementById("addNotification").checked = document.getElementById("showAdmin").checked;
    document.getElementById("giveFeedbackAdmin").checked = document.getElementById("showAdmin").checked;
    
    setFilters()
}

function setTeamFilters(){
    document.getElementById("viewPage").checked = document.getElementById("showTeams").checked;
    document.getElementById("userLogin").checked = document.getElementById("showTeams").checked;
    document.getElementById("nameRegister").checked = document.getElementById("showTeams").checked;
    document.getElementById("reveals").checked = document.getElementById("showTeams").checked;
    document.getElementById("getScore").checked = document.getElementById("showTeams").checked;
    document.getElementById("giveFeedbackTeam").checked = document.getElementById("showTeams").checked;
    document.getElementById("removesNotification").checked = document.getElementById("showTeams").checked;
    
    setFilters()
}

function filterActivityArrays(){
    teamDataRow = new Array(144).fill(0);
    adminDataRow = new Array(144).fill(0);
    
    let nameFilter = $("#name-filter").val()
    let time1Filter = $("#time1-filter").val()
    let time2Filter = $("#time2-filter").val()
    
        for (let i = 0; i < logs.length; i++) {
            if(nameFilter != ""){
                if(logs[i].name != nameFilter)
                continue;
            }
            if(time1Filter != ""){
                let times1 = time1Filter.split(':');
                time1 = parseInt(times1[0]*6)+parseInt(times1[1]/10)
                if(logs[i].time10 < time1)
                    continue
            }
            if(time2Filter != ""){
                let times2 = time2Filter.split(':');
                let time2 = parseInt(times2[0]*6)+parseInt(times2[1]/10)
                if(logs[i].time10 > time2)
                    continue
            }
        
        if(logs[i].type === "team"){
            if(teamFilterArray[logs[i].actionNum] == false){
                continue;
            }
            teamDataRow[logs[i].time10]++
        }
        else if(logs[i].type === "admin"){
            if(adminFilterArray[logs[i].actionNum] == false){
                continue;
            }
            adminDataRow[logs[i].time10]++
        }
    }
}

    function filterScores(){
        for (let i = adminData.length-1; i > -1; i--) {
                let message = adminData[i].message.split(" ")
            if(message[0] === "Gives"){
                
                let found = false;
                for(let j = 0; j < scoreData.length; j++) {
                    if (scoreData[j].teamID == message[message.length-1] && scoreData[j].adminID == adminData[i].userID) {
                        found = true;
                        break;
                    }
                }
                if(!found){
                    scoreData.push({adminID: adminData[i].userID, teamID: message[message.length-1], score: message[2]})
                }
            }
        }
        let tempArray = new Array();
        for (let i = scoreData.length-1; i > -1; i--) {
            let j;
            var found = false;
            for(j = 0; j < tempArray.length; j++) {
                if (tempArray[j].adminID === scoreData[i].adminID) {
                    found = true;
                    break;
                }
            }
            if(found){
                
                tempScores = tempArray[j].scores
                tempScores.push(parseInt(scoreData[i].score.slice(0, -1)))
                tempArray[j] = {adminID: scoreData[i].adminID, scores: tempScores}
            }
            else{
                tempArray.push({adminID: scoreData[i].adminID, scores: [parseInt(scoreData[i].score.slice(0, -1))]})
            }
        }
        scoreData = tempArray;
        return scoreData;
    }

    function drawActivityChart() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'X');
      data.addColumn('number', 'rastinpitäjät');
      data.addColumn('number', 'kilpailijat');

    for (let i = 0; i < 144; i++) {
      if(activeHours){
          if(adminDataRow[i] != 0 || teamDataRow[i] != 0){
         data.addRow(
        [new Date(2020,2,27,calculateTime(i)[0],calculateTime(i)[1]), adminDataRow[i], teamDataRow[i]]);
        }
      }
      else{
         data.addRow(
        [new Date(2020,2,27,calculateTime(i)[0],calculateTime(i)[1]), adminDataRow[i], teamDataRow[i]]);  
      }
    }
        var options = {
          title: 'Aktiivisuus',
          legend: { position: 'top' },
          chartArea: { left: '3%',right: '2%', top: '8%', width: "97%", height: "85%"},
            hAxis : { 
                textStyle : {
                    fontSize: 13 // or the number you want
                }
        
            }
        };
        
      // Instantiate and draw the chart.
      var chart = new google.visualization.LineChart(document.getElementById('activityChart'));
      chart.draw(data, options);
    }
    
    
    function drawHintViewChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Element');
      data.addColumn({type:'string', role:'annotation'});
      data.addColumn({type: 'string', role: 'style'});
      data.addColumn('number', 'pisteitä annettu');
      
    for(let i = 0; i< hintData.length; i++){
        hintDataRows[hintData[i].ID]++;
    }
    for(let i = 0; i< hintDataRows.length; i++){
        if(hintDataRows[i] != 0){
            data.addRow([hints[i].hint, hints[i].bar,"color: #008100;", hintDataRows[i]])
        }
    }
    
    var view = new google.visualization.DataView(data);
      view.setColumns([0, 3,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
                       
      var options = {
        title: "Vihjeiden kokonais näyttökerrat",
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        annotations: {highContrast: true},
        chartArea: { left: '20%',right: '2%', top: '8%', width: "97%", height: "85%"}
      };
      var chart = new google.visualization.BarChart(document.getElementById("hintViewChart"));
      chart.draw(view, options);
  }
      function drawScoreChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Element');
      data.addColumn({type:'string', role:'annotation'});
      data.addColumn({type: 'string', role: 'style'});
      data.addColumn('number', 'kävijät');
      scoreData = filterScores();
      
    for(let i = 0; i< scoreData.length; i++){
            data.addRow([scoreData[i].adminID, countArrayAverage(scoreData[i].scores).toString(),"color: #00a600;", scoreData[i].scores.length])
    }
    
    var view = new google.visualization.DataView(data);
      view.setColumns([0, 3,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
                       
      var options = {
        title: "Rastien kävijä määrä sekä keskimääräiset pisteet",
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        annotations: {highContrast: true},
        chartArea: { left: '3%',right: '2%', top: '8%', width: "97%", height: "85%"}
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("scoreViewChart"));
      chart.draw(view, options);
  }

function calculateIndex(time){
    let timeStr = time.split(":")
    let index = 0
    index+= parseInt(timeStr[0]) * 60
    index += parseInt(timeStr[1])
    // 1038
    index = (index - 720)/10
    // 31,8
    index = Math.round(index)
    
    return index
}

function calculateTime(minutes){
    let timeHours = (minutes*10+720)/60
    let timeMinutes = (timeHours % 1)
    
    timeHours = timeHours - timeMinutes
    timeMinutes = Math.round(timeMinutes*60)
    return [timeHours,timeMinutes];
}

function countArrayAverage(numArray){
    let sum = 0
    for(let i = 0; i< numArray.length; i++){
        sum += numArray[i] 
    }
    sum = sum / numArray.length
    return Math.round(sum);
}

$( window ).on("load", function() {
     changeToLogs();
    $("#chart-page").hide();
    $("#log-button").click(function(){
        $("#log-page").show();
        $("#chart-page").hide();
       changeToLogs();
        
    });
    $("#chart-button").click(function(){
        $("#log-page").hide();
        $("#chart-page").show();
        changeToCharts();
        renderActivityChart() 
    });
    loadData()

    $("#name-filter, #time1-filter, #name-filter").keyup(function () {
    appendLogs()
    renderActivityChart() 
    });
    $("#name-filter, #time1-filter, #name-filter").change(function () {
    appendLogs()
    renderActivityChart() 
    });
    //renderActivityChart()
    //drawHintViewChart()
     //drawScoreChart()
     
});
</script>


