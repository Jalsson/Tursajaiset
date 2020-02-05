<?php
$rowCount = getNumberOfRows("Bar_hint");
echo "<script>var rowCount = {$rowCount};</script>";

$regionCount = getNumberOfRows("Region");
echo "<script>var regionCount = {$regionCount};</script>";
?>
<style>
.col {
    padding: 3px;
}
</style>
<h1>Muokkaa alueita</h1>
<br>
<h3>Lisää uusia vihjeitä</h3>
<script>
ReloadHintList();
ReloadRegionList();
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
            $('#hints').append($("<button id='submit' onclick='PostHints()' >lähetä vihjeet</button>"));
              }
          });
        });

function PostHints(){
        var bars = new Array();
        var hints = new Array();
        for (let index = 0; index < hintCount; index++) {
            bars.push($('#bar'+index).val());
            hints.push($('#hint'+index).val());
        }
        $.post('/kisapanel/tools/modifyRegions.php', {
        hintCount: $("#hintCount").val(),
        bars: JSON.stringify(bars),
        hints: JSON.stringify(hints)
    }, function(data,status){
        $('#postHintResult').html(data);
    })
    ReloadHintList();
}


function ReloadHintList(){
    $.post('/kisapanel/admin/content/hintList.php', {
        rowCount: rowCount
    }, function(data,status){
        $('#reloadHintResult').html(data);
    })
}

function ReloadRegionList(){
    $.post('/kisapanel/admin/content/regionList.php', {
        regionCount: regionCount
    }, function(data,status){
        $('#reloadRegionResult').html(data);
    })
}

function UpdateHints(){
    var rowsFound = 0;
    let index = 1;
    while (rowsFound < rowCount) {
    if ($("#hint-id"+(index)).length){
        rowsFound++;
    }
    if ($("#hint-checkbox"+(index)).is(":checked")) {
            $.post('/kisapanel/tools/modifyRegions.php', {
                    updatedBarName: $("#barName"+ index).val(),
                    updatedBarHint: $("#barHint" + index).val(),
                    id: index
                }, function(data,status){
                    $('#updateHintResult').html(data);
            })
        }
        index++;
    }

}

function DeleteHints(){

    var confirmed = confirm("Haluatko varmasti poistaa valitut vihjeet");
    if(confirmed == false){
        return;
    }
    var rowsFound = 0;
    let index = 1;
    while (rowsFound < rowCount) {
    if ($("#hint-id"+(index)).length){
        rowsFound++;
    }
    if ($("#hint-checkbox"+(index)).is(":checked")) {
        $.post('/kisapanel/tools/modifyRegions.php', {
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

function DeleteRegions(){

var confirmed = confirm("Haluatko varmasti poistaa valitut alueet");
if(confirmed == false){
    return;
}
var rowsFound = 0;
let index = 1;
while (rowsFound < regionCount) {
if ($("#region-id"+(index)).length){
    rowsFound++;
}
if ($("#region-checkbox"+(index)).is(":checked")) {
    $.post('/kisapanel/tools/modifyRegions.php', {
                deletedRegion: $("#Region"+ index).val(),
                id: index
            }, function(data,status){
                $('#deleteRegionResult').html(data);
        })
    }
    index++;
}
}

$(document).ready(function(){
        $("#region-add").click(function(){
    var barsArray = new Array();
    var rowsFound = 0;
    let index = 1;
    while (rowsFound < rowCount) {
        if ($("#hint-id"+(index)).length){
            rowsFound++;
        }
        if ($("#hint-checkbox"+(index)).is(":checked")) {
            barsArray.push(index);
        }
        index++;
    }
    $.post('/kisapanel/tools/modifyRegions.php', {
            barsArray: JSON.stringify(barsArray),
            regionName: $("#regionName").val()
        }, function(data,status){
            $('#addRegionResult').html(data);
    })
    });
});

</script>
<input type="text" id="hintCount" value="">vihjeiden määrä</input><br />
    <button id="fill-form"> Luo lomake</button>

    <div id='hints' action="" method="post">

    </div>

<h2>Muokkaa vihjeita</h2>
<p id="postHintResult"></p>
<p id="reloadHintResult"></p>
<p id="updateHintResult"></p>
<p id="deleteHintResult"></p>
<p id="addRegionResult"></p>

<h2>Lisää uusi alue</h2>
<input type="text" id="regionName" value="">Alueen nimi</input><br />
<button id="region-add"> Luo alue</button>

<h2>Poista alueita</h2>

<p id="reloadRegionResult"></p>
<p id="deleteRegionResult"></p>