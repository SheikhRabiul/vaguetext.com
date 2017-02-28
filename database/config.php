<?php
//globals
$domain_name='';	

function db_connect()
{
	$mysql_hostname = "localhost";
	//$mysql_user = "root";
	$mysql_user = "yourDBUserName";
	//$mysql_password = "";
	$mysql_password = "YourDBPassword";
	$mysql_database = "vtext";
	
	$mysqli= new mysqli($mysql_hostname, $mysql_user, $mysql_password,$mysql_database);
	if($mysqli->connect_error)
	{
		die("Database connection failed: ".$mysqli->connect_error);
	}
	return $mysqli;
}

function db_close($mysqli)
{
	$mysqli->close();
	
}

?>