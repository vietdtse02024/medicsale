<?php include '../config.php';?>
<?php
$storeName = isset($_POST['storeName']) ? $_POST['storeName'] : null;
$storeAddress = isset($_POST['storeAddress']) ? $_POST['storeAddress'] : null;
$phoneNo = isset($_POST['phoneNo']) ? $_POST['phoneNo'] : null;
$commission = isset($_POST['commission']) ? $_POST['commission'] : null;
$sql = "UPDATE setting_med SET STORE_NAME = '".$storeName."', STORE_ADDRESS = '".$storeAddress."', PHONE = '".$phoneNo."', COMMISSION = ".$commission;

if ($conn->query($sql) === TRUE) {
    $data = ["result" => "SUCCESS"];
} else {
    $data = ["result" => "ERROR"];
}

// Finally, encode the array to JSON and output the results
echo json_encode($data);

$conn->close();
?>