<!doctype HTML>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="http://localhost/WebD/LibraryRevisited/styles/style.css">
	<title>Log in</title>
</head>
<body>
	<?php	
		include("createSession.php");
		if(isset($_SESSION['loggedInUser']))
		{
			header('Location: profile.php');
		}
		
		if(isset($_POST['register']))
		{
			header('Location: register.php');
		}
	?>
	
	<div id = "container">
		<div id = "login">
			<form method = "POST">
				<label for = "username">Username:</label><input type = "text" name = "username" value = "<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>"><br>
				<label for = "password">Password:</label><input type = "password" name = "password" value = "<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>"><br><br>
				<input type = "submit" name = "submit" value = "Log in">
				<input type = "submit" name = "register" value = "Register">
				<?php echo ('<p class = "loginError">'.$error.'</p>')?>
			</form>	
		</div>
	</div>
	
</body>
</html>	
