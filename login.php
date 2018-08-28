<?php include 'config.php';?>
<?php
$userName = isset($_POST['userName']) ? $_POST['userName'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$sql = "select * from user where ActiveFlg = 1 and USER_NAME = '".$userName."' and password = '".$password."'";
$result = $conn->query($sql);

$array = array();

while($row = $result->fetch_assoc())
{
	array_push($array, $row);
}

// Finally, encode the array to JSON and output the results
if (!empty($array)) {
    $data = ["data" => $array, "result" => "SUCCESS"];
} else {
	$data = ["result" => "ERROR"];
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

$conn->close();
?>