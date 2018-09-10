<?php

$hostAddress = getenv('HOST_ADDRESS');
$dbUserName = getenv('DB_USERNAME');
$dbPassword = getenv('DB_PASSWORD');
$dbName = getenv('DB_NAME');

$link = mysqli_connect($hostAddress, $dbUserName, $dbPassword, $dbName);
		
		if(mysqli_connect_error()){
			
			die ("Database Connection Error");
			
		}

		

?>
