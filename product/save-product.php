<?php include '../config.php';?>
<?php
$jsonData = isset($_POST['mydata']) ? json_decode($_POST['mydata'], true) : null;
$productId = $jsonData['productId'];
$productName = $jsonData['productName'];
$supplier = $jsonData['supplier'];
$productGroup = $jsonData['productGroup'];
$country = $jsonData['country'];
$producter = $jsonData['producter'];
$note = $jsonData['note'];
$importPrice = $jsonData['importPrice'];
$sellType = $jsonData['sellType'];
$productUnit = $jsonData['productUnit'];

$queryInsertProduct = "INSERT INTO Product (ProductName, GroupID, CountryID, Supplier, ProducterID, ImportPrice, Descriptions, Image, ActiveFlg)
						VALUES (?,?,?,?,?,?,?,'not used', 1)";
$queryUpdateProduct = "UPDATE Product SET ProductName = ?, GroupID = ?, CountryID = ?, Supplier = ?
                            , ProducterID = ?, ImportPrice = ?, Descriptions = ? WHERE ID = ?";
$queryUpdateSellPrice = "UPDATE SellPrice SET InterestRate = ?, Price = ? WHERE ID = ?";
$queryInsertSellPrice = "INSERT INTO SellPrice (ProductID, SellTypeID, InterestRate, Price) VALUES (?, ?, ?, ?)";

$queryDeleteProductUsedUnit = "DELETE FROM ProductUsedUnit WHERE ProductID = ?";
$queryInsertProductUsedUnit = "INSERT INTO ProductUsedUnit (ProductID, ProductUsedUnit) VALUES (?,?)";

try {
	$activeFlg = 1;
    $conn->autocommit(FALSE); // i.e., start transaction
	
	if (empty($productId)) {
		// prepare and bind
		$stmt = $conn->prepare($queryInsertProduct);
		$stmt->bind_param("siiiids",$productName, $productGroup, $country, $supplier, $producter, $importPrice, $note);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		$productId = $stmt->insert_id;
		
		$productCode = getPreCode(strlen($productId)).$productId;
		$stmt = $conn->prepare("UPDATE Product SET ProductCode = ? WHERE ID = ?");
		$stmt->bind_param("si",$productCode, $productId);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		
		foreach ($sellType as $item) {
			$stmt = $conn->prepare($queryInsertSellPrice);
			$stmt->bind_param("iidd",$productId, $item['ID'], $item['InterestRate'], $item['Price']);
			$result = $stmt->execute();
			if (!$result ) {
				throw new Exception($conn->error);
			}
		}
		
	} else {
		foreach ($sellType as $item) {
			$stmt = $conn->prepare($queryUpdateSellPrice);
			$stmt->bind_param("ddi",$item['InterestRate'], $item['Price'], $item['ID']);
			$result = $stmt->execute();
			if (!$result ) {
				throw new Exception($conn->error);
			}
		}
		
		$stmt = $conn->prepare($queryDeleteProductUsedUnit);
		$stmt->bind_param("i",$productId);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
		
		
		
		$stmt = $conn->prepare($queryUpdateProduct);
		$stmt->bind_param("siiiidsi", $productName, $productGroup, $country, $supplier, $producter, $importPrice, $note, $productId);
		$result = $stmt->execute();
		if (!$result ) {
			throw new Exception($conn->error);
		}
		
		
	}
	
	foreach ($productUnit as $item) {
		$stmt = $conn->prepare($queryInsertProductUsedUnit);
		$stmt->bind_param("ii",$productId, $item['ID']);
		$result = $stmt->execute();
		if ( !$result ) {
			throw new Exception($conn->error);
		}
	}
	
	
    $conn->commit();
    $conn->autocommit(TRUE);
	
	$data = ["result" => "SUCCESS"];
}
catch ( Exception $e ) {
	$data = ["result" => "ERROR"];
	
    // before rolling back the transaction, you'd want
    // to make sure that the exception was db-related
    $conn->rollback(); 
    $conn->autocommit(TRUE); // i.e., end transaction   
}
$stmt->close();
$conn->close();

// Finally, encode the array to JSON and output the results
echo json_encode($data);


function getPreCode($length) {
	$temp = "";
	switch ($length)
	{
		case 1:
			$temp = "0000000";
			break;
		case 2:
			$temp = "000000";
			break;
		case 3:
			$temp = "00000";
			break;
		case 4:
			$temp = "0000";
			break;
		case 5:
			$temp = "000";
			break;
		case 6:
			$temp = "00";
			break;
		case 7:
			$temp = "0";
			break;
		default:
			break;
	}
	return $temp;
}
?>