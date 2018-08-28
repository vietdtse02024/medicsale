<?php include 'config.php';?>
<?php
$jsonData = isset($_POST['mydata']) ? json_decode($_POST['mydata'], true) : null;
$userRight = $jsonData['userRight'];
$description = $jsonData['description'];
$fullName = $jsonData['fullName'];
$staffId = $jsonData['staffId'];
$userName = $jsonData['userName'];
$userLogin = $jsonData['userLogin'];
$password = isset($jsonData['password']) ? $jsonData['password'] : null;

$queryUpdate = "UPDATE `USER` SET FULL_NAME = ?, RIGHT_ID = ?, DESCRIPTION = ?, USER_UPDATE = ?, DATE_UPDATE = NOW() WHERE ID = ?";
$queryInsert = "INSERT INTO `User` (USER_NAME, PASSWORD, DESCRIPTION, RIGHT_ID, DATE_INSERT, DATE_UPDATE, FULL_NAME, USER_INSERT, ActiveFlg) VALUES (?, ?, ?, ?, NOW(), NOW(), ?, ?, 1) ";
$querySelect = "SELECT * FROM `User` WHERE USER_NAME = '".$userName."' AND ActiveFlg = 1 ";
try {
	$conn->autocommit(FALSE);
	if (empty($staffId)) {
		$resultSelect = $conn->query($querySelect);
		if ($resultSelect->num_rows === 0) {
			// prepare and bind
			$stmt = $conn->prepare($queryInsert);
			$stmt->bind_param("sssisi",$userName, $password, $description, $userRight, $fullName, $userLogin);
			$result = $stmt->execute();
			if ( !$result ) {
				throw new Exception($conn->error);
			}
			$stmt->close();
			$data = ["result" => "SUCCESS"];
		} else {
			$data = ["result" => "ERROR", "message" => "Tên đăng nhập đã tồn tại"];
		}
		
	} else {
		// prepare and bind
		$stmt = $conn->prepare($queryUpdate);
		$stmt->bind_param("sisii",$fullName, $userRight, $description, $userLogin, $staffId);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		$stmt->close();
		$data = ["result" => "SUCCESS"];
	}
	
	
    $conn->commit();
    $conn->autocommit(TRUE);
}
catch ( Exception $e ) {
	$data = ["result" => "ERROR: ".$e];
	
    // before rolling back the transaction, you'd want
    // to make sure that the exception was db-related
    $conn->rollback(); 
    $conn->autocommit(TRUE); // i.e., end transaction   
}

$conn->close();

// Finally, encode the array to JSON and output the results
echo json_encode($data);

?>