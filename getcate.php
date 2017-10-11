<?php
	
	$q = intval($_GET['q']);

	include("connection.php");

	$query="SELECT description FROM product_categories WHERE  cate_id = '".$q."'";

	$result = mysqli_query($link, $query);

	$row = mysqli_fetch_array($result);
	
	echo $row['description'];

?> 