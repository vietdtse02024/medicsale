<?php include 'config.php';?>
<?php
$isPutAll = true;
$sql = "select ID, CustomerName from Customer where ActiveFlg = 1 ";
$result = $conn->query($sql);
$array = array();

if ($isPutAll) {
	$object = new stdClass;
	$object->id=-1;
    $object->value="Tất cả";
	array_push($array, $object);
}
while($row = $result->fetch_assoc())
{
	$object = new stdClass;
	$object->id=$row["ID"];
    $object->value=$row["CustomerName"];
	array_push($array, $object);
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