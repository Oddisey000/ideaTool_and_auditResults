<?php
	session_start();
	require('includes/header.php');
	require('includes/connection.php');
	$loggedInUser = $_SESSION['loggedInUser'];
	$query 	  = mysqli_query($conn, "SELECT segment FROM users WHERE login = '$loggedInUser'");
	$parent_2 = mysqli_fetch_assoc($query);
	$parent_2 = $parent_2['segment'];
	$query = mysqli_query($conn, "SELECT status FROM users WHERE login = '$loggedInUser'");
	$loggedInUser = mysqli_fetch_assoc($query);
	$loggedInUser = $loggedInUser['status'];
	$note_values = array(1,2,3,4,5);

	$sql = "SELECT * FROM 5s_level";
	$res = mysqli_query($conn, $sql);
	$temp = mysqli_fetch_array($res);
	$substracted = substr($temp['5s_level'], -2, 1);
	//Receive data
	if (isset($_REQUEST['element'])) {
		$element = $_REQUEST['element'];
		$date = $_REQUEST['date'];
		$dashboard = $_REQUEST['dashboard'];
		
		$query 	  = mysqli_query($conn, "SELECT parent FROM dashboard_data WHERE child = '$element'");
		$parent_1 = mysqli_fetch_assoc($query);
		$parent_1 = $parent_1['parent'];
	}
	//Find wich questionnaire need to retreive
	$query = mysqli_query($conn, "SELECT questionnaire FROM org_units WHERE child = '$element'");
	$temp = mysqli_fetch_array($query);
	$questionnaire = $temp['questionnaire'];
	//Retrieving data from name of the questionnaire and asign it to $qst
	if ($questionnaire == "Стандарт") {
		$qst = "офісу & продукції";
	} elseif ($questionnaire == "Офіс") {
		$qst = "Офісу";
	} elseif ($questionnaire == "Продукція") {
		$qst = "Продукції";
	} elseif ($questionnaire == "Автопарк") {
		$qst = "Автопарку";
	} elseif ($questionnaire == "Склад") {
		$qst = "Складу";
	} elseif ($questionnaire == "Технічна дільниця") {
		$qst = "Технічної дільниці";
	} elseif ($questionnaire == "Нарадчі кімнати") {
		$qst = "Нарадчих кімнат";
	} elseif ($questionnaire == "Кухня") {
		$qst = "Кухонь";
	} 
	// count number of questions and use this data for future calculation

	//Retrive all data related to First S
	$query_first_s = mysqli_query($conn, "SELECT id, body FROM questionnaire WHERE element = '1S' AND type = '$questionnaire'");
	$num_rows_first_s = mysqli_num_rows($query_first_s);
	//Retrive all data related to Second S
	$query_second_s = mysqli_query($conn, "SELECT id, body FROM questionnaire WHERE element = '2S' AND type = '$questionnaire'");
	$num_rows_second_s = mysqli_num_rows($query_second_s);
	//Retrive all data related to Third S
	$query_third_s = mysqli_query($conn, "SELECT id, body FROM questionnaire WHERE element = '3S' AND type = '$questionnaire'");
	$num_rows_third_s = mysqli_num_rows($query_third_s);
	//Retrive all data related to Fourth S
	$query_fourth_s = mysqli_query($conn, "SELECT id, body FROM questionnaire WHERE element = '4S' AND type = '$questionnaire'");
	$num_rows_fourth_s = mysqli_num_rows($query_fourth_s);
	//Retrive all data related to Fifth S
	$query_fifth_s = mysqli_query($conn, "SELECT id, body FROM questionnaire WHERE element = '5S' AND type = '$questionnaire'");
	$num_rows_fifth_s = mysqli_num_rows($query_fifth_s);
	
	//Checks if questionnaire is already complete for current mounth
	$checkData = mysqli_query($conn, "SELECT DISTINCT element FROM result WHERE `current_date` = '$date' AND element = '$element'");			
	if (mysqli_num_rows($checkData) > 0) {
		$queryData_first_s = mysqli_query($conn, "SELECT * FROM result WHERE `current_date` = '$date' AND element = '$element' AND S = '1S'");
		$num_rows_data_first_s = mysqli_num_rows($queryData_first_s);
		$queryData_second_s = mysqli_query($conn, "SELECT * FROM result WHERE `current_date` = '$date' AND element = '$element' AND S = '2S'");
		$num_rows_data_second_s = mysqli_num_rows($queryData_second_s);
		$queryData_third_s = mysqli_query($conn, "SELECT * FROM result WHERE `current_date` = '$date' AND element = '$element' AND S = '3S'");
		$num_rows_data_third_s = mysqli_num_rows($queryData_third_s);
		$queryData_fourth_s = mysqli_query($conn, "SELECT * FROM result WHERE `current_date` = '$date' AND element = '$element' AND S = '4S'");
		$num_rows_data_fourth_s = mysqli_num_rows($queryData_fourth_s);
		$queryData_fifth_s = mysqli_query($conn, "SELECT * FROM result WHERE `current_date` = '$date' AND element = '$element' AND S = '5S'");
		$num_rows_data_fifth_s = mysqli_num_rows($queryData_fifth_s);
		
		$people = mysqli_query($conn, "SELECT people FROM dashboard_data WHERE `date` = '$date' AND child = '$element'");
		$people = mysqli_fetch_assoc($people);
		$people = $people['people'];
	}

