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
	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='helyszin'";
	mysql_query($query)  or die ("A törlés sikertelen!". $query);
	$query="insert into a_temp (tmp_user, tmp_menu, tmp_s, tmp_i1) values (".$_SESSION["userid"].",'helyszin','".$_GET['fkeres1']."',".$_GET['fkeres2'].")";
	mysql_query($query)  or die ("Az insert sikertelen!". $query);
}

if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_helyszinek set hely_del=1-hely_del where hely_id=".$id);
	if($query1){header('location:admin_helyszin.php');}
}



if(isset($_POST['felvesz']))
{		
		$query="insert into a_helyszinek (hely_tip_id, hely_naploba, hely_megnev, hely_cim, hely_rnev, hely_link, hely_del) values (".$_POST[ftip_id].",'".$_POST[fnaploba]."',".nulloz(ekezetcsere($_POST[fmegnev])).",".nulloz(ekezetcsere($_POST[fcim])).",".nulloz(ekezetcsere($_POST[frnev])).",".nulloz(ekezetcsere($_POST[flink])).",0)";
		mysql_query($query) or die ("A felvétel sikertelen!". $query);
}


if(isset($_POST['modosit']))
{
		$query="update a_helyszinek set hely_tip_id=".$_POST[ftip_id]." , hely_naploba='".$_POST[fnaploba]."' , hely_megnev= ".nulloz(ekezetcsere($_POST[fmegnev])).", hely_cim=".nulloz(ekezetcsere($_POST[fcim]))." , hely_rnev=".nulloz(ekezetcsere($_POST[frnev]))." , hely_link=".nulloz(ekezetcsere($_POST[flink]));
		$query=$query." where hely_id=".$_POST[mid]; 
		mysql_query($query) or die ("A módosítás nem sikerült!". $query);
		unset($_GET['mozgat']);
}


//szûrés állapot elõállítása (a felvételben is használjuk, ezért kell itt)
$query="select tmp_s, tmp_i1 from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='helyszin'";
$eredm=mysql_query($query);  //query futtatása
$sor=mysql_fetch_array($eredm);
$keres1=$sor['tmp_s'];	
switch ($sor['tmp_i1']) {
	case 1: $keres2='I'; break;
	case 2: $keres2='N'; break;
	case 3: $keres2=''; break;
}


if(isset($_GET['mozgat'])) {
			$query2="select * from a_helyszinek where hely_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtatása
			$sor2=mysql_fetch_array($eredm2);

			$mozgatid=$_GET['mozgat'];		$gnev='modosit';				$felirat='Módosít';				$tilt=' disabled';				$szin='#ffcc66';
			$tip_id=$sor2['hely_tip_id'];	$naploba=$sor2['hely_naploba'];	$megnev=$sor2['hely_megnev'];	$cim=$sor2['hely_cim'];			$rnev=$sor2['hely_rnev'];	$link=$sor2['hely_link'];
		 } 
	else {	$mozgatid=0;					$gnev='felvesz';				$felirat='Felvesz';				$tilt='';						$szin='#669999';
			$tip_id=100;					$naploba='N';					$megnev='';						$cim='';						$rnev='';					$link='';
		 }



