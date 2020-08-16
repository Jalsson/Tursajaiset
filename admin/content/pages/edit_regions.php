<?php
// for js to know what id's need the update. we ask for server to deliver how many row are in given tables.
// So when we update stuff on these tables. js runs a search for hint id's and stops when it founds a given number..
// of hints. that number is defined below. This could be done in better way. feel free to update :)
$rowCount = getNumberOfRows("Bar");
echo "<script>var rowCount = {$rowCount};</script>";

$regionCount = getNumberOfRows("Region");
echo "<script>var regionCount = {$regionCount};</script>";
?>

<script>
$.holdReady( true );
//defining background colors for bars
let colors = ["#db5353","#aa7240","#eae15c", "#b6e157", "#85dd5d", "#7bd797", "#7bd7ca", "#4cabe4", "#6a66e4", "#9f66e4","#d673e4","#db67be","#a2173e","#8c480b","#708a37","#db5353","#aa7240","#eae15c", "#b6e157", "#85dd5d", "#7bd797", "#7bd7ca", "#4cabe4", "#6a66e4", "#9f66e4","#d673e4","#db67be","#a2173e","#8c480b","#708a37" ];
// this page has all functions that modify regions and hints
let barsToUpdate = []
//here we define path to get all necesacy ajax calls independent from url
let originalPath = window.location.pathname.substring(0, 11)

ReloadHintList();
ReloadRegionList();

// here is function to create a form that can be used to create more hints.
// just creates inputs with for loop and uses PostHints function to post new hints
    $(document).ready(function(){
      $("#fill-form").click(function(){
          hintCount = $("#hintCount").val()
          $("#hints").empty();
          if(hintCount > 0){
              $('#hints').append($("<a> täytä vihjeet</a> <br>"));
        for (let index = 0; index < hintCount; index++) {

            $('#hints').append($("<input type='text' id='bar"+(index)+"'placeholder='baari "+(index+1)+"'></input>"));

            $('#hints').append($("<input type='text' id='hint"+(index)+"'placeholder='vihje "+(index+1)+"'></input>"));
            $('#hints').append($("<br>"));
        }
        $('#hints').append($("<button id='submit' class='btn btn-primary' onclick='PostHints()' >lähetä vihjeet</button>"));
          }
      });
    });

// post new hints to be added in database
function PostHints(){
        var bars = new Array();
        var hints = new Array();
        // gets all new bars and hints for them
        for (let index = 0; index < hintCount; index++) {
            bars.push($('#bar'+index).val());
            hints.push($('#hint'+index).val());
        }
        // posting it to new php
        $.post(originalPath+'tools/modifyRegions.php', {
        hintCount: $("#hintCount").val(),
        bars: JSON.stringify(bars),
        hints: JSON.stringify(hints)
    }, function(data,status){
        $('#postHintResult').html(data);
    })
    ReloadHintList();
}
//this reloads all hints with ajax call. this function is used in various places and enables site to update without full refresh
function ReloadHintList(){
    $.post(window.location.pathname+'content/ajax/load_hintList.php', {
    }, function(data,status){
        $('#reloadHintResult').html(data);
    })
}
// same here for regions
function ReloadRegionList(){
    $.post(window.location.pathname+'content/ajax/load_regionList.php', {
    }, function(data,status){
        $('#reloadRegionResult').html(data);
    })
}

// looping through all hints with id+index untill we find all hints. can very easily be infinite loop if not carefull
// after that we post and update the database

function updateList(e){
    let id = e.target.value
     if ($("#"+e.target.id).is(":checked")) {
         barsToUpdate.push(id)
     }else{
         let index = barsToUpdate.indexOf(id)
         barsToUpdate.splice(index, 1)
     }
}

function UpdateHints(){
    
    barsToUpdate.forEach(element => {
        console.log($("#barAdmin" + element).val());
    $.post(originalPath+'tools/modifyRegions.php', {
                        updatedBarName: $("#barName"+ element).val(),
                        updatedBarHint: $("#barHint" + element).val(),
                        updatedBarOwner: $("#barAdmin" + element).val(),
                        id: element
                    }, function(data,status){
                        $('#updateHintResult').html(data);
                })
    });
}


