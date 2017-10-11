<?php

	session_start();
	
	ob_start();
	
	$error = "";
	
	$successsignup = "";
	
	if (array_key_exists("logout", $_GET)) {
        
        unset($_SESSION);
        setcookie("stu_id", "", time() - 60*60);
        $_COOKIE["stu_id"] = "";  
        
        session_destroy();
		header('Location: index.php');
        
    } else if ((array_key_exists("stu_id", $_SESSION) AND $_SESSION['stu_id']) OR (array_key_exists("stu_id", $_COOKIE) AND $_COOKIE['stu_id'])) {
        
        header("Location: loggedinpage.php");
        
    }

	if(array_key_exists("submit", $_POST)){
		
		include("connection.php");
		
		if(!$_POST['email']){
			$error .= "An email address is required<br>";
		}
		
		if(!$_POST['password']){
			$error .= "An password is required<br>";
		}
		
		if($_POST['signUp'] == "1"){
			
			if(!$_POST['university']){
				
				$error .= "Your school name is required<br>";
				
			}
		}
		
		
		
		if($error != ""){
			
			$error = "<p>There were error(s) in your form</p>".$error;
			
		} else {
			
			if($_POST['signUp'] == "1"){

				$query = "SELECT stu_id FROM students WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
				
				$result = mysqli_query($link, $query);
				
				if(mysqli_num_rows($result) > 0){
					
					$error = "You have already signed up, please sign in.";
			
				} else {
					
					$uni_id = $_POST['university'];
					
					$find_uni = "SELECT * FROM schools WHERE school_id = $uni_id";
					
					$uni_result = mysqli_fetch_array(mysqli_query($link, $find_uni));
					
					$uni = $uni_result['school_name'];
					
					$query = "INSERT INTO students (school_name, email, password) VALUES ('$uni','".mysqli_real_escape_string($link, $_POST['email'])."','".mysqli_real_escape_string($link, $_POST['password'])."')";
					
					if(!mysqli_query($link, $query)){
						
						$error = "<p>Could not sign you up, please try again later.</p>";
						
					} else {
						
						$query = "UPDATE students SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE stu_id = ".mysqli_insert_id($link)." LIMIT 1";
						
						mysqli_query($link, $query);
						
						$_SESSION['stu_id'] = mysqli_insert_id($link);

						if ($_POST['stayLoggedIn'] == '1') {

							setcookie("stu_id", mysqli_insert_id($link), time() + 60*60*24*365);

						} 

						$successsignup = "You have signed up successfully! Please sign in.";
					
					}
				
				}
			} else {
				
				$query = "SELECT * FROM students WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
				$result = mysqli_query($link, $query);
			
				$row = mysqli_fetch_array($result);
			
				if (isset($row)) {
					
					$hashedPassword = md5(md5($row['stu_id']).$_POST['password']);
					
					if ($hashedPassword == $row['password']) {
						
						$_SESSION['stu_id'] = $row['stu_id'];
						
						if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {

							setcookie("stu_id", $row['stu_id'], time() + 60*60*24*365);

						} 

						header("Location: loggedinpage.php");
                                
						} else {
					
							$error = "Invalid email-password combination.";
					
						}
					
				}else{
					
					$error = "Invalid email-password combination.";
					
				}
			}
		}
	}
	
?>

<?php include("header.php"); ?>

<!DOCTYPE html>

<html>

	<head>
	
		<title>TYE</title>
		
	</head>
	
	<body>
  
		<div class = "container">

			<h1>Tracking Your Expenses</h1>
			
			<p><strong id = "strong">A note of your spending</strong><p>
			
			<div id = "error"><?php if($error != ""){
				
										echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
										
									} else if ($successsignup != ""){
										
										echo '<div class="alert alert-success" role="alert">'.$successsignup.'</div>';
										
									}
								
								?>
			</div>

			<form method = "post" id = "sighUpForm">
			
				<p>Interested? Sign up now!</p>
				
				<div class="form-group">
				
					<input class="form-control" type = "email" name = "email" placeholder = "Your Email">
				
				</div>
				
				<div class="form-group">
				
					<input class="form-control" type = "password" name = "password" placeholder = "Password">
				
				</div>
				
				<div class="form-grop">
         
					<select class="form-control" id = "country" name="country" onchange="getID(this.value);">
					
						<option value="">Select Country</option>
						
						<?php
						
							include('connection.php');
							
							$query_countries = "SELECT * FROM countries";
							
							$results = mysqli_query($link, $query_countries);
							
							while($rowc = mysqli_fetch_array($results)){
							
								echo '<option value="'.$rowc['country_id'].'">'.$rowc['country'].'</option>';
						
							}
						
						?>
						
					</select>
					
				</div>
				
				<br>
				
				<div class="form-group">
					
					<select class="form-control" name="university" id="university">
					
						<option value="">Select Country First</option>
						
					</select>
					
				</div>

				
				<div class="form-check">
				
					<label class="form-check-label">
				
						<input type = "checkbox" name = "stayLoggedIn" value = "1">
					
						Stay logged in
					
					</label>
				</div>
				
				<div class="form-group">
				
					<input type = "hidden" name = "signUp" value = "1">

					<input class="btn btn-success" type = "submit" name = "submit" value = "Sign up">
				
				</div>
				
				<p><a class = "toggleForms">Sign in</a></p>
			</form>

			
			
			<form method = "post" id = "logInForm">
			
				<p>Please sign in with your email and password.</p>

				<div class="form-group">
				
					<input class="form-control" type = "email" name = "email" placeholder = "Your Email">
				
				</div>
				
				<div class="form-group">
				
					<input class="form-control" type = "password" name = "password" placeholder = "Password">
				
				</div>
				
				<div class="form-check">
					<label class="form-check-label">
					
						<input type = "checkbox" name = "stayLoggedIn" value = "1">
						
						Stay logged in
					
					</label>
				</div>
				
				<div class="form-group">
				
					<input type = "hidden" name = "signUp" value = "0">
					
					<input class="btn btn-success" type = "submit" name = "submit" value = "Sign in">
				
				</div>
				
				<p><a class = "toggleForms">Sign up</a></p>

			</form>
			
		</div>

	</body>
		
</html>

<?php include("footer.php"); ?>

