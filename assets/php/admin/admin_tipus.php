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
	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='tipus'";
	mysql_query($query)  or die ("A törlés sikertelen!". $query);
	$query="insert into a_temp (tmp_user, tmp_menu, tmp_s) values (".$_SESSION["userid"].",'tipus','".$_GET['tipfajta']."')";
	mysql_query($query)  or die ("Az insert sikertelen!". $query);
}

if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_tipusok set tip_del=1-tip_del where tip_id=".$id);
	if($query1){header('location:admin_tipus.php');}
}



if(isset($_POST['felvesz']))
{		
		$query="insert into a_tipusok (tip_fajta, tip_nev, tip_tipus, tip_egy, tip_del) values ('".$_POST[ffajta]."',".nulloz(ekezetcsere($_POST[fnev])).",".nulloz(ekezetcsere($_POST[ftipus])).",'".$_POST[fegy]."',0)";
		mysql_query($query) or die ("A felvétel sikertelen!". $query);
}


if(isset($_POST['modosit']))
{
//echo $_POST[fnev]." - ".ekezetcsere($_POST[fnev])." - ".nulloz(ekezetcsere($_POST[fnev]));
		$query="update a_tipusok set tip_nev=".nulloz(ekezetcsere($_POST[fnev])).", tip_tipus=".nulloz(ekezetcsere($_POST[ftipus])).", tip_egy='".$_POST[fegy]."' ";
		$query=$query." where tip_id=".$_POST[mid]; 
		mysql_query($query) or die ("A módosítás nem sikerült!". $query);
		unset($_GET['mozgat']);
}


//szûrés állapot elõállítása (a felvételben is használjuk, ezért kell itt)
$query="select tmp_s, tmp_i1 from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='tipus'";
$eredm=mysql_query($query);  //query futtatása
$sor=mysql_fetch_array($eredm);
$hely=$sor['tmp_s'];	


if ($hely=='')		{$hely="esemény";}

$ismen=' disabled'; $tipegy='-';
if ($hely=='esemény')	{$ismen=''; $tipegy='I';		} 

if(isset($_GET['mozgat'])) {
			$query2="select * from a_tipusok where tip_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtatása
			$sor2=mysql_fetch_array($eredm2);			//$sor2['']

			$mozgatid=$_GET['mozgat'];			$gnev='modosit';			$felirat='Módosít';				$tilt=' disabled';			$szin='#ffcc66';
			$tipfajta=$sor2['tip_fajta'];		$tipnev=$sor2['tip_nev'];	$tiptipus=$sor2['tip_tipus'];	$tipegy=$sor2['tip_egy'];
		 } 
	else {	$mozgatid=0;						$gnev='felvesz';			$felirat='Felvesz';				$tilt='';					$szin='#669999';
			$tipfajta=$_GET['fajta'];			$tipnev='';					$tiptipus='';					$tipegy='N';
		 }



echo "<form action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."' align='center'><tr><td width='90%'>";
	echo "<table border='3'><tr><th>Típus</th><th>Megnevezés</th><th>Jellemzõ</th><th>Ismétlõdõ esemény?</th></tr>";
		echo "<tr><td><input type='hidden' id='mid' name='mid' value='".$mozgatid."'>";
	
