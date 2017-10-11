

<?php

    include_once "connection.php";
	
    if (!empty($_POST["cid"])) {
		
		$cid = $_POST["cid"];
		
        $query_uni ="SELECT * 
		
		FROM countries c INNER JOIN schools s ON c.country = s.country 
		
		WHERE country_id = $cid
		
		ORDER BY school_name";
		
        $resultu = mysqli_query($link, $query_uni);

        foreach ($resultu as $uni){
?>
            <option value="<?php echo $uni["school_id"];?>"><?php echo $uni["school_name"];?>
    </option>       
<?php
        }
    }else{
		
		echo '<option value="">Select Country First</option>';
	}
?> 