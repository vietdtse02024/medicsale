<?php include 'config.php';?>
<?php
$sql = "Select A.*, B.RightName from `User` A INNER JOIN `Right` B ON A.RIGHT_ID = B.ID WHERE A.ActiveFlg = 1 ";
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