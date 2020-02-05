<?php
require "../../tools/connectToDB.php";
echo "<div class='container'>";
$foundedRegions = 0;
$i = 1;
while ($foundedRegions < $_POST['regionCount']) {
    $result = runSqlQuery("
        SELECT region_name, bar_hint_ids
        FROM Region
        WHERE id = $i
        ");

    if ($result->num_rows > 0) {
        $foundedRegions++;
        if ($row = $result->fetch_assoc()) {
            $regionName = $row['region_name'];
            $regionBars = json_decode($row['bar_hint_ids']);
            echo "
                <div class='row'>
                    <div class='col-auto'>
                        <div class='custom-control custom-checkbox'>
                            <input type='checkbox' class='custom-control-input' id='region-checkbox{$i}'>
                            <label class='custom-control-label' for='region-checkbox{$i}' style='margin-top: 3px;'></label>
                        </div>
                    </div>
                    <div class='col'><input id='Region{$i}' type='text' value='{$regionName}' style='width: 100%;'readonly></input> </div>
                    <input type='hidden' id='region-id{$i}' name='regionId' value='{$i}'>";
            for ($j = 0; $j < count($regionBars); $j++) {
                $result = runSqlQuery("
                        SELECT bar_name
                        FROM Bar_hint
                        WHERE id = {$regionBars[$j]}
                        ");

                if ($result->num_rows > 0) {
                    if ($row = $result->fetch_assoc()) {
                        echo "<div class='col'><input type='text' value='{$row['bar_name']}' style='width: 100%;'readonly></input> </div>";
                    }
                }
            }
            echo "<div class='w-100'></div>

                </div>
                ";
        }
    }
    $i++;
}
echo "</div>";
echo "<button id='update-hints' onclick='DeleteRegions()'> Poista vinkit</button>";
