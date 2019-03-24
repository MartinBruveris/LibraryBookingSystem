<?php
	require_once "connectToDatabase.php";
	session_start();

	$confirm_user=$_SESSION['loggedInUser'];
	$check_sesion_sql=mysql_query("select * from users where username='$confirm_user'");
	$row = mysql_fetch_assoc($check_sesion_sql);
	$login_session =$row['firstname'];
	$login_session_username =$row['username'];
	
	if(!isset($login_session))
	{
		header('location: index.php'); 
	}
?>