//		$query = "SELECT mtip_tip, mtip_hely from a_menutipus where mtip_tip='TIPUS' ";
//		$eredm = mysql_query($query);
//		echo "<SELECT name='ffajta' >";
//		while ($rek = mysql_fetch_array($eredm)) 
//			{	if ($hely==$rek["mtip_hely"]) {$sel=' selected';} else {$sel='';}
//				echo "<OPTION value='".$rek["mtip_hely"]."' ".$sel.">".$rek["mtip_hely"]."</OPTION>";	}
//		echo "</SELECT></td>";

		echo "<input type='text' name='ffajta' size='20' maxlength='20' value='".$hely."' readonly></td>";
		echo "<td><input type='text' name='fnev' size='40' maxlength='150' value='".$tipnev."'></td>";
		echo "<td><input type='text' name='ftipus' size='20' maxlength='250' value='".$tiptipus."'></td>";

		echo "<td><SELECT name='fegy' ".$ismen.">";
			if ($ismen!='') {if ('-'==$tipegy) {echo "<OPTION value='-' selected>-</OPTION>";}	else {echo "<OPTION value='-'>-</OPTION>";}}
			if ('N'==$tipegy) {echo "<OPTION value='N' selected>N</OPTION>";}	else {echo "<OPTION value='N'>N</OPTION>";}
			if ('I'==$tipegy) {echo "<OPTION value='I' selected>I</OPTION>";}	else {echo "<OPTION value='I'>I</OPTION>";}
		echo "</SELECT></td>";

	echo "</tr></table>";

echo "</td><td width='10%' ><input type='submit' name='".$gnev."' value='".$felirat."'></td></tr></table>";
echo "</form>";




//lista és szûrés rész

echo "<form action='admin_tipus.php' name='szures' method='get'>";
echo "<table border='0' bgcolor='gray' align='center'><tr><td>";
	echo "<table border='3'>";
	echo "<tr><th>Szótár típusa</th></tr>";
	echo "<tr><td>";
		$query = "SELECT * FROM a_menutipus where mtip_tip='TIPUS' order by mtip_hely";
		$eredm = mysql_query($query);
		echo "<SELECT name='tipfajta'>";
		while ($rek = mysql_fetch_array($eredm))
		{
			if ($rek["mtip_hely"]==$hely) {$sel=" selected";} else {$sel="";}
			echo "<OPTION value='".$rek["mtip_hely"]."' ".$sel.">".$rek["mtip_hely"]."</OPTION>";
		}
		echo "</SELECT></td>";
	echo "</tr></table>";
echo "</td><td>";
echo "<input type='submit' name='szures' value='szures'>";
echo "</td></tr></table>";
echo "</form>";

$query="select mtip_leiras, mtip_maxszint from a_menutipus where mtip_tip='TIPUS' and mtip_hely='".$hely."'";
$eredm=mysql_query($query);  //query futtatása
$sor=mysql_fetch_array($eredm);
echo "<p>".$sor['mtip_leiras']."</p>";	
if (($sor['mtip_maxszint']==-1) and (substr($_SESSION["jog"],0,1)!='A')) 
		 {$modosithato='N'; 
			echo "<p>Az itt szereplõ adatok csak rendszergazda joggal módosíthatóak, mivel módosításuk a honlap mûködését akadályozhatja (felvételük, pedig programozást igényel).</p>";	}
	else {$modosithato='I';}


$query="select * from a_tipusok T where tip_fajta='".$hely."' order by tip_fajta, tip_nev ";
$eredm=mysql_query($query);  //query futtatása

echo "<table class='naptar'><tr><th></th><th>ID</th><th>Típus</th><th>Megnevezés</th><th>Jellemzõ</th><th>Ismétlõdõ esemény?</th><th></th></tr>";
$v='</font>';
while($sor=mysql_fetch_array($eredm)) 
  {	if ($sor['tip_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
    if ($modosithato=='I') {
		$del="<a href='admin_tipus.php?delid=".$sor['tip_id']."'><img src='".$kep.".png' height='15'></a>";
		$mod="<a href='admin_tipus.php?mozgat=".$sor['tip_id']."'><img src='mozgat.png' height='15'></a>";
		}
		else {$del=''; $mod='';}

		echo "<tr><td>".$del."</td><td>".$szin.$sor['tip_id'].$v."</td><td>".$szin.$sor['tip_fajta'].$v."</td><td>".$szin.$sor['tip_nev'].$v."</td><td>".$szin.$sor['tip_tipus'].$v."</td><td>".$szin.$sor['tip_egy'].$v."</td>";
		echo "<td>".$mod."</td></tr>";
	  }

echo "</table>";


echo "</body>";
echo "</HTML>";
?>

