<?php

include 'includes/database.inc.php';

$conn = getDatabaseConnection(); //gets database connection


function displayCategories(){
	$sql = "SELECT category_Id, name
		FROM oc_categories";
	$records = getDataBySQL($sql);	
	foreach ($records as $record) {
		echo "<option value=\"".$record['category_Id']."\">".$record['name'] . "</option>";
	}
}

function displayAllMovies(){
	$sql = "SELECT m.movie_id AS movie_id, m.title AS title, m.description AS description, m.rating AS rating, c.name AS categoryname
			FROM oc_movies m
			INNER JOIN oc_categories c ON c.category_id = m.category_id";
	
	$records = getDataBySQL($sql);	
	return $records;
	/*
	foreach ($records as $record) {
		echo $record['productName']."-".$record['price']."<br />";
	}
	 */
}

function filterProucts(){
global $conn;

//reset values
if (isset($_GET['reset'])){
	unset($_GET['searchForm']);
	unset($_GET['categoryId']);
	unset($_GET['title']);
	unset($_GET['rating']);
	unset($_GET['orderBy']);
}

if(isset($_GET['searchForm'])){  //user submitte the filter form
	
	$categoryId = $_GET['categoryId'];
	//This is the WRONG way to create quaries because it allows SQL injections
	/*
	$sql = "SELECT productName, price 
			FROM oe_product
			WHERE categoryId = '". $categoryId ."'";
	*/
	
	$sql = "SELECT m.movie_id AS movie_id, m.title AS title, m.description AS description, m.rating AS rating, c.name AS categoryname
			FROM oc_movies m
			INNER JOIN oc_categories c ON c.category_id = m.category_id
			WHERE m.category_id = :categoryId"; //using Named Parameters (precent SQL injections)
	$nameParameters = array();
	$nameParameters[':categoryId'] = $categoryId;
	
	$title = $_GET['title'];
	
	if(!empty($title)){
		
		//$sql = $sql . "";
		$sql .= " AND m.title = :title"; //using named parameters
		$nameParameters[':title'] = $title;
	}

	$rating = $_GET['rating'];
	
	if($rating != 11){
		$sql .= " AND m.rating = :rating"; //using named parameters
		$nameParameters[':rating'] = $rating;
	}
	
	$orderByFields = array("title", "rating");
	$orderByIndex = array_search($_GET['orderBy'], $orderByFields);
	
	//$sql .= " ORDER BY " . $_GET['orderBy'];
	$sql .= " ORDER BY " . $orderByFields[$orderByIndex];
	$statement=$conn->prepare($sql);
	$statement->execute($nameParameters);
	$records = $statement->fetchALL(PDO::FETCH_ASSOC);
	/*
	foreach ($records as $record) {
		echo $record['productName']."-".$record['price']."<br />";
	}
	 * 
	 */
	 return $records;
}
}

function getStatistics(){
	$sql = "SELECT MIN(rating) AS low, MAX(rating) AS high, AVG(rating) AS average, COUNT(rating) AS total 
			FROM oc_movies";
	$records = getDataBySQL($sql);	
	foreach ($records as $record) {
		echo "Lowest Rating: " . $record['low'] . "<br />";
		echo "Highest Rating: " . $record['high'] . "<br />";
		echo "Average Rating: " . $record['average'] . "<br />";
		echo "Total Rating: " . $record['total'] . "<br />";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Online-Catalog</title>
  <meta name="description" content="">
  <meta name="author" content="Mauro">

  <meta name="viewport" content="width=device-width; initial-scale=1.0">

  <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
  <link href="css/styles.css" rel="stylesheet" />
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
</head>

<body>
  <div>
    <header>
      <h1>Online Movie Catalog</h1>
    </header>

    <div>
    	
    	
    	
    	
		<form method="get">
		Select Category:
		<select name="categoryId">
			<!-- this data should be coming from the database -->
			<?=displayCategories()?>

		</select>
			
		Title: 
		<input type="text" name="title" /><!--value="<?=$_GET['title']?>"-->  	
		Rating: 
		<select name="rating">
			<option value="11">None</option>
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</select>


		
		 <strong>Order by:</strong>
		<select name="orderBy">
			<option value="title">Title</option>
			<option value="rating">Rating</option>
		</select>
		<br />
		<input type="submit" value="Search Movies" name="searchForm" />
		<input type="submit" value="Reset Values" name="reset" />
		</form>
		
		<hr> <br />
		<div style="float: left">
		<?php
		
		//Displays all products by default
		if(!isset($_GET['searchForm'])){
			$records = displayAllMovies();
		} else {
			$records = filterProucts();
		}
		
		foreach ($records as $record) {
			echo "<a target='getMoreInformationIframe' href='getMoreInformation.php?movie_id=" . $record['movie_id'] . "'>";
			echo $record['title'];
			echo "</a>";
			echo " - ".$record['description'];
			echo " - ".$record['rating'];
			echo " - ".$record['categoryname']."<br />";
		}
		?>
		</div>
		<div style="float: left">
			<iframe src="getMoreInformation.php" name="getMoreInformationIframe" width="250" height="300" frameborder="0">
				
			</iframe>
		</div>
		<div>
			<h2>Statistics</h2>
			<?=getStatistics()?>
		</div>
      
    </div>

    <footer>
     <p>&copy; Copyright  by Mauro</p>
    </footer>
  </div>
</body>
</html>
