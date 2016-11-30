<?php
defined("IN_INDEX") or die("<p style='color:red;font-weight:bold'>You don't have permission to access this file!</p>");

try {
	$handler = new PDO('mysql:host=localhost;dbname=skolnidb','Daemon','6666666');
	$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$handler->query("SET NAMES 'utf8'");
} catch(PDOException $e) {
	echo("Server couldn't connect to database! <br />Error: " . $e->getMessage());
}