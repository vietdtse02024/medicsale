<?php include '../config.php';?>
<?php
$productId = isset($_GET['productId']) ? (int) $_GET['productId'] : null;
$sql = "SELECT wh.InputDate, wh.BillCode, p.ProductName, wh.Quantity, 
            wh.ExpDate, us.FULL_NAME, wh.Note, pu.Name as UnitName, wh.InputPrice 
            FROM WareHouse wh INNER JOIN Product p ON wh.ProductId = p.ID 
            INNER JOIN `USER` us ON wh.UserInput = us.ID 
            INNER JOIN ProductUnit pu ON pu.ID = wh.ProductUnit  WHERE ServiceType = 1    ";
if ($productId != null && $productId != -1) {
	$sql = $sql." wh.ProductId = ".$productId;
}
$sql = $sql." ORDER BY wh.InputDate DESC";
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