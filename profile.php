<!doctype HTML>
	<?php
		include('checkSession.php');
	?>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="http://localhost/WebD/LibraryRevisited/styles/style.css">
	<title>Welcome <?php echo ucfirst($login_session);?></title>
</head>
<body>

	<div id = "navBar">
		<p>Hello <?php echo ucfirst($login_session);?>!</p>
	</div>
	
	<ul id = "mainMenu">
		<li class = "mainMenuButton"><a class = "mainMenuButtonLink" href = "searchBook.php">Search for a book</a></li>
		<li class = "mainMenuButton"><a class = "mainMenuButtonLink" href = "reserveBook.php">Reserve a book</a></li>
		<li class = "mainMenuButton"><a class = "mainMenuButtonLink" href = "viewReserved.php">View reserved books</a></li>
		<li class = "mainMenuButton"><a class = "mainMenuButtonLink" href = "logout.php">Log Out</a></li>
	</ul>
	
</html>

