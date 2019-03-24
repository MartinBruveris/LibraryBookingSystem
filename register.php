<!doctype HTML>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="http://localhost/WebD/LibraryRevisited/styles/style.css">
	<title>Register</title>
</head>
<body>

	<div id = "navBar">
		<p>Register your account</p>
	</div>
	
	<form id = "regContainer" method = "POST">
		<label class = "regLabel" for = "username"><span>*</span> Username</label><input class = "regInput" type = "text" name = "username" size ="25" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>"><br>
		<label class = "regLabel" for = "password"><span>*</span> Password</label><input class = "regInput" type = "password" name = "password" size ="25" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>"><br>
		<label class = "regLabel" for = "confirmPassword"><span>*</span> Confirm Password</label><input class = "regInput" type = "password" name = "confirmPassword" size ="25" value="<?php echo isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '' ?>"><br>
		<label class = "regLabel" for = "firstname"><span>*</span> First Name</label><input class = "regInput" type = "text" name = "firstname" size ="25"  value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>"><br>
		<label class = "regLabel" for = "surname"><span>*</span> Last Name</label><input class = "regInput" type = "text" name = "surname" size ="25" value="<?php echo isset($_POST['surname']) ? $_POST['surname'] : '' ?>"><br>
		<label class = "regLabel" for = "addressLine1"><span>*</span> Address Line 1</label><input class = "regInput" type = "text" name = "addressLine1" size ="45" value="<?php echo isset($_POST['addressLine1']) ? $_POST['addressLine1'] : '' ?>"><br>
		<label class = "regLabel" for = "addressLine2"> Address Line 2</label><input class = "regInput" type = "text" name = "addressLine2" size ="45" value="<?php echo isset($_POST['addressLine2']) ? $_POST['addressLine2'] : '' ?>"><br>
		<label class = "regLabel" for = "city"><span>*</span> City</label><input class = "regInput" type = "text" name = "city" size ="15" value="<?php echo isset($_POST['city']) ? $_POST['city'] : '' ?>"><br>
		<label class = "regLabel" for = "telephone"><span>*</span> Telephone</label><input class = "regInput" type = "tel" name = "telephone" size ="10" value="<?php echo isset($_POST['telephone']) ? $_POST['telephone'] : '' ?>"><br>
		<label class = "regLabel" for = "mobile"><span>*</span> Mobile</label><input class = "regInput" type = "tel" name = "mobile" size ="10" value="<?php echo isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>"><br>
		<input class = "regLabel1" type = "submit" name = "create" value = "Sign Up">
		<input class = "regLabel2" type = "submit" name = "cancel" value = "Cancel Registration">
	</form>
	<p id="req"><span>*</span> required fields</p>
	
	<?php	
		require_once "connectToDatabase.php";
		if(isset($_POST['create']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confirmPassword']) && !empty($_POST['firstname'])
			&& !empty($_POST['surname']) && !empty($_POST['addressLine1']) && !empty($_POST['city']) && !empty($_POST['telephone'])
			&& !empty($_POST['mobile']))
		{
			$usn = mysql_real_escape_string($_POST['username']);
			$pass = mysql_real_escape_string($_POST['password']);
			$confP = mysql_real_escape_string($_POST['confirmPassword']);
			$first = mysql_real_escape_string($_POST['firstname']);
			$last = mysql_real_escape_string($_POST['surname']);
			$add1 = mysql_real_escape_string($_POST['addressLine1']);
			$add2 = mysql_real_escape_string($_POST['addressLine2']);
			$cty = mysql_real_escape_string($_POST['city']);
			$tph = mysql_real_escape_string($_POST['telephone']);
			$mob = mysql_real_escape_string($_POST['mobile']);
			if(strlen($pass) == 6)
			{
				if($pass == $confP)
				{
					if(is_numeric($tph) && strlen($tph) <= 10 && is_numeric($mob) && strlen($mob) <= 10 )
					{
						$query = "insert into users(username,password,firstname,surname,addressline1,addressline2,city,telephone,mobile)
						values(LOWER('$usn'),'$pass','$first','last','$add1','$add2','$cty','$tph','$mob')";
						if(mysql_query($query))
						{
							header('Location: registered.php');
						}
						else if(mysql_errno() === 1062)
						{
							echo('<p class = "error">Username '.$usn.' already exists, please try different username</p>');
						}
					}
					else if (!is_numeric($tph) || strlen($tph)>10)
					{
						echo('<p class = "error">Telephone number '.$tph.' is not a valid number</p>');
					}
					else if(!is_numeric($mob) || strlen($mob)>10)
					{
						echo('<p class = "error">Telephone number '.$mob.' is not a valid number</p>');
					}
					else if(strlen($mob)>10)
					{
						echo('<p class = "error">Telephone number '.$mob.' is too long</p>');
					}
					else if(strlen($tph)>10)
					{
						echo('<p class = "error">Telephone number '.$tph.' is too long</p>');
					}
				}
				else
				{
					echo("<p class = "."error".">Passwords doesn't match</p>");
				}
			}
			else
			{
				echo("<p class = "."error".">Passwords has to be 6 characters long!</p>");
			}
		}
		
		if(isset($_POST['create']) && (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirmPassword']) || empty($_POST['firstname'])
			|| empty($_POST['surname']) || empty($_POST['addressLine1']) || empty($_POST['city']) || empty($_POST['telephone'])
			|| empty($_POST['mobile'])))
		{
			echo('<p class = "error">Please fill in all required fields</p>');
		}
		
		if(isset($_POST['cancel']))
		{
			header('location: index.php');
		}
	?>	

</body>
</html>