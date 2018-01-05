<?php
$server = "localhost";
$user = "user";
$pass = "abc12345";
$dbname = "cryptoanalyzer";

try {
	$conn = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8", $user, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);					// Fixes a SQLi vulnerability
} catch (Exception $e) {													// that depends on the char
	echo $e->getMessage();													// encoding.
}
?>