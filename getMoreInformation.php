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
	
/*	$sql = "SELECT *
			FROM oc_actors a
			INNER JOIN oc_movie_actors m ON m.actor_id = a.actor_id
			WHERE m.movie_id = " . $_GET['movie_id'];
			*/
	$sql = "SELECT *
			FROM oc_actors a
			WHERE a.movie_id = " . $_GET['movie_id'];
	$records = getDataBySQL($sql);
	echo "Actors: ";
	echo "<br />";
	foreach ($records as $record) {
		echo  $record['first_name'];
		echo " " . $record['last_name'] . "<br />";
	}
	
/*	$sql = "SELECT *
			FROM oc_directors d
			INNER JOIN oc_movie_directors m ON m.director_id = d.director_id
			WHERE m.movie_id = " . $_GET['movie_id'];
			*/
	$sql = "SELECT *
			FROM oc_directors d
			WHERE d.movie_id = " . $_GET['movie_id'];
	$records = getDataBySQL($sql);
	
	echo "Directors: ";
	echo "<br />";
	foreach ($records as $record) {
		echo  $record['first_name'];
		echo " " . $record['last_name'] . "<br />";
	}
}
?>