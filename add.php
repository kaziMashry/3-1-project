<?php
	if(!isset($_COOKIE["pharm_code"])) 
		header('location: Home.php');
	
	$con = mysqli_connect('localhost', 'root', '1234');
	mysqli_select_db($con, '3_1_project');
	$mn = $_POST['mediName'];
	$mco = $_POST['mediComp'];
	
	$query = "CALL get_medi_code('$mn', '$mco')";
	$result = mysqli_query($con, $query);
	$numOfRows = mysqli_num_rows($result);
	
	if($numOfRows>0) {
		$pc = $_COOKIE['pharm_code'];
		$p = $_POST['price'];
		$q = $_POST['quantity'];
		$mc = 0;
		while($row = $result->fetch_assoc()) {
			$mc = $row['medi_code'];
			break;
		}
		
		$con = mysqli_connect('localhost', 'root', '1234');
		mysqli_select_db($con, '3_1_project');
		
		$status = 'BUY';
		$qry = "CALL make_transaction('$pc', '$mc', '$status', '$q', '$p')";
		$res = mysqli_query($con, $qry);

		$query1 = "CALL is_already_stored('$pc', '$mc')";
		$result1 = mysqli_query($con, $query1);
		$num = mysqli_num_rows($result1);
		echo $num;
		
		if($num>0) {
			while($row = $result1->fetch_assoc()) {
				$q += $row['quantity'];
			}
			$con = mysqli_connect('localhost', 'root', '1234');
			mysqli_select_db($con, '3_1_project');
			$query2 = "CALL update_medi_in_pharm('$pc', '$mc', '$q')";
			$result2 = mysqli_query($con, $query2);
		} else {
			$con = mysqli_connect('localhost', 'root', '1234');
			mysqli_select_db($con, '3_1_project');
			$query3 = "CALL insert_medi_in_pharm('$pc', '$mc', '$p', '$q')";
			$result3 = mysqli_query($con, $query3);
		}
	} else {
		header('location: medicine_not_found.php');
	}
	header('location: add_to_storage.php');
?>