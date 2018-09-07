<?php include '../config.php';?>
<?php
$jsonData = isset($_POST['mydata']) ? json_decode($_POST['mydata'], true) : null;
$unitId = $jsonData['unitId'];
$unitName = $jsonData['unitName'];
$unitExchange = $jsonData['unitExchange'];
$note = $jsonData['note'];

$queryUpdate = "UPDATE ProductUnit SET Name = ?, Exchange = ?, Description = ? WHERE ID = ? ";
$queryInsert = "INSERT INTO ProductUnit (Name, Exchange, Description,ActiveFlg) VALUES (?,?,?,1) ";

try {
	$conn->autocommit(FALSE);
	if (empty($unitId)) {
		// prepare and bind
		$stmt = $conn->prepare($queryInsert);
		$stmt->bind_param("sis",$unitName, $unitExchange, $note);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		$stmt->close();
		$data = ["result" => "SUCCESS"];
	} else {
		// prepare and bind
		$stmt = $conn->prepare($queryUpdate);
		$stmt->bind_param("sisi",$unitName, $unitExchange, $note, $unitId);
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