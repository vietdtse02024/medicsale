<?php include 'config.php';?>
<?php
$customerId = isset($_GET['customerId']) ? (int) $_GET['customerId'] : null;

$sql = "SELECT A.*, B.DistrictName, C.ProvinceName FROM Customer A 
	LEFT JOIN district B ON A.CustomerDistrictID = B.ID 
	LEFT JOIN province C ON A.CustomerProvinceID = C.ID WHERE A.ActiveFlg = 1 ";
if ($customerId != null && $customerId != -1) {
	$sql = $sql." AND A.ID = ".$customerId;
}
$sql = $sql." ORDER BY CustomerName";
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