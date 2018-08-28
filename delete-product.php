<?php include 'config.php';?>
<?php
$productId = isset($_GET['productId']) ? $_GET['productId'] : null;
$queryUpdate = "UPDATE Product SET ActiveFlg = 0 WHERE ID = ".$productId;
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