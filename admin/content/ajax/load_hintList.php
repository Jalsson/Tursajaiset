<?php
require "../../../tools/connectToDB.php";
echo "<div class='container'>
<script>
    let bars = [];
</script>
";

    $result = runSqlQuery("
            SELECT id, name, hint, (SELECT username FROM Admin WHERE id = Bar.admin) AS owner
            FROM Bar
        ");
        
        // doing echo where we display all the values
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "
            <script>
                bars.push('{$row['name']}');
            </script>
            
                <div class='row'>
                    <div class='col-auto'>
                        <div class='custom-control custom-checkbox'>
                            <input value='{$row['id']}' type='checkbox' onclick='updateList(event)' class='custom-control-input' id='hint-checkbox{$row['id']}'>
                            <label class='custom-control-label' for='hint-checkbox{$row['id']}' style='margin-top: 3px;'></label>
                        </div>
                    </div>
                    <div class='col'><input id='barName{$row['id']}' type='text' value='{$row['name']}' style='width: 100%;'></input> </div>
                    <div class='col-7'><input id='barHint{$row['id']}' type='text' value='{$row['hint']}' style='width: 100%;'></input> </div>
                    <div class='col'><input id='barAdmin{$row['id']}' onclick='toggleDropDown(event)' onkeyup='filterFunction(event)' type='text' value='{$row['owner']}' style='width: 100%;'></input> 
                        <div id='DropDown{$row['id']}' class='dropdown-content'>
                        <div id='multiRegions'>
                        </div>";
                        include 'admins.php';
                      echo "</div>
                    </div>
                    <input type='hidden' id='hint-id{$row['id']}' name='custId' value='{$row['id']}'>
                    <div class='w-100'></div>
                </div>
                ";
        }
    }
    else {
    echo "0 results";
}

// here is all the logic that goes into dropdown username selector
echo "
<script>
$.holdReady( false );
// this filters the input field 
function toggleDropDown(e) {
  document.getElementById(e.target.nextElementSibling.id).classList.toggle('show');
}
     function filterFunction(e) {
  var input, filter, ul, li, a, i;
  input = e.target;
  filter = input.value.toUpperCase();
  div = e.target.parentElement;
  a = div.getElementsByTagName('li');
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = '';
    } else {
      a[i].style.display = 'none';
    }
  }
}

function setAdmin(e){
        $('#'+e.target.parentElement.previousElementSibling.id).val(e.target.textContent.trim())
        document.getElementById(e.target.parentElement.id).classList.toggle('show');
}
</script>

</div> ";
echo " <div class='flex-container' style='justify-content: space-evenly;'> <button class='btn btn-primary' id='update-hints' onclick='UpdateHints()'> Päivitä valitut vinkit</button>";
echo "<button class='btn btn-primary' id='update-hints'  onclick='DeleteHints()'> Poista valitut vinkit</button> </div>";
?>

