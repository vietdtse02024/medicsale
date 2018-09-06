<?php include '../config.php';?>
<?php
$doctorId = isset($_GET['doctorId']) ? $_GET['doctorId'] : null;
$queryUpdate = "UPDATE Doctor SET ActiveFlg = 0 WHere ID = ".$doctorId;
try {

    $conn->autocommit(FALSE); // i.e., start transaction
    $result = $conn->query($queryUpdate);
    if ( !$result ) {
        $result->free();
        throw new Exception($conn->error);
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

// Finally, encode the array to JSON and output the results
echo json_encode($data);

$conn->close();
?>