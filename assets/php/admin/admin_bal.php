<?php

session_start();
if (!isset( $_SESSION["userid"])) die("Nincs bejelentkezve!");

echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<body  class='fuggoleges'>";

require("../a_kapcs.inc.cS7.php");
dbkapcs();

$jogok=$_SESSION["jog"];
switch (substr($_SESSION["jog"],0,1))	{
	   case "F" : $jog1=" in ('F') ";		break;  
	   case "A" : $jog1=" in ('F','A') ";	break;  
	   default:	$jog1=" in ('')";		}
switch (substr($_SESSION["jog"],1,1))	{
	   case "F" : $jog2=" in ('F') ";		break;  
	   case "A" : $jog2=" in ('F','A') ";	break;  
	   default:	$jog2=" in ('')";		}

echo "<div id='navigation'>";
echo "<ul><li><a href='http://kassaiter.hu/index.html' target='frame_kozep'>Honlap n�zet</a></li></ul>";
//echo "<ul><li><a href='http://kassaiter.hu/index.php' target='frame_kozep'>Kor�bbi honlap</a></li></ul>";
echo "<br>";

echo "<div style='background-color:yellow; text-align:center;'><i>Bel�pett felhaszn�l�:</i><br><b>".$_SESSION["nev"]."</b></div>";
echo "<br>";

echo "<ul>";
   $query="SELECT m_id, m_nev, case when m_url is null then concat(m_nev) else concat('<a href=''',m_url,''' target=''',m_target,'''>',m_nev,'</a>') end as menu FROM a_menuk ";
   $query=$query."WHERE m_hely='ADMINMENU' and m_del=0 and (substring(m_jog,1,1) ".$jog1." or substring(m_jog,2,1) ".$jog2.") order by m_sorrend";
   $query_eredm=mysql_query($query);
   while($eredm=mysql_fetch_array($query_eredm)){
    echo "<li>";
	  echo $eredm['menu']; 

    echo "</li>";  
   }
echo "</ul>";

echo "<br>";
echo "<ul><li><a href='admin_logout.php' target='_top'>Kil�p�s</a></li></ul>";

echo "<br>";
echo "<br>";


echo "</div>";

echo "<table class='lent' bgcolor='#000000'>";
echo "<tr><td></td><td>Jelmagyar�zat</td></tr>";
echo "<tr><td><table align='center'><tr><td><img src='torol.png'  height='15'></td><td><img src='beir.png'   height='12' ></td></tr></table></td><td>T�r�l/Visszahoz</td></tr>";
//echo "<tr><td></td><td>Visszahoz</td></tr>";
echo "<tr><td><img src='tolt.png'   height='12' ></td><td>Felt�lt</td></tr>";
echo "<tr><td><img src='mozgat.png' height='12' ></td><td>�thelyez, m�dos�t</td></tr>";
echo "<tr><td><img src='delez.png'  height='12' ></td><td>V�gleges t�rl�s</td></tr>";
echo "</table>";

echo "</body></HTML>";
?>

