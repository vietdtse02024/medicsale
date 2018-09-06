<?php include '../config.php';?>
<?php
$roleId = isset($_GET['roleId']) ? $_GET['roleId'] : null;
$queryUpdateRight = "DELETE FROM `Right` WHERE ID = ".$roleId;
$queryDeleteUserRight = "DELETE FROM `UserRight` WHERE RightID = ".$roleId;
try {

    $conn->autocommit(FALSE); // i.e., start transaction
    $result = $conn->query($queryUpdateRight);
    if ( !$result ) {
        $result->free();
        throw new Exception($conn->error);
    }
    $result = $conn->query($queryDeleteUserRight);
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