<?php
// file: db_connection.php

$dbhost = "127.0.0.1";
$dbname = "tswblog";
$dbuser = "tswuser";
$dbpass = "tswblogpass";

$db = new PDO(
	"mysql:host=$dbhost;dbname=$dbname;charset=utf8", // connection string
	$dbuser, 
	$dbpass, 
	array( // options
	  PDO::ATTR_EMULATE_PREPARES => false,
	  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	)
);
