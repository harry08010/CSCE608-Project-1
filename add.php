<?php

	session_start();
	
	$errora = "";
	
	$success = "";
	
	$stuid = $_SESSION['stu_id'];
	
	if(isset($_POST['add'])){
		
		include("connection.php");
		
		if(!isset($_POST['item']) || $_POST['item'] == ""){
			
			$errora .= "Item name is required<br>";
			
		}
		
		if(!isset($_POST['category']) || ($_POST['category'] == 0)){
			
			$errora .= "Category is required<br>";
			
		}
		
		if(!isset($_POST['price']) || $_POST['price'] == ""){
			
			$errora .= "Price is required<br>";
			
		}
		
		if(!isset($_POST['seller']) || ($_POST['seller'] == 0)){
			
			$errora .= "Seller name is required<br>";
			
		}
		
		if(!isset($_POST['dateadd']) || $_POST['dateadd'] == ""){
			
			$errora .= "Date is required<br>";
			
		}
		
		if($errora != ""){
			
			$errora = "<p>Some required information is missing or incomplete.</p>".$errora;
			
		} else {
			
			$queryfetchcate = "SELECT subcategory FROM product_categories WHERE cate_id = '".$_POST['category']."'";
			
			if(!mysqli_query($link, $queryfetchcate)){
				
				$errora .= "fetch error";
				
			}else{
			
				$resultfetchcate = mysqli_fetch_array(mysqli_query($link, $queryfetchcate));
				
				$subcategory = $resultfetchcate['subcategory'];
				
				$queryfetchseller = "SELECT seller_name FROM sellers WHERE seller_id = '".$_POST['seller']."'";
				
				$resultfetchseller = mysqli_fetch_array(mysqli_query($link, $queryfetchseller));
				
				$product_name = mysqli_real_escape_string($link, $_POST['item']);
				
				$price = mysqli_real_escape_string($link, $_POST['price']);
				
				$orderdate = mysqli_real_escape_string($link, $_POST['dateadd']);

				$seller_name = $resultfetchseller['seller_name'];
				
				$querya = "INSERT INTO products (pro_name, price, category, seller_name)

				VALUES ('$product_name', '$price', '$subcategory', '$seller_name')";
				
				if(!mysqli_query($link, $querya)){
					
					$errora = "<p>Could not add item, please try again later</p>";
					
				} else {
						
					$productid = mysqli_insert_id($link);
					
					$queryaddorder = "INSERT INTO orders (stu_id, pro_id, order_date)
					
					VALUES ('$stuid', '$productid','$orderdate')";
					
					if(!mysqli_query($link,$queryaddorder)){
						
						$errora = "<p>Could not add item, please try again later</p>";
						
					}else{
						
						$success = "Congradualations! Item added.";
						
					}

				}
				
			}
				
		}
		
	}
	
	include("header.php");
?>

<!DOCTYPE html>

<html>
	<head>
	
		<title>TYE | Add New</title>
		
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

	<h2>Spent money on something today?</h2>

	<div class = "containera">
		
		<div id = "error"><?php if($errora != ""){
				
										echo '<div class="alert alert-danger" role="alert">'.$errora.'</div>';
										
									} 
								
								?>
		</div>
		
		<div id = "suc"><?php if($success != ""){
				
										echo '<div class="alert alert-success" role="alert">'.$success.'</div>';
										
									} 
								
								?>
		</div>
		
		<form action = "add.php" method = "post" id = "insert">
			
			<div class="form-group row">
			
			  <label for="product" class="col-2 col-form-label text-white">Item  :</label>
			  
			  <div class="col-9">
			  
				<input class="form-control" type="text" name = "item" id="product" placeholder= " e.g. water, desk, earphone">
				
			  </div>
			  
			</div>
			
			<div class="form-group row">
			
				<label for="category" class="col-2 col-form-label text-white">Category  : </label>
				
				 <div class="col-9">
				 
					<select  class="form-control" id="category" name = "category" onchange = "showCate(this.value)">
					
						<option>Select item category</option>
						
						<?php
						
						include("connection.php");
						
						$queryc = "SELECT * FROM product_categories ORDER BY subcategory ASC";
						
						$resultc = mysqli_query($link, $queryc);

						while($rowc = mysqli_fetch_array($resultc)){
							
							echo '<option value="'.$rowc['cate_id'].'">'.$rowc['subcategory'].'</option>';
							
						} 

						?>
						
					</select>
					
				</div>
				
			</div>
			
			<div class="form-group row">
			
				<label for="price" class="col-2 col-form-label text-white">Price  :</label>
				
				<div class="col-9">
				
					<input class="form-control" type="number" step = 0.01 id="price" name = "price" placeholder = "e.g. 9.99">
					
				</div>
				
			</div>
			
			<div class="form-group row">
			
				<label for="seller" class="col-2 col-form-label text-white">Seller  : </label>
				
				<div class="col-9">
				
					<select  class="form-control" id="seller" name = "seller">
					
						<option>Select seller</option>
						<?php
						
							include("connection.php");
							
							$querys = "SELECT * FROM sellers ORDER BY seller_name ASC";
							
							$results = mysqli_query($link, $querys);
							

							while($rows = mysqli_fetch_array($results)){
								
								echo '<option value="'.$rows['seller_id'].'">'.$rows['seller_name'].'</option>';
								
							} 

						 ?>
						 
					 </select>
				 
				</div>
				
			</div>
			
			<div class="form-group row">
				<label for="date" class="col-2 col-form-label text-white">Date  :</label>
				
				<div class="col-9">
				
					<input class="form-control" type="date" id="date" name = "dateadd">
					
				</div>
				
			</div>
		
			<button type="submit" class="btn btn-primary" id = "submita" name = "add">Submit</button>
		
		</form>

	</div>

	<div>

		<textarea id = "description" name = "description" rows = "6" cols = "50" readonly>
			
			<?php
			
				include("getcate.php");
				
			?>
			
			
		</textarea>


	</div>

	</body>
		
</html>


<?php include("footer.php"); ?>