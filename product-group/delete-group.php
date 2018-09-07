<?php include '../config.php';?>
<?php
$groupId = isset($_GET['groupId']) ? $_GET['groupId'] : null;
$queryUpdate = "UPDATE ProductGroup SET ActiveFlg = 0 WHERE ID = ".$groupId;
try {

    $conn->autocommit(FALSE); // i.e., start transaction
    $result = $conn->query($queryUpdate);
    if ( !$result ) {
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