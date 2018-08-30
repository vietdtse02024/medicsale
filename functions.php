<?php include 'config.php';?>
<?php

if(isset($_GET['function'])) {
	$functionName = $_GET['function'];
	if(strcmp($functionName, "ProductDataTableEdit") == 0) {
		$productId = isset($_GET['productId']) ? (int) $_GET['productId'] : null;
		getProductSellTypeData($productId);
	}
}

function getProductSellTypeData($productId) {
	$conn = $GLOBALS['conn'];
    $sellTypeArray = array();
	$productUnitArray = array();
	if ($productId != null) {
		$sql = "SELECT SellType.Name, SellPrice.ID, SellPrice.InterestRate, SellPrice.Price, SellPrice.CK
			FROM SellPrice INNER JOIN SellType ON SellType.ID = SellPrice.SellTypeID WHERE 1=1 ";
		$sql = $sql." AND ProductID = ".$productId;
		$result = $conn->query($sql);
		
		while($row = $result->fetch_assoc())
		{
			array_push($sellTypeArray, $row);
		}
		
		$sql = "SELECT A.*, IF(B.ID IS NOT NULL, 'true', 'false' ) AS checked FROM ProductUnit A
			LEFT JOIN ProductUsedUnit B ON A.ID = B.ProductUsedUnit AND B.ProductID = ".$productId." WHERE A.ActiveFlg = 1 ORDER BY Name";
		
		$result = $conn->query($sql);
		
		while($row = $result->fetch_assoc())
		{
			array_push($productUnitArray, $row);
		}
	} else {
		$sql = "SELECT *, Value0 AS 'InterestRate' FROM SellType ";
		$result = $conn->query($sql);
		
		while($row = $result->fetch_assoc())
		{
			array_push($sellTypeArray, $row);
		}
		
		$sql = "SELECT * FROM ProductUnit WHERE ActiveFlg = 1 ORDER BY Name";
		
		$result = $conn->query($sql);
		
		while($row = $result->fetch_assoc())
		{
			array_push($productUnitArray, $row);
		}
	}
	
	// Finally, encode the array to JSON and output the results

	$data = ["sellTypeData" => $sellTypeArray, "productUnitData" => $productUnitArray, "type" => "DATA"];
	echo json_encode($data, JSON_UNESCAPED_UNICODE);

	$conn->close();
}

?>