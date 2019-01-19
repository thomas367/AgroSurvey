<?php

	$host="localhost";
	$username="root";
	$password="";
	$database="ptyxiaki";

	$connect=mysql_connect($host, $username, $password) or die("Cannot connect to host.");
	mysql_select_db($database);
	mysql_query("SET NAMES 'utf8'"); 
	mysql_query('SET CHARACTER SET utf8');

?>