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
		
	include("connection.php");	
	
	$studentorderid = $_SESSION['stu_id'];

	$sql  = "SELECT p.pro_name, p.price, p.category, p.seller_name, o.order_date, p.pro_id

	FROM products p

	INNER JOIN orders o on p.pro_id = o.pro_id
	
	WHERE o.stu_id = $studentorderid
	
	ORDER BY o.order_date DESC";
	
	if(isset($_POST['mc_select'])){
		
		if(isset($_POST['monthhis']) && $_POST['monthhis'] != 0){
							
			if(isset($_POST['categoryhis']) && $_POST['categoryhis'] != 0){
				
				$queryfetchcate = "SELECT subcategory FROM product_categories WHERE cate_id = " .$_POST['categoryhis'];
				
				$cateresult = mysqli_fetch_array(mysqli_query($link, $queryfetchcate));
				
				$catefetch = $cateresult['subcategory'];
				
				$sqlselected = "SELECT p.pro_name, p.price, p.category, p.seller_name, o.order_date, p.pro_id

								FROM products p

								INNER JOIN orders o on p.pro_id = o.pro_id
								
								WHERE Month(o.order_date) = " .$_POST['monthhis']." AND p.category = '$catefetch' AND o.stu_id = $studentorderid
								
								ORDER BY o.order_date DESC";
				
			} else {
				
				$sqlselected = "SELECT p.pro_name, p.price, p.category, p.seller_name, o.order_date, p.pro_id

							FROM products p

							INNER JOIN orders o on p.pro_id = o.pro_id
							
							WHERE Month(o.order_date) = " .$_POST['monthhis']." AND o.stu_id = $studentorderid
							
							ORDER BY o.order_date DESC";
				
			}
		
		} else {
			
			if(isset($_POST['categoryhis']) && $_POST['categoryhis'] != 0){
				
				$queryfetchcate = "SELECT subcategory FROM product_categories WHERE cate_id = " .$_POST['categoryhis'];
				
				$cateresult = mysqli_fetch_array(mysqli_query($link, $queryfetchcate));
				
				$catefetch = $cateresult['subcategory'];
				
				$sqlselected = "SELECT p.pro_name, p.price, p.category, p.seller_name, o.order_date, p.pro_id

								FROM products p

								INNER JOIN orders o on p.pro_id = o.pro_id
								
								WHERE p.category = '$catefetch' AND o.stu_id = $studentorderid
								
								ORDER BY o.order_date DESC";
				
			} else {
				
				$sqlselected = "SELECT p.pro_name, p.price, p.category, p.seller_name, o.order_date, p.pro_id

								FROM products p

								INNER JOIN orders o on p.pro_id = o.pro_id
								
								WHERE o.stu_id = $studentorderid
								
								ORDER BY o.order_date DESC";
				
			}
			
		}
		
		$query = mysqli_query($link, $sqlselected);
		
	} else {
		
		$query = mysqli_query($link, $sql);

		if (!$query) {
			
			die ('SQL Error: ' . mysqli_error($link));
			
		}
		
	}

	
	
?>


<!DOCTYPE html>

<html>

	<head>
	
		<title>TYE | Record</title>
		
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
		

		<form method = "post">
		
			<div class="form-group mcselect col-2 selections">
			
				<br>
				
				<label class = "text-white" for="month">Month:(Default all)</label>
				
				<select class="form-control" id="month" name = "monthhis">
					<option value = 0>Select Month</option>
					<option value = 1>Jan</option>
					<option value = 2>Feb</option>
					<option value = 3>Mar</option>
					<option value = 4>Apr</option>
					<option value = 5>May</option>
					<option value = 6>Jun</option>
					<option value = 7>Jul</option>
					<option value = 8>Aug</option>
					<option value = 9>Sep</option>
					<option value = 10>Oct</option>
					<option value = 11>Nov</option>
					<option value = 12>Dec</option>
				</select>
				
				<br>
				
				<label class = "text-white" for="catesel">Category:(Default all)</label>
				
				<select class="form-control" id="catesel" name = "categoryhis">
					<option value = 0>Select Category</option>
					<?php
							
						include("connection.php");
						
						$querycate = "SELECT * FROM product_categories ORDER BY subcategory ASC";
						
						$resultcate = mysqli_query($link, $querycate);

						while($rowcate = mysqli_fetch_array($resultcate)){
							echo '<option value="'.$rowcate['cate_id'].'">'.$rowcate['subcategory'].'</option>';
						} 

					?>
				</select>
				
				<input class="btn btn-success" type = "submit" name = "mc_select" value = "Confirm" id = "confirmbutton">
				
				<br>
				
			</div>
			
			<div id = "tablecontainer">
			
				<h1>Expenditure of this month</h1>
				
				<table class="table historytable">
					<thead class = "thead-default">
						<tr>
							<th class = "text-center">DATE</th>
							<th class = "text-center">ITEM</th>
							<th class = "text-center">CATEGORY</th>
							<th class = "text-center">SELLER</th>
							<th class = "text-center">PRICE</th>
							<th class = "text-center">EDIT</th>
							<th class = "text-center">DELETE</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$total 	= 0;
					while ($row = mysqli_fetch_array($query))
					{
						$price  = $row['price'] == 0 ? '' : number_format($row['price']);
						echo '<tr>
								<th scope="row" class="table-warning">'. date('M d, Y', strtotime($row['order_date'])) . '</th>
								<td class="table-danger">'.$row['pro_name'].'</td>
								<td class="table-danger">'.$row['category'].'</td>
								<td class="table-danger">'.$row['seller_name']. '</td>
								<td class="table-danger">'.$row['price'].'</td>
								<td class="table-danger"><a class="btn btn-info" href="edit.php?editid='.$row['pro_id'].'" role="button">Edit</a></td>
								<td class="table-danger"><a class="btn btn-danger" href="delete.php?num='.$row['pro_id'].'" role="button">Delete</a></td>
							</tr>';
						$total += $row['price'];
					}?>
					</tbody>
					<tfoot class = "bg-warning">
						<tr>
							<th colspan= "4" >TOTAL</th>
							<th class = "text-center"><?=number_format($total,2)?></th>
							<th colspan = "2" class = "text-center">
							<?php

								include('connection.php');
								
								$select_school = "SELECT school_name 
								
												FROM students
												
												WHERE stu_id = ". $_SESSION['stu_id'];

								$school_result = mysqli_fetch_array(mysqli_query($link, $select_school));
								
								$school_name = $school_result['school_name'];
								
								$select_country = "SELECT *
								
												FROM schools
												
												WHERE school_name = '$school_name'";

								$country_result = mysqli_fetch_array(mysqli_query($link, $select_country));
								
								$country_is = $country_result['country'];
								
								$select_currency = "SELECT currency 
								
								FROM countries
								
								WHERE country = '$country_is'";

								$currency_result = mysqli_fetch_array(mysqli_query($link, $select_currency));
								
								$currency = $currency_result['currency'];
								
								echo $currency;

							?></th>

						</tr>
					</tfoot>
					
				</table>
				
			</div>
				
		</form>
	
	</body>
		
</html>
	
	
	

<?php

	include("footer.php");

?>

