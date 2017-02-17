<?php
 session_start();
 if (!isset( $_SESSION["userid"])) die("Nincs bejelentkezve!");

require("../a_kapcs.inc.cS7.php");
require("../a_ellenorzes.inc.php");
dbkapcs();


echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<body>";

if(isset($_GET['szures']))
{	
	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='eloado'";
	mysql_query($query)  or die ("A törlés sikertelen!". $query);
	$query="insert into a_temp (tmp_user, tmp_menu, tmp_s) values (".$_SESSION["userid"].",'eloado','".$_GET['fkeres1']."')";
	mysql_query($query)  or die ("Az insert sikertelen!". $query);
}

if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_szemelyek set szem_del=1-szem_del where szem_id=".$id);
	if($query1){header('location:admin_eloado.php');}
}



if(isset($_POST['felvesz']))
{		
		$query="insert into a_szemelyek (szem_nev, szem_hiv, szem_del) values (".nulloz(ekezetcsere($_POST[fnev])).",".nulloz(ekezetcsere($_POST[fhiv])).",0)";
		mysql_query($query) or die ("A felvétel sikertelen!". $query);
}


if(isset($_POST['modosit']))
{
		$query="update a_szemelyek set szem_nev=".nulloz(ekezetcsere($_POST[fnev])).", szem_hiv=".nulloz(ekezetcsere($_POST[fhiv]))." ";
		$query=$query." where szem_id=".$_POST[mid]; 
		mysql_query($query) or die ("A módosítás nem sikerült!". $query);
		unset($_GET['mozgat']);
}


//szûrés állapot elõállítása (a felvételben is használjuk, ezért kell itt)
$query="select tmp_s from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='eloado'";
$eredm=mysql_query($query);  //query futtatása
$sor=mysql_fetch_array($eredm);
$keres1=$sor['tmp_s'];	

if(isset($_GET['mozgat'])) {
			$query2="select * from a_szemelyek where szem_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtatása
			$sor2=mysql_fetch_array($eredm2);

			$mozgatid=$_GET['mozgat'];		$gnev='modosit';				$felirat='Módosít';			$tilt=' disabled';			$szin='#ffcc66';
			$szemnev=$sor2['szem_nev'];		$szemhiv=$sor2['szem_hiv'];		
		 } 
	else {	$mozgatid=0;					$gnev='felvesz';				$felirat='Felvesz';			$tilt='';					$szin='#669999';
			$szemnev='';					$szemhiv='';				
		 }



echo "<form action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."' align='center'><tr><td>";
	echo "<table border='3'><tr><th>Elõadó (személy, együttes...) neve</th><th>Web-címe</th></tr>";
		echo "<tr><td><input type='hidden' id='mid' name='mid' value='".$mozgatid."'>";
		echo "<input type='text' name='fnev' size='40' maxlength='80' value='".$szemnev."'></td>";
		echo "<td><input type='text' name='fhiv' size='60' maxlength='250' value='".$szemhiv."'></td>";
	echo "</tr></table>";
echo "</td><td width='10%' ><input type='submit' name='".$gnev."' value='".$felirat."'></td></tr></table>";
echo "</form>";


//lista és szûrés rész
echo "<form action='admin_eloado.php' name='szures' method='get'>";
echo "<table border='0' bgcolor='gray' align='center'><tr><td>";
	echo "<table border='3'>";
	echo "<tr><th>Elõadó neve</th></tr>";
	echo "<tr>";
		echo "<td><input type='text' name='fkeres1' size='20' maxlength='20' value='".$keres1."'></td>";
	echo "</tr></table>";
echo "</td><td>";
echo "<input type='submit' name='szures' value='Keres'>";
echo "</td></tr></table>";
echo "</form>";



$query="select * from a_szemelyek T where szem_nev like '%".$keres1."%' order by szem_nev ";
$eredm=mysql_query($query);  //query futtatása

echo "<table class='naptar'><tr><th></th><th>ID</th><th>Elõadó neve</th><th>Honlap címe</th><th></th></tr>";
$v='</font>';
while($sor=mysql_fetch_array($eredm)) 
  {	if ($sor['szem_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
		$del="<a href='admin_eloado.php?delid=".$sor['szem_id']."'><img src='".$kep.".png' height='15'></a>";
		$mod="<a href='admin_eloado.php?mozgat=".$sor['szem_id']."'><img src='mozgat.png' height='15'></a>";
		echo "<tr><td>".$del."</td><td>".$szin.$sor['szem_id'].$v."</td><td>".$szin.$sor['szem_nev'].$v."</td><td>".$szin."<a href='".$sor['szem_hiv']."' target='_blank'>".$sor['szem_hiv']."</a>".$v."</td>";
		echo "<td>".$mod."</td></tr>";
	  }
echo "</table>";

echo "</body>";
echo "</HTML>";
?>