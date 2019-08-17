<?php
    include("../connection.php");
    header('Content-Type: application/json');

    $lastmonth = date("Y-m", strtotime("first day of previous month"));
    $data = array();
    
    $query = mysqli_query($conn, "SELECT 5s_level FROM 5s_level");
    $result = mysqli_fetch_array($query);
    $s_level = $result['5s_level']; 

    if (isset($_REQUEST['onLoadDep'])) {
        if ($_REQUEST['onLoadDep'] == "") {
            if ($_REQUEST['firstDate'] && $_REQUEST['secondDate'] != '') {
                $firstDate = $_REQUEST['firstDate'];
                $secondDate = $_REQUEST['secondDate'];
                $query = mysqli_query($conn, "SELECT DISTINCT s_level FROM dashboard_data WHERE `date` BETWEEN '$firstDate' AND '$secondDate'");
                $result = mysqli_fetch_array($query);
                $s_level_second = $result['5s_level'];
                if ($s_level == $s_level_second) {
                    $query = mysqli_query($conn, "SELECT parent, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE (`date` BETWEEN '$firstDate' AND '$secondDate') GROUP BY parent ASC");
					$plant = mysqli_query($conn, "SELECT ROUND(AVG(note),2) AS value FROM dashboard_data WHERE (`date` BETWEEN '$firstDate' AND '$secondDate')");
                } elseif ($s_level_second == '') {
					$query = mysqli_query($conn, "SELECT parent, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE (`date` BETWEEN '$firstDate' AND '$secondDate') GROUP BY parent ASC");
					$plant = mysqli_query($conn, "SELECT ROUND(AVG(note),2) AS value FROM dashboard_data WHERE (`date` BETWEEN '$firstDate' AND '$secondDate')");
				} else {
                    $query = mysqli_query($conn, "SELECT parent, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE s_level = '$s_level' GROUP BY parent ASC");
					$plant = mysqli_query($conn, "SELECT ROUND(AVG(note),2) AS value FROM dashboard_data WHERE s_level = '$s_level'");
                }
                if ($firstDate == $secondDate) {
                    $query = mysqli_query($conn, "SELECT parent, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE `date` = '$firstDate' GROUP BY parent ASC");
					$plant = mysqli_query($conn, "SELECT ROUND(AVG(note),2) AS value FROM dashboard_data WHERE `date` = '$firstDate'");
                }
            } elseif ($_REQUEST['allTime'] != "") {
                $query = mysqli_query($conn, "SELECT parent, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE s_level = '$s_level' GROUP BY parent ASC");
				$plant = mysqli_query($conn, "SELECT ROUND(AVG(note),2) AS value FROM dashboard_data WHERE s_level = '$s_level'");
            } else {
                $query = mysqli_query($conn, "SELECT parent, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE `date` = '$lastmonth' GROUP BY parent ASC");
				$plant = mysqli_query($conn, "SELECT ROUND(AVG(note),2) AS value FROM dashboard_data WHERE `date` = '$lastmonth'");
            }
            
            foreach ($query as $row) {
                $data[] = $row;
            }
			foreach ($plant as $row) {
				if ($row['value']) {
					$row = [parent=>'WUAKM', value=>$row['value']];
					$data[] = $row;
				}
            }
        }
        if ($_REQUEST['onLoadDep'] != "") {
            $onLoadDep = $_REQUEST['onLoadDep'];
            if ($_REQUEST['firstDate'] && $_REQUEST['secondDate'] != '') {
                $firstDate = $_REQUEST['firstDate'];
                $secondDate = $_REQUEST['secondDate'];
                $query = mysqli_query($conn, "SELECT DISTINCT s_level FROM dashboard_data WHERE `date` BETWEEN '$firstDate' AND '$secondDate'");
                $result = mysqli_fetch_array($query);
                $s_level_second = $result['5s_level'];
                if ($s_level == $s_level_second) {
                    $query = mysqli_query($conn, "SELECT child, note, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE (`date` BETWEEN '$firstDate' AND '$secondDate') AND parent = '$onLoadDep' GROUP BY child");
                } elseif ($s_level_second == '') {
					$query = mysqli_query($conn, "SELECT child, note, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE (`date` BETWEEN '$firstDate' AND '$secondDate') AND parent = '$onLoadDep' GROUP BY child");
				} else {
                    $query = mysqli_query($conn, "SELECT child, note, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE s_level = '$s_level' AND parent = '$onLoadDep' GROUP BY child");
                }
                if ($firstDate == $secondDate) {
                    $query = mysqli_query($conn, "SELECT child, note, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE `date` = '$firstDate' AND parent = '$onLoadDep' GROUP BY child");
                }
            } elseif ($_REQUEST['allTime'] != "") {
                $query = mysqli_query($conn, "SELECT child, note, s_level, ROUND(AVG(note),2) AS value FROM dashboard_data WHERE parent = '$onLoadDep' AND s_level = '$s_level' GROUP BY child");
            } else {
                $query = mysqli_query($conn, "SELECT child, note, s_level FROM dashboard_data WHERE `date` = '$lastmonth' AND parent = '$onLoadDep'");
            }

            foreach ($query as $row) {
                $data[] = $row;
            }
        }
        echo json_encode($data);
    }
