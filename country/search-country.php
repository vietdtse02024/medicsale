<?php include '../config.php';?>
<?php
$sql = "SELECT * FROM Country WHERE ActiveFlg = 1 ORDER BY ID DESC ";
$result = $conn->query($sql);
$array = array();
while($row = $result->fetch_assoc())
{
	array_push($array, $row);
}

// Finally, encode the array to JSON and output the results

$data = ["data" => $array, "type" => "DATA"];
echo json_encode($data, JSON_UNESCAPED_UNICODE);

$conn->close();
?>