<!doctype HTML>
	<?php
		include('checkSession.php');
	?>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="http://localhost/WebD/LibraryRevisited/styles/style.css">
	<title>Reserve a book</title>
</head>
<body>

	<div id = "navBar">
		<p> What book would you like to reserve <?php echo ucfirst($login_session);?>?</p>
		<ul id = "navContainer">
			<li class = "navButtons"><a class = "navButtonsLink" href = "profile.php">main page</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "searchBook.php">search a book</a></li>
			<li class = "navButtons"><a id = "selected" class = "navButtonsLink" href = "reserveBook.php">reserve a book</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "viewReserved.php">view reserved books</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "logout.php">log out</a></li>
		</ul>
	</div>
	
	<h1>RESERVE A BOOK</h1>
	<hr>
	
	<form id = "reserveBookSearchInput" method = "GET" action = "reserveBook.php">
		<label for = "manualRes">Enter ISBN number to reserve a book: </label><input type = "text" name = "manualRes">
		<input type = "submit" name = "reserve" value = "RESERVE">
	</form>
	<hr>
	
	<?php
		function chkReservStatuss()
		{
			$isbnNum = $_GET['manualRes'];
			$checkQuery = "select * from books where isbn='$isbnNum' and reserved='N'";
			if(mysql_query($checkQuery))
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		if(isset($_GET['available']))
		{
			$isbn = $_GET['available'];
			$isbn = mysql_real_escape_string($isbn);
			$username = mysql_real_escape_string($login_session_username);
			$query = "insert into reservations(isbn, username, reservedDate) values('$isbn', '$username', now())";
			$bookQuery = "update books set reserved='Y' where isbn='$isbn'";
			$bookDetails = "select isbn, booktitle, edition, year, author, categorydescription from books join categories on 
						books.category = categories.categoryid
						where isbn='$isbn'";
			$bookResult = mysql_query($bookQuery);
			$result = mysql_query($query);
			$bookDesc = mysql_query($bookDetails);
			$row = mysql_num_rows($bookDesc);
			$cols = mysql_num_fields($bookDesc);
			if($result && $bookResult)
			{
				header('location: viewReserved.php');
			}
		}
		else if(isset($_GET['manualRes']) && chkReservStatuss() && !empty($_GET['manualRes']))
		{
			$isbn = $_GET['manualRes'];
			$isbn = mysql_real_escape_string($isbn);
			$username = mysql_real_escape_string($login_session_username);
			$query = "insert into reservations(isbn, username, reservedDate) values('$isbn', '$username', now())";
			$bookQuery = "update books set reserved='Y' where isbn='$isbn'";
			$bookDetails = "select isbn, booktitle, edition, year, author, categorydescription from books join categories on 
						books.category = categories.categoryid
						where isbn='$isbn'";
			$result = mysql_query($query);
			$bookResult = mysql_query($bookQuery);			
			$bookDesc = mysql_query($bookDetails);
			$rows = mysql_fetch_array($bookDesc);
			$row = mysql_num_rows($bookDesc);
			$cols = mysql_num_fields($bookDesc);
			if($result && $bookResult)
			{
				header('location: viewReserved.php');
			}
			else
			{
				if(chkReservStatuss() && $rows['booktitle']!= null)
				{
					echo('<p id = "notAvailableError">Sorry, but '.$rows['booktitle'].' is already booked</p>');
				}
				if(!$rows['booktitle'])
				{
					echo('<p id = "notAvailableError">Sorry, but '.$_GET['manualRes'].' is not listed in our database</p>');
				}
			}
		}
		
		if(empty($_GET['manualRes']) && isset($_GET['manualRes']))
		{
			echo('<p id = "notAvailableError">Enter isbn number to reserve a book</p>');
		}
	?>
</html>