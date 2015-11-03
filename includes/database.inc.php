<?php

function getDatabaseConnection(){
	$host = "localhost";
	$dbname = "klin5995";  //your otterid
	$username = "klin5995"; //your otterid
	$password = "s3cr3t";
	
	//creates connection to database
	$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	
	// Setting Errorhandling to Exception
	$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	return $dbConn;
}

function getDataBySQL($sql){
global $conn;
	$statement = $conn->prepare($sql); //prevents SQL Injection
	$statement->execute();
	$records = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch and fetchAll
	return $records;
}
?>