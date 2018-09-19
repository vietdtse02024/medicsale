<?php include 'config.php';?>
<?php

try {
	if(isset($_GET['function'])) {
		$functionName = $_GET['function'];
		if(strcmp($functionName, "ProductDataTableEdit") == 0) {
			$productId = isset($_GET['productId']) ? (int) $_GET['productId'] : null;
			getProductSellTypeData($productId);
		} else if(strcmp($functionName, "InitProductInit") == 0) {
			initProductInit();
		} else if(strcmp($functionName, "BarcodeInfo") == 0) {
			getBarcodeInfo();
		}
	}
	
} catch ( Exception $e ) {
	$data = ["result" => "ERROR: ".$e];
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
} finally {
    $conn->close();
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
}

function initProductInit() {
	$productCode = isset($_GET['productCode']) ? (int) $_GET['productCode'] : null;
	$conn = $GLOBALS['conn'];
    $productUnits = array();
	if ($productCode != null) {
		$sql = "SELECT pu.ID, pu.Name FROM ProductUnit pu INNER JOIN ProductUsedUnit puu ON pu.ID = puu.ProductUsedUnit WHERE puu.ProductID = ".$productCode;
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc())
		{
			$object = new stdClass;
			$object->name=$row["Name"];
			$object->value=$row["ID"];
			array_push($productUnits, $object);
		}
	}
	
	// Finally, encode the array to JSON and output the results

	$data = ["productUnits" => $productUnits, "type" => "DATA"];
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

function getBarcodeInfo() {
	$productCode = isset($_GET['productCode']) ? (int) $_GET['productCode'] : null;
	$unitId = isset($_GET['unitId']) ? (int) $_GET['unitId'] : null;
	$conn = $GLOBALS['conn'];
    $productUnits = array();
	if ($productCode != null) {
		$inputPrice = 0;
		$exchange = 1;
        $barcode = "";
        $productName = "";
		$sql = "SELECT p.ProductCode, p.ProductName, s.Price FROM Product p 
		INNER JOIN SellPrice s ON p.ID = s.ProductID WHERE p.ProductCode = ".$productCode." AND s.SellTypeID = 1";
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc())
		{
			$inputPrice = $row["Price"];
			$barcode = $row["ProductCode"];
			$productName = $row["ProductName"];
		}
		$sql = "SELECT Exchange from ProductUnit WHERE ID = ".$unitId;
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc())
		{
			$exchange = $row["Exchange"];
		}
		
		$price = floatval($inputPrice) * intval($exchange);
	}
	
	// Finally, encode the array to JSON and output the results

	$data = ["price" => $price, "productName" => $productName, "barcode" => $barcode, "type" => "DATA"];
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

?>