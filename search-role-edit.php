<?php include 'config.php';?>
<?php
$roleId = isset($_GET['roleId']) ? $_GET['roleId'] : null;
$sql = "SELECT A.RightName, A.Description, B.ScreenID FROM `Right` A LEFT JOIN UserRight B ON A.ID = B.RightID WHERE A.ActiveFlg = 1 AND A.ID = ".$roleId;

$result = $conn->query($sql);
$array = array();
while($row = $result->fetch_assoc())
{
	array_push($array, $row);
}

// Finally, encode the array to JSON and output the results

if (!empty($array)) {
    $data = ["data" => $array, "type" => "DATA"];
} else {
	$data = ["result" => "ERROR"];
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

$conn->close();
?>