<?php include 'config.php';?>
<?php
$productId = isset($_GET['productId']) ? (int) $_GET['productId'] : null;
$sql = "SELECT Product.ID,ProductCode ,Serial, ProductName ,Country.CountryName, Product.CountryID, Product.ProducterID, Product.Supplier AS 'SupplierID', Product.GroupID,
			producter.ProducterName as 'Producter',ProductGroup.ProductGroupName as 'ProductGroup', Supplier.SupplierName as 'Supplier' 
			,ImportPrice, ExportPrice, Descriptions, Image, IFNULL(storagemanagement.Quantity, 0) AS 'Quantity' 
			FROM Product LEFT JOIN Country ON Product.CountryID = Country.ID 
			LEFT JOIN ProductGroup ON Product.GroupID = ProductGroup.ID 
			LEFT JOIN Producter ON Product.ProducterID = Producter.ID 
			LEFT JOIN Supplier ON Product.Supplier = Supplier.ID 
			LEFT JOIN storagemanagement ON Product.ID = storagemanagement.ProductID 
			WHERE Product.ActiveFlg=1 ";
if ($productId != null && $productId != -1) {
	$sql = $sql." AND Product.ID = ".$productId;
}
$sql = $sql." ORDER BY ProductName LIMIT 100";
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