<?php

	session_start();
	
	ob_start();
	
	$errore = "";
	
	$successe = "";
	
	$stuid = $_SESSION['stu_id'];
	
	$id = $_GET['editid'];
	
	if(isset($_POST['edit'])){
		
		include("connection.php");
		
		if(!isset($_POST['item']) || $_POST['item'] == ""){
			
			$errore .= "Item name is required<br>";
			
		}
		
		if(!isset($_POST['category']) || ($_POST['category'] == 0)){
			
			$errore .= "Category is required<br>";
			
		}
		
		if(!isset($_POST['price']) || $_POST['price'] == ""){
			
			$errore .= "Price is required<br>";
			
		}
		
		if(!isset($_POST['seller']) || ($_POST['seller'] == 0)){
			
			$errore .= "Seller name is required<br>";
			
		}
		
		if(!isset($_POST['dateadd']) || $_POST['dateadd'] == ""){
			
			$errore .= "Date is required<br>";
			
		}
		
		if($errore != ""){
			
			$errore = "<p>Some required information is missing or incomplete.</p>".$errore;
			
		} else {
			
			$queryfetchcate = "SELECT subcategory FROM product_categories WHERE cate_id = '".$_POST['category']."'";
			
			if(!mysqli_query($link, $queryfetchcate)){
				
				$errore .= "fetch error";
				
			}else{
			
				$resultfetchcate = mysqli_fetch_array(mysqli_query($link, $queryfetchcate));
				
				$subcategory = $resultfetchcate['subcategory'];
				
				$queryfetchseller = "SELECT seller_name FROM sellers WHERE seller_id = '".$_POST['seller']."'";
				
				$resultfetchseller = mysqli_fetch_array(mysqli_query($link, $queryfetchseller));
				
				$product_name = mysqli_real_escape_string($link, $_POST['item']);
				
				$price = mysqli_real_escape_string($link, $_POST['price']);
				
				$orderdate = mysqli_real_escape_string($link, $_POST['dateadd']);

				$seller_name = $resultfetchseller['seller_name'];
				
				$querya =  "UPDATE products 
				
							SET pro_name = '$product_name', price = '$price', category = '$subcategory', seller_name = '$seller_name'

							WHERE pro_id = $id";
				
				if(!mysqli_query($link, $querya)){
					
					$errore = "<p>Could not update item, please try again later</p>";
					
					print_r($id);
					
				} else {
						
					$productid = mysqli_insert_id($link);
					
					$queryaddorder =   "UPDATE orders 
					
										SET order_date = '$orderdate'
										
										WHERE pro_id = $id";
					
					if(!mysqli_query($link,$queryaddorder)){
						
						$errore = "<p>Could not update item, please try again later</p>";
						
					}else{
						
						$successe = "Congradualations! Item Updated.";
						
						header('Location: history.php');
		
						ob_end_flush();
						
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
	
		<title>TYE | Edit</title>
		
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

		<h2>Edit your expense history</h2>

		<div class = "containera">
			
			<div id = "error"><?php if($errore != ""){
					
											echo '<div class="alert alert-danger" role="alert">'.$errore.'</div>';
											
										} 
									
									?>
			</div>
			
			<div id = "suc"><?php if($successe != ""){
					
											echo '<div class="alert alert-success" role="alert">'.$successe.'</div>';
											
										} 
									
									?>
			</div>
			
			<form method = "post" id = "insert">
				
				<div class="form-group row">
				
					<label for="product" class="col-2 col-form-label text-white">Item  :</label>
				   
					<div class="col-9">
				  
						<input class="form-control" type="text" name = "item" id="product" placeholder= " e.g. water, desk, earphone">
					
					</div>
				  
				</div>
				
				<div class="form-group row">
				
					<label for="category" class="col-2 col-form-label text-white">Category  : </label>
					
					 <div class="col-9">
					 
						<select  class="form-control" id="category" name = "category" onchange = "showCatee(this.value)">
						
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
			
				<button type="submit" class="btn btn-primary" id = "submite" name = "edit">Submit</button>
			
			</form>

		</div>

		<div>

			<textarea id = "descriptione" name = "description" rows = "6" cols = "50" readonly>
				
				<?php
				
					include("getcate.php");
					
				?>
				
				
			</textarea>


		</div>

	</body>
		
</html>

<?php include("footer.php"); ?>