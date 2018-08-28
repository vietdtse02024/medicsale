<?php include 'config.php';?>
<?php
$jsonData = isset($_POST['mydata']) ? json_decode($_POST['mydata'], true) : null;
$doctorName = $jsonData['doctorName'];
$phoneNo = $jsonData['phoneNo'];
$email = $jsonData['email'];
$province = $jsonData['province'];
$district = $jsonData['district'];
$address = $jsonData['address'];
$note = $jsonData['note'];
$doctorId = $jsonData['doctorId'];

$queryUpdate = "UPDATE doctor SET doctorName = ?,doctorAddress = ?,doctorPhoneNo = ?,doctorEmail = ?,doctorProvinceID = ?, doctorDistrictID = ?, doctorDescription = ? WHERE ID = ?";
$queryInsert = "INSERT INTO doctor(doctorName, doctorAddress,doctorPhoneNo, doctorEmail,doctorProvinceID, doctorDistrictID,doctorDescription, ActiveFlg) VALUES(?,?,?,?,?,?,?,1)";

try {
	$conn->autocommit(FALSE);
	if (empty($doctorId)) {
		// prepare and bind
		$stmt = $conn->prepare($queryInsert);
		$stmt->bind_param("ssssiis",$doctorName, $address, $phoneNo, $email, $province, $district, $note);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		$stmt->close();
		$data = ["result" => "SUCCESS"];
	} else {
		// prepare and bind
		$stmt = $conn->prepare($queryUpdate);
		$stmt->bind_param("ssssiisi",$doctorName, $address, $phoneNo, $email, $province, $district, $note, $doctorId);
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