<?php
define("IN_INDEX", true);

require "config/mysql.php";

if(!$_COOKIE['id']) {
	setcookie("id", 0, time()+60*60*24*30, "/");
}

$stmt = $handler->prepare("SELECT * FROM studenti WHERE ID=:id");
$stmt->execute(array(
		":id" => $_COOKIE['id']
));

$r = $stmt->fetch();

if(!$r) {
	include "prihlaseni.php";
} else {
	$sessionid = hash("sha256", $_COOKIE['sessionid']);
	$control = $r['sessionid'];
	if($control == $sessionid) {
		include "prehled.php";
	} else {
		include "prihlaseni.php";
	}
}

