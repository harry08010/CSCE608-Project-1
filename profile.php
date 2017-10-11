<?php
	
	session_start();
	
	$errorp = "";
	
	$update = "";
	
	if(array_key_exists("stu_id", $_COOKIE) && $_COOKIE['stu_id']){
		$_SESSION['stu_id'] = $_COOKIE['stu_id'];
	}

	if(array_key_exists("stu_id", $_SESSION)){
		
		$studentid = $_SESSION['stu_id'];
		
		include("connection.php");
		
		$query = "SELECT * FROM students WHERE stu_id = ".mysqli_real_escape_string($link,$_SESSION['stu_id'])." LIMIT 1";
		
		$row = mysqli_fetch_array(mysqli_query($link, $query));
		
		$first_name = $row['first_name'];
		
		$last_name = $row['last_name'];
		
		$middle_name = $row['middle_name'];
		
		$school_name_pro = $row['school_name']; 
		
		$email = $row['email'];
		
		if(array_key_exists("subprofile", $_POST)){
			
			if(!isset($_POST['unichange']) || $_POST['unichange'] == ""){
				
				$errorp .= "Your school name is required<br>";
				
			}
			
			if(!isset($_POST['email']) || $_POST['email'] == ""){
				
				$errorp .= "Your email address is required<br>";
				
			}
			
			
			if($errorp != ""){
			
				$errorp = "<p>Some required information is missing or incomplete.</p>".$errorp;
			
			} else {
				
				$uninum = $_POST['unichange'];
				
				$first_name = mysqli_real_escape_string($link, $_POST['firstName']);
				
				$last_name = mysqli_real_escape_string($link, $_POST['lastName']);
				
				$middle_name = mysqli_real_escape_string($link, $_POST['middleName']);
				
				$uniquery = "SELECT * FROM schools WHERE school_id = $uninum";
				
				$uni_change_result = mysqli_fetch_array(mysqli_query($link, $uniquery));
				
				$school_name_pro = $uni_change_result['school_name'];
				
				$email =  mysqli_real_escape_string($link, $_POST['email']);
			
				$query = "UPDATE students 
				
						  SET first_name = '$first_name', last_name = '$last_name', middle_name = '$middle_name', school_name = '$school_name_pro', email = '$email'
				
						  WHERE stu_id =  $studentid";
				 
				if(!mysqli_query($link, $query)){
					
					$errorp = "Could not update your information<br>Possible reason: The school name is not right";
					
				}else{
					
					$update = "Your information has been updated.";

				}
			}	
		}
		
		include("header.php"); 
	}
	
	

?>

<html>

	<head>
	
		<title>TYE | Profile</title>
		
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

		<div class = "containerp">

			<form method = "post" id = "profileform">
				
				<p id = "titlep">You profile</p>
				
				<div id = "updatesuccess"><?php if($errorp != ""){
					
													echo '<div class="alert alert-danger" role="alert">'.$errorp.'</div>';
											
												} else if($update != ""){
													
													echo '<div class="alert alert-success" role="alert">'.$update.'</div>';
												}
											
											?>
				
				<div class="form-group">
					
					<label for="firstName" class = "text-white">First Name:</label>
					
					<input class="form-control" type = "text" name = "firstName" value = <?php 

																		echo $first_name;
																		
																		?>>		
																		
					<small id="emailHelp" class="form-text text-white">We'll never share your email with anyone else.</small>
					
				</div>
				
				<div class="form-group">
					
					<label for="lastName" class = "text-white">Last Name:</label>
					
					<input class="form-control" type = "text" name = "lastName" value = <?php 

																		echo $last_name;
																		
																	?>>
																
				</div>
				
				<div class="form-group">
					
					<label for="middleName" class = "text-white">Middle Name:</label>
				
					<input class="form-control" type = "text" name = "middleName" value = <?php 

																		echo $middle_name;
																		
																	?>>
																
				</div>
				
				<div class="form-group">
					
					<label for="uni" class = "text-white">Current School:</label>
				
					<textarea class="form-control" id = "uni" readonly><?php 

														echo $school_name_pro;
														
													?></textarea>
																
				</div>
				
				<div class="form-group">
					
					<label class = "text-white">Change School:</label>
				
					<select class="form-control" id = "unichange" name="unichange">
					
						<option value="">Select School</option>
						
						<?php
						
							include('connection.php');
							
							$query_uni_change = "SELECT * FROM schools";
							
							$resultp = mysqli_query($link, $query_uni_change);
							
							while($rowp = mysqli_fetch_array($resultp)){
							
								echo '<option value="'.$rowp['school_id'].'">'.$rowp['school_name'].'</option>';
						
							}
						
						?>
						
					</select>
																
				</div>
															
				<div class="form-group">
					
					<label for="email" class = "text-white">Email Address:</label>
					
					<input class="form-control" type = "email" name = "email" value = <?php 

																		echo $email;
																		
																	?>>
						
				</div>			

				<button type="submit" class="btn btn-primary" id = "subprofile" name = "subprofile">Update</button>

			</form>

		</div>

	</body>
		
</html>

<?php

	include("footer.php");

?>