echo "<form action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."' align='center'><tr><td>";
	echo "<table id='bevitel' border='3'>"; 
	echo "<tr><th>Helyszín megnevezése<input type='hidden' id='mid' name='mid' value='".$mozgatid."'></th><th>Hely rövid neve</th><th>Hely típusa</th><th>Naptárba</th></tr>";
	echo "<tr>";
	echo "<td><input type='text' name='fmegnev' size='60' maxlength='250' value='".$megnev."'></td>";
	echo "<td><input type='text' name='frnev' size='40' maxlength='50' value='".$rnev."'></td>";
	echo "<td><SELECT name='ftip_id' >";
	$query="select tip_id, tip_nev from a_tipusok where tip_fajta='Helyszín' and tip_del=0 order by tip_nev ";
	$eredm=mysql_query($query);  //query futtatása
	while($sor=mysql_fetch_array($eredm)) 
	{ if ($sor['tip_id']==$tip_id) {$sel=' selected';} else {$sel='';}
		echo "<OPTION value='".$sor["tip_id"]."' ".$sel.">".$sor["tip_nev"]."</OPTION>";	}
	echo "</SELECT></td>";
	echo "<td><SELECT name='fnaploba' >";
	if ($naploba=='I') {$sel=' selected';} else {$sel='';} echo "<OPTION value='I' ".$sel.">I</OPTION>";
	if ($naploba=='N') {$sel=' selected';} else {$sel='';} echo "<OPTION value='N' ".$sel.">N</OPTION>";			
	echo "</SELECT></td>";
	echo "</tr>"; 

	echo "<tr><th colspan='1'>Helyszín címe</th><th colspan='3'>Honlap link</th></tr>";
	echo "<tr>";
	echo "<td colspan='1'><input type='text' name='fcim' size='60' maxlength='100' value='".$cim."'></td>";
	echo "<td colspan='3'><input type='text' name='flink' size='80' maxlength='250' value='".$link."'></td>";
	echo "</tr>"; 
	echo "</table>";
echo "</td><td width='10%' ><input type='submit' name='".$gnev."' value='".$felirat."'></td></tr></table>";
echo "</form>";


//lista és szûrés rész
echo "<form action='admin_helyszin.php' name='szures' method='get'>";
echo "<table border='0' bgcolor='gray' align='center'><tr><td>";
	echo "<table border='3'>";
	echo "<tr><th>Helyszín neve</th><th>Naptárba?</th></tr>";
	echo "<tr>";
		echo "<td><input type='text' name='fkeres1' size='20' maxlength='20' value='".$keres1."'></td>";
		echo "<td><SELECT name='fkeres2' >";
		if ($keres2=='') {$sel=' selected';} else {$sel='';} echo "<OPTION value='3' ".$sel.">-</OPTION>";
		if ($keres2=='I') {$sel=' selected';} else {$sel='';} echo "<OPTION value='1' ".$sel.">I</OPTION>";
		if ($keres2=='N') {$sel=' selected';} else {$sel='';} echo "<OPTION value='2' ".$sel.">N</OPTION>";			
		echo "</SELECT></td>";
	echo "</tr></table>";
echo "</td><td>";
echo "<input type='submit' name='szures' value='Keres'>";
echo "</td></tr></table>";
echo "</form>";



$query="select T.*, tip_nev from a_helyszinek T join a_tipusok on hely_tip_id=tip_id where hely_megnev like '%".$keres1."%' and hely_naploba like '%".$keres2."%' order by hely_megnev ";
$eredm=mysql_query($query);  //query futtatása

echo "<table class='naptar'><tr><th></th><th>ID</th><th>Helyszín típusa</th><th>Naplóba?</th><th>Helyszín megnevezése</th><th>Helyszín rövidneve</th><th>Helyszín címe</th><th>Honlap címe</th><th></th></tr>";
$v='</font>';
while($sor=mysql_fetch_array($eredm)) 
  {	if ($sor['hely_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
		$del="<a href='admin_helyszin.php?delid=".$sor['hely_id']."'><img src='".$kep.".png' height='15'></a>";
		$mod="<a href='admin_helyszin.php?mozgat=".$sor['hely_id']."'><img src='mozgat.png' height='15'></a>";
		echo "<tr><td>".$del."</td><td>".$szin.$sor['hely_id'].$v."</td><td>".$szin.$sor['tip_nev'].$v."</td><td>".$szin.$sor['hely_naploba'].$v."</td><td>".$szin.$sor['hely_megnev'].$v."</td>";
		echo "<td>".$szin.$sor['hely_rnev'].$v."</td><td>".$szin.$sor['hely_cim'].$v."</td><td>".$szin."<a href='".$sor['hely_link']."' target='_blank'>".$sor['hely_link']."</a>".$v."</td>";
		echo "<td>".$mod."</td></tr>";
	  }
echo "</table>";

echo "</body>";
echo "</HTML>";
?>