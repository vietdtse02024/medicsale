<?php include '../config.php';?>
<?php
$jsonData = isset($_POST['mydata']) ? json_decode($_POST['mydata'], true) : null;
$rightName = $jsonData['roleName'];
$description = $jsonData['description'];
$roleId = $jsonData['roleId'];
$roleData = $jsonData['roleData'];

$queryInsertRight = "INSERT INTO `Right` (RightName, Description, ActiveFlg) VALUES (?,?,?)";
$queryUpdateRight = "UPDATE `Right` SET RightName = ?, Description = ? WHERE ID = ?";
$queryDeleteUserRight = "DELETE FROM `UserRight` WHERE RightID = ?";
$queryInsertUserRight = "INSERT INTO `UserRight` VALUES (?, ?)";
try {
	$activeFlg = 1;
    $conn->autocommit(FALSE); // i.e., start transaction
	
	if (empty($roleId)) {
		// prepare and bind
		$stmt = $conn->prepare($queryInsertRight);
		$stmt->bind_param("ssi",$rightName, $description, $activeFlg);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		$roleId = $stmt->insert_id;
	} else {
		
		$stmt = $conn->prepare($queryDeleteUserRight);
		$stmt->bind_param("i",$roleId);
		$result = $stmt->execute();
		if (!$result ) {
			throw new Exception($conn->error);
		}
		
		$stmt = $conn->prepare($queryUpdateRight);
		$stmt->bind_param("ssi",$rightName, $description, $roleId);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
	}
	
	$stmt = $conn->prepare($queryInsertUserRight);
	foreach ($roleData as $screenId) {
		$stmt->bind_param("is", $roleId, $screenId);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
	}
	
    $conn->commit();
    $conn->autocommit(TRUE);
	
	$data = ["result" => "SUCCESS"];
}
catch ( Exception $e ) {
	$data = ["result" => "ERROR"];
	
    // before rolling back the transaction, you'd want
    // to make sure that the exception was db-related
    $conn->rollback(); 
    $conn->autocommit(TRUE); // i.e., end transaction   
}
$stmt->close();
$conn->close();

// Finally, encode the array to JSON and output the results
echo json_encode($data);

?>