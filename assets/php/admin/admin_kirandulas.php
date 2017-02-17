<?php
 session_start();
 if (!isset( $_SESSION["userid"])) die("Nincs bejelentkezve!");

require("../a_kapcs.inc.cS7.php");
require("../a_ellenorzes.inc.php");
dbkapcs();


echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<body>";

//if(isset($_GET['szures']))
//{	
//	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='kirandulas'";
//	mysql_query($query)  or die ("A törlés sikertelen!". $query);
//	$query="insert into a_temp (tmp_user, tmp_menu, tmp_s, tmp_i1) values (".$_SESSION["userid"].",'kirandulas','".$_GET['fkeres1']."',".$_GET['fkeres2'].")";
//	mysql_query($query)  or die ("Az insert sikertelen!". $query);
//}

if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_kirandulas set kir_del=1-kir_del where kir_id=".$id);
	if($query1){header('location:admin_kirandulas.php');}
}



if(isset($_POST['felvesz']))
{		
		$query="insert into a_kirandulas (kir_taldate, kir_talhely, kir_nev, kir_leiras, kir_idotartam, kir_jelhatdate, kir_kapcsolat, kir_izelito, kir_del) values ";
		$query=$query."('".$_POST[taldate]."','".$_POST[talhely]."','".$_POST[nev]."','".$_POST[leiras]."','".$_POST[idotartam]."',".nulloz($_POST[jelhatdate]).",'".$_POST[kapcsolat]."',".nulloz($_POST[izelito]).",0)";
		mysql_query($query) or die ("A felvétel sikertelen!". $query);
}


if(isset($_POST['modosit']))
{
		$query="update a_kirandulas set ";
		$query=$query." kir_taldate='".$_POST[taldate]."', kir_talhely='".$_POST[talhely]."'";
		$query=$query.", kir_nev='".$_POST[nev]."', kir_leiras='".$_POST[leiras]."'";
		$query=$query.", kir_idotartam='".$_POST[idotartam]."', kir_jelhatdate=".nulloz($_POST[jelhatdate])." ";
		$query=$query.", kir_kapcsolat='".$_POST[kapcsolat]."'";
		$query=$query.", kir_izelito=".nulloz($_POST[izelito])." ";
		$query=$query." where kir_id=".$_POST[mid]; 
		mysql_query($query) or die ("A módosítás nem sikerült!". $query);
		unset($_GET['mozgat']);
}


//szûrés állapot elõállítása (a felvételben is használjuk, ezért kell itt)
//$query="select tmp_s, tmp_i1 from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='kirandulas'";
//$eredm=mysql_query($query);  //query futtatása
//$sor=mysql_fetch_array($eredm);
//$keres1=$sor['tmp_s'];	
//$keres2=$sor['tmp_i1'];


if(isset($_GET['mozgat'])) {
			$query2="select * from a_kirandulas where kir_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtatása
			$sor2=mysql_fetch_array($eredm2);

			$mozgatid=$_GET['mozgat'];			$gnev='modosit';					$felirat='Módosít';			$tilt=' disabled';					$szin='#ffcc66';
			$taldate=$sor2['kir_taldate'];		$talhely=$sor2['kir_talhely'];		$nev=$sor2['kir_nev'];		$leiras=$sor2['kir_leiras'];		$izelito=$sor2['kir_izelito'];
			$idotartam=$sor2['kir_idotartam'];	$jelhatdate=$sor2['kir_jelhatdate'];$kapcsolat=$sor2['kir_kapcsolat'];	
		 } 
	else {	
			$query="SELECT current_date() as taldate"; 
			$eredm=mysql_query($query);  
			$sor=mysql_fetch_array($eredm);

			$mozgatid=0;						$gnev='felvesz';				$felirat='Felvesz';				$tilt='';						$szin='#669999';
			$nap=substr($sor["taldate"],0,4);	$talhely='';					$nev='';						$leiras='';						$izelito='';
			$idotartam='';						$jelhatdate='';					$kapcsolat='';	
		 }
