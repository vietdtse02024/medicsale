<?php 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
	header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
	header('Content-Type: application/json; charset=utf-8');
	
	$servername = "localhost";
	$username = "root";
	$password = "123456";
	$dbname = "med_data";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	// Change character set to utf8
	mysqli_set_charset($conn,"utf8");
?>
