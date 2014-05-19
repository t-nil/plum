<?php
if(!isset($_GET["name"]) || !isset($_GET["key"]))
	die("Invalid call. Nice try!");
	
require_once("config.inc.php");

$_GET["name"] = urldecode($_GET["name"]);

$result = mysql_query("SELECT * FROM users WHERE name='".mysql_real_escape_string($_GET["name"])."' AND is_active=false");

if(mysql_num_rows($result) == 0)
	die("Non-existent account or already activated. Nice try!");
	
$row = mysql_fetch_assoc($result);

if($_GET["key"] != $row["pw"])
	die("Invalid key. Nice try!");

mysql_query("UPDATE users SET is_active=true WHERE id={$row["id"]}");
	die("Account successfully activated!");
?>