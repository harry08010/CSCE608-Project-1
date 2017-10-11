<?php

	session_start();
	
	include("connection.php");
		
	$num = $_GET['num'];
	
	$delete = "DELETE FROM products WHERE pro_id = $num";
	
	mysqli_query($link, $delete);

	header('Location:history.php');
?>

