<?php include '../config.php';?>
<?php
$jsonData = isset($_POST['mydata']) ? json_decode($_POST['mydata'], true) : null;
$groupId = $jsonData['groupId'];
$groupName = $jsonData['groupName'];
$note = $jsonData['note'];

$queryUpdate = "UPDATE ProductGroup SET ProductGroupName = ?, Description = ? WHERE id = ? ";
$queryInsert = "INSERT INTO ProductGroup (ProductGroupName, Description, ActiveFlg) VALUES (?,?,1) ";

try {
	$conn->autocommit(FALSE);
	if (empty($groupId)) {
		// prepare and bind
		$stmt = $conn->prepare($queryInsert);
		$stmt->bind_param("ss",$groupName, $note);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		$stmt->close();
		$data = ["result" => "SUCCESS"];
	} else {
		// prepare and bind
		$stmt = $conn->prepare($queryUpdate);
		$stmt->bind_param("ssi",$groupName, $note, $groupId);
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