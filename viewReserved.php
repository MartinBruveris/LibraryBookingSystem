<!doctype HTML>
	<?php
		include('checkSession.php');
	?>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="http://localhost/WebD/LibraryRevisited/styles/style.css">
	<title>View reserved books</title>
</head>
<body>

	<div id = "navBar">
		<p>Books reserved by you <?php echo ucfirst($login_session);?></p>
		<ul id = "navContainer">
			<li class = "navButtons"><a class = "navButtonsLink" href = "profile.php">main page</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "searchBook.php">search a book</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "reserveBook.php">reserve a book</a></li>
			<li class = "navButtons"><a id = "selected" class = "navButtonsLink" href = "viewReserved.php">view reserved books</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "logout.php">log out</a></li>
		</ul>
	</div>
	
	<h1>RESERVED BOOKS</h1>
	<hr>
	
	<?php
		$username = mysql_real_escape_string($login_session_username);
		$query = "Select reservations.isbn, bookTitle, edition, year, author, categorydescription, reservedDate, reserved from reservations join 
				books on reservations.isbn = books.isbn 
				join categories on books.category = categories.categoryID
				where username='$username' and reserved='Y' order by reservedDate desc";
		$result = mysql_query($query);
		$record_count = mysql_num_rows($result);	
		if (isset($_GET['pageno'])) 
		{
			$pageno = $_GET['pageno'];
		} 
		else 
		{
			$pageno = 1;
		}
	
		$rows_per_page = 5;
		$lastpage = ceil($record_count/$rows_per_page);
		$pageno = (int)$pageno;
		if ($pageno > $lastpage) 
		{
			$pageno = $lastpage;
		} 
		if ($pageno < 1) 
		{
			$pageno = 1;
		} 
		
		$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		$query = "Select reservations.isbn, bookTitle, edition, year, author, categorydescription, reservedDate, reserved from reservations join 
				books on reservations.isbn = books.isbn 
				join categories on books.category = categories.categoryID
				where username='$username' and reserved='Y' order by reservedDate desc $limit";
		$result = mysql_query($query);
		
		if(mysql_num_rows($result))
		{
			echo('<form method = "POST"><table><tr><th>ISBN</th><th>TITLE</th><th>EDITION</th><th>YEAR</th><th>AUTHOR</th><th>CATEGORY</th><th>DATE RESERVED</th><th>CANCEL RESERVATION?</th></tr>');
			while($row = mysql_fetch_array($result))
			{
				echo("<tr>");
				echo('<td class = "results">'.$row['isbn'].'</td>');
					echo('<td class = "results">'.$row['bookTitle'].'</td>');
					echo('<td class = "results">'.$row['edition'].'</td>');
					echo('<td class = "results">'.$row['year'].'</td>');
					echo('<td class = "results">'.$row['author'].'</td>');
					echo('<td class = "results">'.$row['categorydescription'].'</td>');
					echo('<td class = "results">'.$row['reservedDate'].'</td>');
				if($row['reserved'] == 'Y')
				{
					echo('<td class = "results"><button type = "submit" name = "cancel" value = "'.$row['isbn'].'">CANCEL RESERVATION</button></td>');
				}
				echo("</tr>");
			}
			
			echo('</table></form>');
			echo('<div class = "paginating">');
			if ($pageno == 1) 
			{
			   echo " FIRST PREV ";
			} 
			else 
			{
			   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=1'>FIRST</a>";
			   $prevpage = $pageno-1;
			   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'>PREV</a>";
			} 
			echo " ( Page $pageno of $lastpage ) ";
			if ($pageno == $lastpage) 
			{
			   echo " NEXT LAST ";
			} 
			else 
			{
			   $nextpage = $pageno+1;
			   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'>NEXT</a>";
			   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'>LAST</a>";
			}
			echo('</div>');
		}
		else
		{
			echo("<p id = "."notAvailableError".">You don't have any books reserved!</p>");
		}
		
		if(isset($_POST['cancel']))
		{	
			$isbn = $_POST['cancel']; 
			$bookQuery = "update books set reserved='N' where isbn='$isbn'";
			$reservationsQuery = "delete from reservations where isbn='$isbn' and username='$username'";
			mysql_query($bookQuery);
			mysql_query($reservationsQuery);
			header('location: viewReserved.php');
		}
	?>
</html>