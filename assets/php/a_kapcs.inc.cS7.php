<?php
function dbkapcs()
{
	$host="127.0.0.1";
	$user="sql_master";
	$pass="E1XRR9nMdMscyHQKIXTi";
	$db="kassaiter_dfuiduf456SD";
	global $kapcs;
	$kapcs=mysql_connect($host,$user,$pass) or die("Adatb�zis-hiba!");
	mysql_select_db($db,$kapcs) or die("Nem siker�lt kiv�lasztani az adatb�zist!");
    mysql_query('SET CHARACTER SET latin2');

	return $kapcs;
}
?>
