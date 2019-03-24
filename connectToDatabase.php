<?php
	$db = mysql_connect('localhost', 'root', '','book');
	if($db === FALSE)
	{
		die('Whoops, some connection problems here');
	}
	if(mysql_select_db("book") === FALSE)
	{
		die("Can't connect to book database");
	}
?>

