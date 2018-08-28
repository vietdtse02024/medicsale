<?php include 'config.php';?>
<?php
$jsonData = isset($_POST['mydata']) ? json_decode($_POST['mydata'], true) : null;
$customerName = $jsonData['customerName'];
$phoneNo = $jsonData['phoneNo'];
$email = $jsonData['email'];
$province = $jsonData['province'];
$district = $jsonData['district'];
$address = $jsonData['address'];
$note = $jsonData['note'];
$customerId = $jsonData['customerId'];

$queryUpdate = "UPDATE Customer SET CustomerName = ?,CustomerAddress = ?,CustomerPhoneNo = ?,CustomerEmail = ?,CustomerProvinceID = ?, CustomerDistrictID = ?, CustomerDescription = ? WHERE ID = ?";
$queryInsert = "INSERT INTO Customer(CustomerName, CustomerAddress,CustomerPhoneNo, CustomerEmail,CustomerProvinceID, CustomerDistrictID,CustomerDescription, ActiveFlg) VALUES(?,?,?,?,?,?,?,1)";

try {
	$conn->autocommit(FALSE);
	if (empty($customerId)) {
		// prepare and bind
		$stmt = $conn->prepare($queryInsert);
		$stmt->bind_param("ssssiis",$customerName, $address, $phoneNo, $email, $province, $district, $note);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		$stmt->close();
		$data = ["result" => "SUCCESS"];
	} else {
		// prepare and bind
		$stmt = $conn->prepare($queryUpdate);
		$stmt->bind_param("ssssiisi",$customerName, $address, $phoneNo, $email, $province, $district, $note, $customerId);
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