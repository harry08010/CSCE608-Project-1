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
	
?>

<!DOCTYPE html>

<html>

	<head>
	
		<title>TYE | Home</title>
		
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
		
		<p id = "welcome">Welcome 
		
			<?php    
			
				include('connection.php');
				
				$user_first_name = "";
			
				$welcome = "SELECT * FROM students WHERE stu_id = ".$_SESSION['stu_id'];
				
				$welcome_result = mysqli_fetch_array(mysqli_query($link, $welcome));
				
				$user_first_name = $welcome_result['first_name'];
				
				echo $user_first_name;
			
			?>!</p>
		
		<div id="card1" style="width: 20rem;">
		
			<img class="card-img-top" src="pics/logpic1.jpg" alt="Card image cap">
			
			<div class="card-block">
			
			<h4 class="card-title">New</h4>
			
			<p class="card-text">Spend money on some things today? Note them here.</p>
			
				<a href="add.php" class="btn btn-primary">Let's go</a>
				
			</div>
			
		</div>
		
		<div id="card2" style="width: 20rem;">
		
			<img class="card-img-top" src="pics/logpic2.jpg" alt="Card image cap">
			
			<div class="card-block">
			
			<h4 class="card-title">Record</h4>
			
			<p class="card-text">History of your expenses. Why not have a look?</p>
			
				<a href="history.php" class="btn btn-primary">Let's go</a>
				
			</div>
			
		</div>
		
		<div id="card3" style="width: 20rem;">
		
			<img class="card-img-top" src="pics/logpic3.jpg" alt="Card image cap">
			
			<div class="card-block">
			
			<h4 class="card-title">Trend</h4>
			
			<p class="card-text">Track your spending trending in the past few months.</p>
			
				<a href="trend.php" class="btn btn-primary">Let's go</a>
				
			</div>
			
		</div>

	</body>
		
</html>
	
	
<?php

	include("footer.php");

?>