// read upper text. basically same
function DeleteHints(){

    var confirmed = confirm("Haluatko varmasti poistaa valitut vihjeet");
    if(confirmed == false){
        return;
    }
    var rowsFound = 0;
    let index = -2;
    while (rowsFound < rowCount) {
    if ($("#hint-id"+(index)).length){
        rowsFound++;
    }
    if ($("#hint-checkbox"+(index)).is(":checked")) {
        $.post(originalPath+'tools/modifyRegions.php', {
                deletedBarName: $("#barName"+ index).val(),
                deletedBarHint: $("#barHint" + index).val(),
                id: index
                }, function(data,status){
                    $('#deleteHintResult').html(data);
            })
        }
        index++;
    }
}

// read upper text. basically same
function DeleteRegions(){

let confirmed = confirm("Haluatko varmasti poistaa valitut alueet");
if(confirmed == false){
    return;
}
let rowsFound = 0;
let index = -2;
while (rowsFound < regionCount) {
    if ($("#region-id"+(index)).length){
        rowsFound++;
    }
    if ($("#region-checkbox"+(index)).is(":checked")) {
        $.post(originalPath+'tools/modifyRegions.php', {
                    deletedRegion: $("#Region"+ index).val(),
                    id: index
                }, function(data,status){
                    $('#deleteRegionResult').html(data);
            })
        }
        index++;
    }
}

// adds new region with given name and checked bars.
$(document).ready(function(){
        $("#region-add").click(function(){
            
    var barsArray = new Array();
    var rowsFound = 0;
    let index = -2;
    while (rowsFound < rowCount) {
        if ($("#barHint"+(index)).length){
            rowsFound++;
        }
        if ($("#hint-checkbox"+(index)).is(":checked")) {
            barsArray.push(index);
        }
        index++;
    }
    
    $.post(originalPath+'tools/modifyRegions.php', {
            barsArray: JSON.stringify(barsArray),
            regionName: $("#regionName").val()
        }, function(data,status){
            $('#addRegionResult').html(data);
    })
    });
});

// this function color codes all the bars with preset colors based on their bar name array is defined in load_hintList.php
function colorCode(){
    $( "input" ).each(function( index ) {
        if(bars.includes($( this ).val())){
            if(colors[bars.indexOf($( this ).val())] != undefined ){
            $( this ).css('background',colors[bars.indexOf($( this ).val())])
            $( this ).css('border-color',colors[bars.indexOf($( this ).val())])
            }
        }
    });
}

</script>

<style>
.col {
    padding: 3px;
}
p{
    color: white;
}
</style>
<br>
<div class="filter-row" style="padding: 20px;"> 
    <div class="flex-container" style="margin: 10px 0px;">
        <h3>Lisää uusia vihjeitä</h3>
    </div>
    <p>Alla olevalla kentällä pystyt luomaan lomakkeen jolla lisäät vihjeitä tietokantaan</p>
    <div style="display: flex;justify-content: center;">
        <div class="admin-form">
            <h5>Luo uusia vihjeitä</h5>
            <input type="text" id="hintCount" value="" placeholder="Vihjeiden määrä"></input><br />
            <button class="btn btn-primary" id="fill-form"> Luo lomake</button>
        </div>
    </div>
    <div id='hints' action="" method="post"></div>
    </div>
    <br>
    <div class="filter-row" style="padding: 20px 0px;"> 
    <p> Alapuolella näkyy kaikki olemassa olevat vihjeet. Muokkaaminen toimii siten <br>
    että valitset ne vihjeet joita haluat muokata tai poistaa. Teet muutokset teksti kenttään ja painat nappia</p>



    <div id="postHintResult"></div>
    <div id="reloadHintResult"></div>
    <div id="updateHintResult"></div>
    <div id="deleteHintResult"></div>
    <br><h5>Uuden alueen luonti</h5>
    <p>Uuden alueen luonti tapahtuu siten että valitset haluamasi vihjeet ja annat alueelle nimen.<br>
    <u>Muista aina lisätä alueeseen bonus baari ja loppurasti</u></p>
    <div style="display: flex;justify-content: center;">
        <div class="admin-form">
            <h5>Lisää uusi alue</h5>
            <input type="text" id="regionName" value="" placeholder="Alueen nimi"></input><br />
            <button class="btn btn-primary" id="region-add"> Luo alue</button>
        </div>
    </div>
</div>
<div id="addRegionResult"></div>


<div class="filter-row" style="padding: 20px 0px;"> 
<h3>Poista alueita</h3>

<div id="reloadRegionResult"></div>
<div id="deleteRegionResult"></div>
</div>