?>
<script src="../libs/jquery/jquery-1.12.4.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/edit_results.js"></script>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">5S опитувальник для <?php echo $qst; ?></h1>
	</div><br>
	<span id="alert_message"></span>
	<form action="" method="POST">
		<table class="table text-center content">
			<thead class="thead-light">
			<tr>
				<th class="text-center" colspan="2">Date</th>
				<th class="text-center">Work Area:</th>
				<th class="text-center" colspan="2">5S Level</th>
				<th class="text-center">Performed by:</th>
			</tr>
		</thead>
			<tr id="QcollorWhite">
            <td class="text-center">5S</td>
				<td class="text-center">№</td>
				<td class="text-center" style="width: 60%; min-width: 350px;">Опис</td>
				<td class="text-center" style="width: 5%; min-width: 90px;">Оцінка</td>
				<td class="text-center">N/A</td>
				<td class="text-center" style="width: 20%; min-width: 300px;">Comments</td>
			</tr>
			<?php
				if ($substracted > 0) {
					$count = 1;
					if (mysqli_num_rows($checkData) > 0) {
						echo '<tr>
							<td class="text-center transform" rowspan="' . $num_rows_data_first_s . '" style="vertical-align: middle; font-size: 18px; font-style: italic;">Сортування</td>';
					while ($row = mysqli_fetch_array($queryData_first_s)) {
						echo '
							<td class="text-center"><ins>' . $count++ . '.</ins></td>
								<td class="text-left">' . $row['question'] . '</td>';
								if ($row['note'] == 'yes') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select required class="form-control sum-oneS oneS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_first_s . '">
												<option selected>' . $row['note_value'] . '</option>';
												foreach ($note_values as $i) {
													if ($i != $row['note_value']) {
														echo '<option>' . $i . '</option>';
													}
												}
											echo '</select>
										</td>
											<td><input style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_first_s . '" val="' . $row['id'] . '" class="note_checkbox_one note_no_oneS oneS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
											
											$note_counted += count($row['note_value']);
											$note_summed += ($row['note_value']);
											$note = $note_summed / $note_counted;
											$note = round($note, 1);
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option>' . $row['note_value'] . '</option>
											</select>
										</td>';
											echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);"disabled></td>';
											
											$note_counted += count($row['note_value']);
											$note_summed += ($row['note_value']);
											$note = $note_summed / $note_counted;
											$note = round($note, 1);
									}
								} elseif ($row['note'] == 'no') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select disabled required class="form-control sum-oneS oneS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_first_s . '">
												 <option selected></option>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</td>
											<td><input checked style="-ms-transform: scale(1.5); cursor: pointer;" data="' . $row['id'] . '" type="checkbox" num_rows="' . $num_rows_first_s . '" val="' . $row['id'] . '" class="note_checkbox_one note_no_oneS oneS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option></option>
											</select>
										</td>';
											echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);" disabled checked></td>';
									}	
								}
								if ($loggedInUser == 1) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 == $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 != $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea disabled id="' . $row['id'] . '" class="text-left form-control input-sm" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								}
					}
					if ($loggedInUser == 1) {
						echo '<tr id="QcollorGreen">
							<td class="text-right" colspan="3"><strong><big>Підсумок 1S</big></strong></td>
							<td id="firstS_results" class="results">';
								echo $note;
						echo '</td>
							<td colspan="2"></td>
						</tr>';
					} 	else {
							echo '<tr id="QcollorGreen">
								<td class="text-right sumOfNotes" colspan="3"><strong><big>Підсумок 1S</big></strong></td>';
									echo '<td>' . $note . '</td><td colspan="2"></td></tr>';
						}
					} 
				}
				if ($substracted >= 2) {
					$count = 1;
					if (mysqli_num_rows($checkData) > 0) {
						if (mysqli_num_rows($queryData_second_s) > 0) {
							echo '<tr>
							<td class="text-center transform" rowspan="' . $num_rows_data_second_s . '" style="vertical-align: middle; font-size: 18px; font-style: italic;">Раціональне розташування</td>';
						}
					while ($row = mysqli_fetch_array($queryData_second_s)) {
						echo '
							<td class="text-center"><ins>' . $count++ . '.</ins></td>
								<td class="text-left">' . $row['question'] . '</td>';
								if ($row['note'] == 'yes') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select required class="form-control sum-twoS twoS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_data_second_s . '">
												<option selected>' . $row['note_value'] . '</option>';
												foreach ($note_values as $i) {
													if ($i != $row['note_value']) {
														echo '<option>' . $i . '</option>';
													}
												}
											echo '</select>
										</td>
											<td><input style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_data_second_s . '" val="' . $row['id'] . '" class="note_checkbox_two note_no_twoS twoS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
											
											$note_counted_2 += count($row['note_value']);
											$note_summed_2 += ($row['note_value']);
											$note_2 = $note_summed_2 / $note_counted_2;
											$note_2 = round($note_2, 1);
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option>' . $row['note_value'] . '</option>
											</select>
										</td>';
											echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);"disabled></td>';
											
											$note_counted_2 += count($row['note_value']);
											$note_summed_2 += ($row['note_value']);
											$note_2 = $note_summed_2 / $note_counted_2;
											$note_2 = round($note_2, 1);
									}
								} elseif ($row['note'] == 'no') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select disabled required class="form-control sum-twoS twoS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_data_second_s . '">
												 <option selected></option>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</td>
											<td><input checked style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_data_second_s . '" val="' . $row['id'] . '" class="note_checkbox_two note_no_twoS twoS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option></option>
											</select>
										</td>';
											echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);" disabled checked></td>';	
									}	
								}
								if ($loggedInUser == 1) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 == $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 != $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea disabled id="' . $row['id'] . '" class="text-left form-control input-sm" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								}
					}
					if (mysqli_num_rows($queryData_second_s) > 0) {
						if ($loggedInUser == 1) {
							echo '<tr id="QcollorYellow">
								<td class="text-right" colspan="3"><strong><big>Підсумок 2S</big></strong></td>
								<td id="secondS_results" class="results">';
									echo $note_2;
							echo '</td>
								<td colspan="2"></td>
							</tr>';
						} 	else {
								echo '<tr id="QcollorYellow">
								<td class="text-right sumOfNotes" colspan="3"><strong><big>Підсумок 2S</big></strong></td>';
									echo '<td>' . $note_2 . '</td><td colspan="2"></td></tr>';
							}
						}
					}
				}
				if ($substracted >= 3) {
					$count = 1;
					if (mysqli_num_rows($checkData) > 0) {
						if (mysqli_num_rows($queryData_third_s) > 0) {
							echo '<tr>
							<td class="text-center transform" rowspan="' . $num_rows_data_third_s . '" style="vertical-align: middle; font-size: 18px; font-style: italic;">Прибирання</td>';
						}
					while ($row = mysqli_fetch_array($queryData_third_s)) {
						echo '
							<td class="text-center"><ins>' . $count++ . '.</ins></td>
								<td class="text-left">' . $row['question'] . '</td>';
								if ($row['note'] == 'yes') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select required class="form-control sum-threeS threeS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_data_third_s . '">
												<option selected>' . $row['note_value'] . '</option>';
												foreach ($note_values as $i) {
													if ($i != $row['note_value']) {
														echo '<option>' . $i . '</option>';
													}
												}
											echo '</select>
										</td>
											<td><input style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_data_third_s . '" val="' . $row['id'] . '" class="note_checkbox_three note_no_threeS threeS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
											
											$note_counted_3 += count($row['note_value']);
											$note_summed_3 += ($row['note_value']);
											$note_3 = $note_summed_3 / $note_counted_3;
											$note_3 = round($note_3, 1);
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option>' . $row['note_value'] . '</option>
											</select>
										</td>';
											echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);"disabled></td>';
											
											$note_counted_3 += count($row['note_value']);
											$note_summed_3 += ($row['note_value']);
											$note_3 = $note_summed_3 / $note_counted_3;
											$note_3 = round($note_3, 1);
									}
								} elseif ($row['note'] == 'no') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select disabled required class="form-control sum-threeS threeS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_data_third_s . '">
												 <option selected></option>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</td>
											<td><input checked style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_data_third_s . '" val="' . $row['id'] . '" class="note_checkbox_three note_no_threeS threeS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option></option>
											</select>
										</td>';
										echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);" disabled checked></td>';
									}	
								}
								if ($loggedInUser == 1) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 == $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 != $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea disabled id="' . $row['id'] . '" class="text-left form-control input-sm" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								}
					}
					if (mysqli_num_rows($queryData_third_s) > 0) {
						if ($loggedInUser == 1) {
							echo '<tr id="QcollorBlue">
								<td class="text-right" colspan="3"><strong><big>Підсумок 3S</big></strong></td>
								<td id="thirdS_results" class="results">';
									echo $note_3;
							echo '</td>
								<td colspan="2"></td>
							</tr>';
						} 	else {
								echo '<tr id="QcollorBlue">
								<td class="text-right sumOfNotes" colspan="3"><strong><big>Підсумок 3S</big></strong></td>';
									echo '<td>' . $note_3 . '</td><td colspan="2"></td></tr>';
							}
						}
					} 
				}
				if ($substracted >= 4) {
					$count = 1;
					if (mysqli_num_rows($checkData) > 0) {
						if (mysqli_num_rows($queryData_fourth_s) > 0) {
							echo '<tr>
							<td class="text-center transform" rowspan="' . $num_rows_data_fourth_s . '" style="vertical-align: middle; font-size: 18px; font-style: italic;">Стандартизація</td>';
						}
					while ($row = mysqli_fetch_array($queryData_fourth_s)) {
						echo '
							<td class="text-center"><ins>' . $count++ . '.</ins></td>
								<td class="text-left">' . $row['question'] . '</td>';
								if ($row['note'] == 'yes') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select required class="form-control sum-fourS fourS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_data_fourth_s . '">
												<option selected>' . $row['note_value'] . '</option>';
												foreach ($note_values as $i) {
													if ($i != $row['note_value']) {
														echo '<option>' . $i . '</option>';
													}
												}
											echo '</select>
										</td>
											<td><input style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_data_fourth_s . '" val="' . $row['id'] . '" class="note_checkbox_four note_no_fourS fourS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
											
											$note_counted_4 += count($row['note_value']);
											$note_summed_4 += ($row['note_value']);
											$note_4 = $note_summed_4 / $note_counted_4;
											$note_4 = round($note_4, 1);
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option>' . $row['note_value'] . '</option>
											</select>
										</td>';
											echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);"disabled></td>';
											
											$note_counted_4 += count($row['note_value']);
											$note_summed_4 += ($row['note_value']);
											$note_4 = $note_summed_4 / $note_counted_4;
											$note_4 = round($note_4, 1);
									}
								} elseif ($row['note'] == 'no') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select disabled required class="form-control sum-fourS fourS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_data_fourth_s . '">
												 <option selected></option>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</td>
											<td><input checked style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_data_fourth_s . '" val="' . $row['id'] . '" class="note_checkbox_four note_no_fourS fourS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option></option>
											</select>
										</td>';
										echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);" disabled checked></td>';
									}
								}
								if ($loggedInUser == 1) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 == $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 != $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea disabled id="' . $row['id'] . '" class="text-left form-control input-sm" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								}
					}
					if (mysqli_num_rows($queryData_fourth_s) > 0) {
						if ($loggedInUser == 1) {
							echo '<tr id="QcollorLightGreen">
								<td class="text-right" colspan="3"><strong><big>Підсумок 4S</big></strong></td>
								<td id="fourthS_results" class="results">';
									echo $note_4;
							echo '</td>
								<td colspan="2"></td>
							</tr>';
						}
						echo '<tr id="QcollorLightGreen">
							<td class="text-right sumOfNotes" colspan="3"><strong><big>Підсумок 4S</big></strong></td>';
								echo '<td>' . $note_4 . '</td><td colspan="2"></td></tr>';
						}
					} 
				}
				if ($substracted >= 5) {
					$count = 1;
					if (mysqli_num_rows($checkData) > 0) {
						if (mysqli_num_rows($queryData_fifth_s) > 0) {
							echo '<tr>
							<td class="text-center transform" rowspan="' . $num_rows_data_fifth_s . '" style="vertical-align: middle; font-size: 18px; font-style: italic;">Вдосконалення</td>';
						}
					while ($row = mysqli_fetch_array($queryData_fifth_s)) {
						echo '
							<td class="text-center"><ins>' . $count++ . '.</ins></td>
								<td class="text-left">' . $row['question'] . '</td>';
								if ($row['note'] == 'yes') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select required class="form-control sum-fifthS fifthS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_data_fifth_s . '">
												<option selected>' . $row['note_value'] . '</option>';
												foreach ($note_values as $i) {
													if ($i != $row['note_value']) {
														echo '<option>' . $i . '</option>';
													}
												}
											echo '</select>
										</td>
											<td><input style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_data_fifth_s . '" val="' . $row['id'] . '" class="note_checkbox_fifth note_no_fifthS fifthS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
											
											$note_counted_5 += count($row['note_value']);
											$note_summed_5 += ($row['note_value']);
											$note_5 = $note_summed_5 / $note_counted_5;
											$note_5 = round($note_5, 1);
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option>' . $row['note_value'] . '</option>
											</select>
										</td>';
											echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);"disabled></td>';
											
											$note_counted_5 += count($row['note_value']);
											$note_summed_5 += ($row['note_value']);
											$note_5 = $note_summed_5 / $note_counted_5;
											$note_5 = round($note_5, 1);
									}
								} elseif ($row['note'] == 'no') {
									if ($loggedInUser == 1) {
										echo '<td>
											<select disabled required class="form-control sum-fifthS fifthS_data note S_data" id="n' . $row['id'] . '" data="' . $row['id'] . '" num_rows="' . $num_rows_data_fifth_s . '">
												 <option selected></option>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</td>
											<td><input checked style="-ms-transform: scale(1.5); cursor: pointer;" type="checkbox" data="' . $row['id'] . '" num_rows="' . $num_rows_data_fifth_s . '" val="' . $row['id'] . '" class="note_checkbox_fifth note_no_fifthS fifthS S_data" style="background: rgba(0, 0, 0, 0)"></td>';
									} else {
										echo '<td>
											<select required class="form-control"disabled>
												<option></option>
											</select>
										</td>';
										echo '<td><input type="checkbox" style="-ms-transform: scale(1.5);" disabled checked></td>';
									}	
								}
								if ($loggedInUser == 1) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea id="t' . $row['id'] . '" data="' . $row['id'] . '" class="text-left form-control input-sm S_data" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 == $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea placeholder="Опис зарадчих заходів" id="' . $row['id'] . '" class="text-left form-control input-sm actionPlan" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								} elseif ($parent_1 != $parent_2) {
									if ($row['note_value'] < 5 && $row['note'] != 'no') {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea><br><p style="color: tomato;">Зарадча дія</p><textarea disabled id="' . $row['id'] . '" class="text-left form-control input-sm" style="width: 105%; height: 60px; font-size: 12px;">' . $row['action'] . '</textarea></td></tr>';
									} else {
										echo '<td><textarea disabled class="text-left form-control input-sm" style="width: 105%; height: 50px; font-size: 12px;">' . $row['comment'] . '</textarea></td></tr>';
									}
								}
					}
					if (mysqli_num_rows($queryData_fifth_s) > 0) {
						if ($loggedInUser == 1) {
							echo '<tr id="QcollorLightRed">
								<td class="text-right" colspan="3"><strong><big>Підсумок 5S</big></strong></td>
								<td id="fifthS_results" class="results">';
									echo $note_5;
							echo '</td>
								<td colspan="2"></td>
							</tr>';
						}
						echo '<tr id="QcollorLightRed">
							<td class="text-right sumOfNotes" colspan="3"><strong><big>Підсумок 5S</big></strong></td>';
								echo '<td>' . $note_5 . '</td><td colspan="2"></td></tr>';
						}
					} 
				}
				if (mysqli_num_rows($checkData) > 0) {
					$note_res = $note + $note_2 + $note_3 + $note_4 + $note_5;
					if ($loggedInUser == 1) {
						echo '<tr id="QcollorWhite">
								<td colspan="6"><textarea class="text-left form-control input-sm" id="people" style="height: 50px; font-size: 14px;">' . $people . '</textarea></td>
							</tr>
						<tr id="QcollorWhite">
							<td class="text-right" colspan="3" rowspan="2"><strong><big><i>Результат 5S аудиту</i></big></strong></td>
							<td rowspan="2" id="Results_data">' . $note_res . '</td>
							<td colspan="2" rowspan="2"><button type="submit" class="btn btn-success btn-lg float-right" id="backToStart" name="submit">Повернутися</button></td>
						</tr>';
					} else {
						echo '<tr id="QcollorWhite">
								<td colspan="6"><textarea class="text-left form-control input-sm" disabled style="height: 50px; font-size: 14px;">' . $people . '</textarea></td>
							</tr>
						<tr id="QcollorWhite">
							<td class="text-right" colspan="3" rowspan="2"><strong><big><i>Результат 5S аудиту</i></big></strong></td>
							<td rowspan="2" id="Results_data">' . $note_res . '</td>
							<td colspan="2" rowspan="2"><button type="submit" class="btn btn-success btn-lg float-right" id="backToStart" name="submit">Повернутися</button></td>
						</tr>';
					}
				}
			?>
		</table>
		</form>
</main>
<div id="element"><?php echo $element; ?></div>
<div id="date"><?php echo $date; ?></div>
<div id="dashboard"><?php echo $dashboard; ?></div>
<?php
	require('includes/footer.php');
	