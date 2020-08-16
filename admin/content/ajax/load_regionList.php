<?php
require "../../../tools/connectToDB.php";
echo "<div class='container'>";

    $result = runSqlQuery("
        SELECT name, id
        FROM Region");

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $regionName = $row['name'];
            $id = $row['id'];
            echo "
                <div class='row'>
                    <div class='col-auto'>
                        <div class='custom-control custom-checkbox'>
                            <input type='checkbox' class='custom-control-input' id='region-checkbox{$id}'>
                            <label class='custom-control-label' for='region-checkbox{$id}' style='margin-top: 3px;'></label>
                        </div>
                    </div>
                     <div class='col-1'><input type='text' id='region-id{$id}' name='regionId' style='width: 100%;' value='{$id}'></input> </div>
                    <div class='col'><input id='Region{$id}' type='text' value='{$regionName}' style='width: 100%;'readonly></input> </div>
                    <input type='hidden' id='region-id{$id}' name='regionId' value='{$id}'>";

                $barResult = runSqlQuery("
                        SELECT name
                        FROM Bar
                            INNER JOIN
                            Region_relation ON Region_relation.region_id = {$id}
                        WHERE Bar.id = Region_relation.bar_id
                        ");

            if ($barResult->num_rows > 0) {
                while($row = $barResult->fetch_assoc()) {
                        echo "<div class='col'><input type='text' value='{$row['name']}' style='width: 100%;'readonly></input> </div>";
                    }
                }
            echo "<div class='w-100'></div>

                </div>";
        }
    }
echo "</div>";
echo "<button id='update-hints' class='btn btn-primary' onclick='DeleteRegions()'> Poista alueet</button>
            <script>
            $( document ).ready(function() {
            colorCode();
            });
                
            </script>";
