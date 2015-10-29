<?php

function getDatabaseConnection(){
	$host = "localhost";
	$dbname = "klin5995";
	$username = "klin5995";
	$password = "s3cr3t";

	$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	
	$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	return $dbConn;
}

function getDataBySQL($sql){
	global $conn;
	
	$statement = $conn->prepare($sql);
	$statement->execute();
	$records = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	return $records;
}
?>