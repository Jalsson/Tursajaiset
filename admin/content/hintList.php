<?php
require "../../tools/connectToDB.php";
echo "<div class='container'>";
$foundedHints = 0;
$i = 0;
while($foundedHints < $_POST['rowCount']) {
    $result = runSqlQuery("
        SELECT bar_name, bar_hint
        FROM Bar_hint
        WHERE id = $i
        ");
        
    if ($result->num_rows > 0) {
        $foundedHints++;
        if ($row = $result->fetch_assoc()) {
            echo "
                <div class='row'>
                    <div class='col-auto'>
                        <div class='custom-control custom-checkbox'>
                            <input type='checkbox' class='custom-control-input' id='hint-checkbox{$i}'>
                            <label class='custom-control-label' for='hint-checkbox{$i}' style='margin-top: 3px;'></label>
                        </div>
                    </div>
                    <div class='col'><input id='barName{$i}' type='text' value='{$row['bar_name']}' style='width: 100%;'></input> </div>
                    <div class='col'><input id='barHint{$i}' type='text' value='{$row['bar_hint']}' style='width: 100%;'></input> </div>
                    <input type='hidden' id='hint-id{$i}' name='custId' value='{$i}'>
                    <div class='w-100'></div>

                </div>
                ";
        }
    }
    $i++;
}
echo "</div>";
echo "<button id='update-hints' onclick='UpdateHints()'> Päivitä vinkit</button>";
echo "<button id='update-hints' onclick='DeleteHints()'> Poista vinkit</button>";
?>