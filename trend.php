<?php

	session_start();
	
	if(array_key_exists("stu_id", $_COOKIE) && $_COOKIE['stu_id']){
		$_SESSION['stu_id'] = $_COOKIE['stu_id'];
	}
	
	if(array_key_exists("stu_id", $_SESSION)){
		
	}else{
		header("Location: index.php");
	}
	
	include("header.php");
	
	$student_trend_id = $_SESSION['stu_id'];
	
	include('connection.php');
	
	$chart_data = '';
	
	if(isset($_POST['year_select'])){
		
		$sel_year = $_POST['yearhis'];
		
		$query_trend = "SELECT SUM(p.price), Month(o.order_date), Year(o.order_date)
		
					FROM products p INNER JOIN orders o ON p.pro_id = o.pro_id
					
					WHERE o.stu_id = $student_trend_id AND Year(o.order_date) = '".$_POST['yearhis']."'
					
					GROUP BY Month(o.order_date)";
		
	} else {
		
		$sel_year = date("Y");
		
		$query_trend = "SELECT SUM(p.price), Month(o.order_date), Year(o.order_date) 
					FROM products p INNER JOIN orders o ON p.pro_id = o.pro_id
					WHERE o.stu_id = $student_trend_id AND Year(o.order_date) = Year(CURDATE())
					GROUP BY Month(o.order_date)";
		
	}
					
	$result_trend = mysqli_query($link, $query_trend);
	
	while($row_trend = mysqli_fetch_array($result_trend)){
		
		$chart_data .= "{month:".$row_trend['Month(o.order_date)'].", sum: ".$row_trend['SUM(p.price)']."}, ";
		
	}
	
	$chart_data = substr($chart_data,0,-2);

?>

<!DOCTYPE html>

<html>
	<head>
	
		<title>TYE | Trend</title>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
		
	</head>
	

	<body>
	
		<nav class="navbar navbar-toggleable-md navbar-light bg-faded navbar-fixed-top">
		 
			<a class="navbar-brand" href='index.php?logout=1'>Track Your Expenses</a>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
			
				<ul class="navbar-nav mr-auto">
				
					<li class="nav-item active">
					
						<a class="nav-link" href="loggedinpage.php">Home<span class="sr-only">(current)</span></a>
						
					</li>
					
					<li class="nav-item">
					
						<a class="nav-link" href='add.php'>Add New</a>
						
					</li>  
					
				</ul>
				
				<div class="form-inline my-2 my-lg-0">
				
					<a href = 'profile.php'><button id = "profile" class = "btn btn-outline-success my-2 my-sm-0" type="submit">My Profile</button></a>
					
					<a href = 'index.php?logout=1'><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Log out</button></a>
					
				</div>
				
		  </div>
		  
		  
		</nav>
		
		<br /><br />
		
		<form method = "post">
		
			<div class="form-group mcselect col-2 selections">
		
			<br>
			
			<label class = "text-white" for="year">Year:</label>
			
			<select class="form-control" id="year" name = "yearhis">
			
				<option value = 0>Select Year</option>
				<option value = 2017>2017</option>
				<option value = 2018>2018</option>
				<option value = 2019>2019</option>
				<option value = 2020>2020</option>
				<option value = 2021>2021</option>
				<option value = 2022>2022</option>
				<option value = 2023>2023</option>
				<option value = 2024>2024</option>
				<option value = 2025>2025</option>
				<option value = 2026>2026</option>
				<option value = 2027>2027</option>
				<option value = 2028>2028</option>
				
			</select>
			
			<br>
			
			<input class="btn btn-success" type = "submit" name = "year_select" value = "Confirm" id = "confirmbutton_year">
			
			<br>
				
			</div>
		
		</form>
		
		<div class = "containert">
		
			<h3>Your expenditure of every month in <?php echo $sel_year; ?> is showing here</h3>
					
			<div id = "chart"></div>
				
		</div>
		
		</body>
		
</html>

<script>

	Morris.Bar({
		
		element : 'chart',
		data:[<?php echo $chart_data; ?>],
		xkey:['month'],
		ykeys:['sum'],
		labels:['sum'],
		xLabels:"month",
		hideHover:'auto'
		
	});

</script>

<?php

	include("footer.php");

?>