<?php 
    include("../connection.php");

    if (isset($_REQUEST['variable'])) {
        $variable = $_REQUEST['variable'];
        
        echo '<table class="table table-striped table-bordered text-center table-hover">
            <tr>
                <th class="text-center">Відділ</th>
                <th class="text-center">Дочірній елемент</th>
                <th class="text-center">Оцінка</th>';
                    $query = mysqli_query($conn, "SELECT child, note, parent FROM dashboard_data WHERE `date` = '$variable' ORDER BY parent ASC");
                    while($row = mysqli_fetch_assoc($query)) {
                        echo "<tr><td>" . $row['parent'] . "</td><td>" . $row['child'] . "</td><td>" . $row['note'] . "</td>";
                    }
                    
                    $query = mysqli_query($conn, "SELECT child, parent FROM org_units WHERE child NOT IN (( SELECT element FROM result WHERE `current_date` = '$variable')) ORDER BY parent ASC");
                    while($row = mysqli_fetch_assoc($query)) {
                        echo "<tr style='background: tomato; color: white;'><td>" . $row['parent'] . "</td><td>" . $row['child'] . "</td><td>" . " - " . "</td>";
                    }
            echo '</tbody></tr></table><br><hr><button class="btn btn-success float-right" id="export">Завантажити Excel</button>';
    }
