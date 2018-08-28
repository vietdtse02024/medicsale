<?php include 'config.php';?>
<?php
$key = isset($_GET['columnKey']) ? $_GET['columnKey'] : null;
$value = isset($_GET['columnValue']) ? $_GET['columnValue'] : null;
$tableName = isset($_GET['tableName']) ? $_GET['tableName'] : null;

$sql = "select ".$key.", ".$value." from `".$tableName."` where ActiveFlg = 1 ";

$result = $conn->query($sql);
$array = array();
$object = new stdClass;

while($row = $result->fetch_assoc())
{
	$object = new stdClass;
	$object->name=$row[$value];
    $object->value=$row[$key];
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