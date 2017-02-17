<?php
 session_start();
 if (!isset( $_SESSION["userid"])) die("Nincs bejelentkezve!");

require("../a_kapcs.inc.cS7.php");
dbkapcs();
$query="select * from a_menuk where m_hely='ADMINMENU' and m_sorrend=1"; 
$eredm=mysql_query($query);  //query futtatása
$sor=mysql_fetch_array($eredm);

echo "<HTML><HEAD><link rel='stylesheet' type='text/css' href='../cserlac.css' /></HEAD>";
echo "<frameset cols='150,*'   framespacing='0'>";
echo "			<frame src='admin_bal.php'		class='frame_bal'   name='frame_bal'	noresize='noresize' scrolling='no' >";
echo "			<frame src='".$sor['m_url']."'  class='frame_kozep' name='frame_kozep'  noresize='noresize' scrolling='auto'>";
echo "</frameset>";
echo "</HTML>";

?>