echo "<form action='' name='".$gnev."' method='post'>";
echo "<table border='3' bgcolor='".$szin."' align='center'>";
echo "<tr><th>Találkozás idõpontja<input type='hidden' id='mid' name='mid' value='".$mozgatid."'></th><td><input type='text' name='taldate' size='20' maxlength='25' value='".$taldate."'></td></tr>";
echo "<tr><th>Találkozási hely:</th><td><input type='text' name='talhely' size='50' maxlength='150' value='".$talhely."'></td></tr>";
echo "<tr><th>Kirándulás célpontja:</th><td><input type='text' name='nev' size='50' maxlength='150' value='".$nev."'></td></tr>";
echo "<tr><th>Részletes leírás</th><td><textarea name='leiras' rows='5' cols='80' maxlength='1500' >".$leiras."</textarea></td></tr>";
echo "<tr><th>Kirándulás idõtartama:</th><td><input type='text' name='idotartam' size='50' maxlength='50' value='".$idotartam."'></td></tr>";
echo "<tr><th>Jelentkezési határidõ:</th><td><input type='text' name='jelhatdate' size='20' maxlength='25' value='".$jelhatdate."'></td></tr>";
echo "<tr><th>Kapcsolat:</th><td><textarea name='kapcsolat' rows='2' cols='60' maxlength='150' >".$kapcsolat."</textarea></td></tr>";
echo "<tr><th>Kedvcsináló (url):</th><td><input type='text' name='izelito' size='100' maxlength='200' value='".$izelito."'></td></tr>";
echo "<tr><th colspan='2'><input type='submit' name='".$gnev."' value='".$felirat."'>";
echo "</table>";
echo "</form>";



//lista és szûrés rész
//echo "<form action='admin_kirandulas.php' name='szures' method='get'>";
//echo "<table border='0' bgcolor='gray' align='center'><tr><td>";
//	echo "<table border='3'>";
//	echo "<tr><th>Helyszín neve</th><th>Naptárba?</th></tr>";
//	echo "<tr>";
//		echo "<td><input type='text' name='fkeres1' size='20' maxlength='20' value='".$keres1."'></td>";
//		echo "<td><SELECT name='fkeres2' >";
//		if ($keres2=='') {$sel=' selected';} else {$sel='';} echo "<OPTION value='3' ".$sel.">-</OPTION>";
//		if ($keres2=='I') {$sel=' selected';} else {$sel='';} echo "<OPTION value='1' ".$sel.">I</OPTION>";
//		if ($keres2=='N') {$sel=' selected';} else {$sel='';} echo "<OPTION value='2' ".$sel.">N</OPTION>";			
//		echo "</SELECT></td>";
//	echo "</tr></table>";
//echo "</td><td>";
//echo "<input type='submit' name='szures' value='Keres'>";
//echo "</td></tr></table>";
//echo "</form>";


$query="select * from a_kirandulas ";
$query=$query."order by coalesce(kir_taldate) desc, kir_id desc";
$eredm=mysql_query($query);  //query futtatása

echo "<table class='naptar'><tr><th></th><th>ID</th><th>Találkozó idõpontja</th><th>Találkozó helye</th><th>Célpont</th><th>Leírás</th><th>Idõtartam</th><th>Jelentkezési határidõ</th><th>Kapcsolat</th><th>Kedvcsináló</th></tr>";
$v='</font>';
while($sor=mysql_fetch_array($eredm)) 
  {	if ($sor['kir_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
	$del="<a href='admin_kirandulas.php?delid=".$sor['kir_id']."'><img src='".$kep.".png' height='15'></a>";
	$mod="<a href='admin_kirandulas.php?mozgat=".$sor['kir_id']."'><img src='mozgat.png' height='15'></a>";
	echo "<tr><td>".$del."</td><td>".$szin.$sor['kir_id'].$v."</td><td>".$szin.$sor['kir_taldate'].$v."</td><td>".$szin.$sor['kir_talhely'].$v."</td><td>".$szin.$sor['kir_nev'].$v."</td>";
	echo "<td>".$szin.$sor['kir_leiras'].$v."</td><td>".$szin.$sor['kir_idotartam'].$v."</td><td>".$szin.$sor['kir_jelhatdate'].$v."</td>";   
	echo "<td>".$szin.$sor['kir_kapcsolat'].$v."</td><td>".$szin.$sor['kir_izelito'].$v."</td>";
	echo "<td>".$mod."</td></tr>";
  }
echo "</table>";

echo "</body>";
echo "</HTML>";
?>