<!doctype HTML>
	<?php
		include('checkSession.php');
	?>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="http://localhost/WebD/LibraryRevisited/styles/style.css">
	<title>Search a book</title>
</head>
<body>

	<div id = "navBar">
		<p> What book would like to find <?php echo ucfirst($login_session);?>?</p>
		<ul id = "navContainer">
			<li class = "navButtons"><a class = "navButtonsLink" href = "profile.php">main page</a></li>
			<li class = "navButtons"><a id = "selected" class = "navButtonsLink" href = "searchBook.php">search a book</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "reserveBook.php">reserve a book</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "viewReserved.php">view reserved books</a></li>
			<li class = "navButtons"><a class = "navButtonsLink" href = "logout.php">log out</a></li>
		</ul>
	</div>

	<h1>SEARCH A BOOK</h1>
	<hr>
	<form id = "searchInputs" method = "GET">
		<label class = "categoryLabel" for = "byAuthor">Search By Author: </label><input class = "search" type = "text" name = "byAuthor">
		<label class = "categoryLabel" for = "byTitle">Search By Book Title: </label><input class = "search" type = "text" name = "byTitle">
		<label class = "categoryLabel" for = "byCategory">Search By Book Category: </label>
		<input class = "search" list = "category" name = "byCategory" value = "select category">
			<datalist id = "category">
			<?php
				echo'<option value="" selected></option>';
				$list = "select * from categories";
				$ListResult = mysql_query($list);
				while($option = mysql_fetch_array($ListResult)) 
				{
					echo'<option value="'.$option['categoryDescription'].'">'.$option['categoryDescription'].'</option>';	
				}
			?>
			</datalist>
		<input type = "submit" name = "search" value = "Search">
	</form>
	<hr>
	
	<?php	
		function printResult($result, $record_count)
		{
			
			$author = $_SESSION['author'];
			$title = $_SESSION['title'];
			$category = $_SESSION['category'];
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
			$query = "select isbn, booktitle, edition, year, categorydescription, author, reserved from books join categories on 
						books.category = categories.categoryid
						where author like '$author' or bookTitle like '$title' or categoryDescription='$category' $limit";
			
			if($result = mysql_query($query))
			{
				echo('<form method = "GET" action = "reserveBook.php"><table><tr><th>ISBN NUMBER</th><th>TITLE</th><th>EDITION</th><th>YEAR</th><th>CATEGORY</th><th>AUTHOR</th><th>AVAILABILITY</th></tr>');
				while($row = mysql_fetch_array($result)) 
				{
					echo("<tr>");
					echo('<td class = "results">'.$row['isbn'].'</td>');
					echo('<td class = "results">'.$row['booktitle'].'</td>');
					echo('<td class = "results">'.$row['edition'].'</td>');
					echo('<td class = "results">'.$row['year'].'</td>');
					echo('<td class = "results">'.$row['categorydescription'].'</td>');
					echo('<td class = "results">'.$row['author'].'</td>');
					
					if($row['reserved'])
					{
						if($row['reserved'] == 'N')
						{
							echo('<td class = "results"><button type = "submit" name = "available" value = "'.$row['isbn'].'">RESERVE NOW</button></td>');
						}
						if($row['reserved'] == 'Y')
						{
							echo('<td class = "results">NOT AVAILABLE</td>');
						}
					}
				}
				echo("</table></form>");
			}
			else
			{
				echo('<p id = "notAvailableError">NOTHING FOUND!</p>');
			}
			
			echo('<div class = "paginating">');
			if ($pageno == 1) 
			{
			   echo ' FIRST PREV ';
			} 
			else 
			{
			   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=1'> FIRST </a> ";
			   $prevpage = $pageno-1;
			   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'> PREV </a>";
			} 
			echo "(Page $pageno of $lastpage )";
			
			if ($pageno == $lastpage) 
			{
			   echo " NEXT LAST </div>";
			} 
			else 
			{
			   $nextpage = $pageno+1;
			   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'> NEXT </a>";
			   echo "<a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'> LAST </a>";
			}
			echo('</div>');
		}

		if(isset($_GET['byAuthor']) || isset($_GET['byTitle']) || isset($_GET['byCategory']) && isset($_GET['search']) )
		{
			$author = ($_GET['byAuthor']);
			$title = ($_GET['byTitle']);
			$category = ($_GET['byCategory']);
			$author = mysql_real_escape_string($author);
			$title = mysql_real_escape_string($title);
			$category = mysql_real_escape_string($category);
			if(!empty($_GET['byAuthor']))
			{
				$author = '%'.$author.'%';
			}
			if(!empty($_GET['byTitle']))
			{
				$title = '%'.$title.'%';
			}
			$_SESSION['author'] = $author;
			$_SESSION['title'] = $title;
			$_SESSION['category'] = $category;
			$query = "select isbn, booktitle, edition, year, categorydescription, author, reserved from books join categories on 
						books.category = categories.categoryid
						where author like '$author' or bookTitle like '$title' or categoryDescription='$category'";
			$result = mysql_query($query);
			$record_count = mysql_num_rows($result);
			$_SESSION['result'] = $result;
			$_SESSION['recCount'] = $record_count;
			if($record_count = mysql_num_rows($result))
			{
				printResult($result, $record_count);
			}
			else
			{
				echo('<p id="notAvailableError">NOTHING FOUND!</p>');
			}
		}
		else if(isset($_GET['pageno']))
		{
			$result = $_SESSION['result'];
			$record_count = $_SESSION['recCount'];
			printResult($result, $record_count);
		}
	?>
</html>