<?php  

	$server    = "localhost";
	$username  = "root";
	$password  = "";
	$database  = "mading_practice";
	$conn  	   = mysqli_connect($server, $username, $password, $database);

	$queryGetRole = "SELECT * FROM role";
	$execGetRole  = mysqli_query($conn, $queryGetRole);

?>