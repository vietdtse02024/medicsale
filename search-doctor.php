<?php include 'config.php';?>
<?php
$doctorId = isset($_GET['doctorId']) ? (int) $_GET['doctorId'] : null;

$sql = "SELECT A.*, B.DistrictName, C.ProvinceName FROM Doctor A 
	LEFT JOIN district B ON A.DoctorDistrictID = B.ID 
	LEFT JOIN province C ON A.DoctorProvinceID = C.ID WHERE A.ActiveFlg = 1 ";
if ($doctorId != null && $doctorId != -1) {
	$sql = $sql." AND A.ID = ".$doctorId;
}
$sql = $sql." ORDER BY DoctorName";
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