<?php include 'config.php';?>
<?php
$jsonData = isset($_POST['mydata']) ? json_decode($_POST['mydata'], true) : null;
$password = isset($jsonData['password']) ? $jsonData['password'] : null;
$staffId = $jsonData['staffId'];
$userLogin = $jsonData['userLogin'];

$queryUpdate = "UPDATE `USER` SET PASSWORD = ?, USER_UPDATE = ?, DATE_UPDATE = NOW() WHERE ID = ?";
try {
	$conn->autocommit(FALSE);
	
	$stmt = $conn->prepare($queryUpdate);
	$stmt->bind_param("sii",$password, $userLogin, $staffId);
	$result = $stmt->execute();
	if ( !$result ) {
		throw new Exception($conn->error);
	}
	$stmt->close();
	$data = ["result" => "SUCCESS"];
	
	
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