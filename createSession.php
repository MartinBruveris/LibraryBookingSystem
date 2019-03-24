<?php
	require_once "connectToDatabase.php";
	session_start(); 
	$error=''; 
	
	if(isset($_POST['submit'])) 
	{
		if(empty($_POST['username']) && !empty($_POST['password']))
		{
			$error = "Username Required";
		}
		if(empty($_POST['password']) && !empty($_POST['username']))
		{
			$error = "Password required";
		}
	}
	
	if(!empty($_POST['username']) && !empty($_POST['password']) && isset($_POST['submit']))
	{
		$username = ($_POST['username']);
		$password = ($_POST['password']);
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$query = mysql_query("select * from users where password='$password' AND username='$username'");
		$rows = mysql_num_rows($query);
		if($rows == 1) 
		{
			$_SESSION['loggedInUser'] = $username; 
			$_SESSION['userPassword'] = $password;
		} 
		else 
		{
			$error = "Username or Password is invalid";
		}
	}
	
	else if(empty($_POST['username']) && empty($_POST['password']) && isset($_POST['submit']))
	{
		$error = "Enter username and password!";
	}
?>