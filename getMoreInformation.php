<?php 

include 'includes/database.inc.php';

$conn = getDatabaseConnection(); //gets database connection

if(isset($_GET['movie_id'])){
	$sql = "SELECT m.movie_id AS movie_id, m.title AS title, m.description AS description, m.rating AS rating, c.name AS categoryname
			FROM oc_movies m
			INNER JOIN oc_categories c ON c.category_id = m.category_id
			WHERE movie_id = " . $_GET['movie_id'];
	$records = getDataBySQL($sql);	
	foreach ($records as $record) {
		echo "Title: " . $record['title'] . "<br />";
		echo "Description: " . $record['description'] . "<br />";
		echo "Rating: " . $record['rating'] . "<br />";
		echo "Category: " . $record['categoryname'] . "<br />";
	}
	
	$sql = "SELECT *
			FROM oc_actors
			WHERE movie_id = " . $_GET['movie_id'];
	$records = getDataBySQL($sql);
	echo "Actors: ";
	foreach ($records as $record) {
		echo  $record['first_name'];
		echo " " . $record['last_name'] . "<br />";
	}
	
	$sql = "SELECT *
			FROM oc_directors
			WHERE movie_id = " . $_GET['movie_id'];
	$records = getDataBySQL($sql);
	echo "<br />";
	echo "Directors: ";
	foreach ($records as $record) {
		echo  $record['first_name'];
		echo " " . $record['last_name'] . "<br />";
	}
}
?>