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
//	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='hirdkep'";
//	mysql_query($query)  or die ("A törlés sikertelen!". $query);
//	$query="insert into a_temp (tmp_user, tmp_menu, tmp_s) values (".$_SESSION["userid"].",'hirdkep','".$_GET['fkeres1']."')";
//	mysql_query($query)  or die ("Az insert sikertelen!". $query);
//}

if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_hirdetes_kepek set hkep_del=1-hkep_del where hkep_id=".$id);
	if($query1){header('location:admin_hirdeteskep.php');}
}



if(isset($_POST['felvesz']))
{		
	$filename = $_FILES["kep"]["tmp_name"];			// fájl elérési útja
	$data = getimagesize($filename);				// eredeti kép méretei
	$h = 500;										// új kép magasság beállítása
	$w=round(($h/$data[1])*$data[0]);				// a szélességet számítjuk az eredeti képbõl arányosan
	$newimage = imagecreatetruecolor($w, $h);		// üres kép létrehozása
	$oldimage = imagecreatefromjpeg($filename);		// eredeti kép beolvasása

	$types = array("jpg", "jpeg", "pdf");			// engedélyezett kiterjesztések
	$maxsize = 2000000;                             // maximális méret (~2 MB) 1MB=1048576
	$query = "SELECT tip_tipus FROM a_tipusok where tip_del=0 and tip_fajta='KEP' and tip_nev='Hirdetés'";
	$eredm = mysql_query($query);
	$rek = mysql_fetch_array($eredm);
	$hova=$rek["tip_tipus"];
	
	$target = $_SERVER['DOCUMENT_ROOT']."/".$hova;						// végleges hely

	// feltöltés ellenõrzése
	if ($_FILES["kep"]["name"] == ""){    print "Nem töltöttél fel képet!";	}
	else{
	    $upload = true;
	    $name = removeaccent($_FILES["kep"]["name"]);
	    // kiterjesztés ellenõrzése
	    $ext = strtolower(array_pop(explode(".", $name)));
	    if (!in_array($ext, $types)){														print "Csak kép tölthetõ fel!";									$upload = false;    }
	    // méret ellenõrzése
	    if (($_FILES["kep"]["size"] > $maxsize) or ($_FILES["kep"]["size"] ==0)){			print "Túl nagy a fájl mérete (max 2MB lehet)!";				$upload = false;    }
	    // áthelyezés
	    if ($upload){
		////        move_uploaded_file($_FILES["kep"]["tmp_name"], $target."/".$name);
			imagecopyresampled($newimage, $oldimage, 0, 0, 0, 0, $w, $h, $data[0], $data[1]); // eredeti kép rámásolása az újra, átméretezve
//			imagejpeg($newimage, $target.$name, 100);	// kép mentése
			echo '<script language="javascript">';		echo 'alert("Feltöltés megtörtént!")';		echo '</script>';
		}
	}
	$query="insert into a_hirdetes_kepek (hkep_link, hkep_kezd, hkep_ervenyes, hkep_tip_id, hkep_leiras, hkep_megjelenik, hkep_del) values ('/".$hova.$name."',".nulloz($_POST[fkezd]).",'".$_POST[fervenyes]."',".$_POST[ftip_id].",'".$_POST[fleir];
	$query=$query."','".pipa($_POST[fch1]).pipa($_POST[fch2]).pipa($_POST[fch3]).pipa($_POST[fch4]).pipa($_POST[fch5])."NNNNN',0)";
	mysql_query($query) or die ("A felvétel sikertelen!". $query);
}


if(isset($_POST['modosit']))
{
		$query="update a_hirdetes_kepek set hkep_kezd=".nulloz($_POST[fkezd]).", hkep_ervenyes='".$_POST[fervenyes]."', hkep_tip_id=".$_POST[ftip_id].", hkep_leiras='".$_POST[fleir]."', ";   
		$query=$query."hkep_megjelenik='".pipa($_POST[fch1]).pipa($_POST[fch2]).pipa($_POST[fch3]).pipa($_POST[fch4]).pipa($_POST[fch5])."NNNNN' ";
		$query=$query." where hkep_id=".$_POST[mid];											//hkep_link='".$_POST[flink]."', 
		mysql_query($query) or die ("A módosítás nem sikerült!". $query);
		unset($_GET['mozgat']);
}


//szûrés állapot elõállítása (a felvételben is használjuk, ezért kell itt)
//$query="select tmp_s from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='hirdkep'";
//$eredm=mysql_query($query);  //query futtatása
//$sor=mysql_fetch_array($eredm);
//$keres1=$sor['tmp_s'];	


if(isset($_GET['mozgat'])) {
			$query2="select * from a_hirdetes_kepek where hkep_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtatása
			$sor2=mysql_fetch_array($eredm2);

			$mozgatid=$_GET['mozgat'];		$gnev='modosit';				$felirat='Módosít';			$tilt=' disabled';			$szin='#ffcc66';	$mrejt='hidden';	$frejt='';
			$link=$sor2['hkep_link'];		$ervenyes=$sor2['hkep_ervenyes'];							$kezd=$sor2['hkep_kezd'];	$tip_id=$sor2['hkep_tip_id'];			$leir=$sor2['hkep_leiras'];
			if (substr($sor2['hkep_megjelenik'],0,1)=='I') {$ch1='checked';} else {$ch1='';}		//A php kódban levõ substr -nél az elsõ karakter a 0-s sorszámú, de MySQL-ben 1-s!!!!!!!!!!!!!!!!!!!!
			if (substr($sor2['hkep_megjelenik'],1,1)=='I') {$ch2='checked';} else {$ch2='';} 
			if (substr($sor2['hkep_megjelenik'],2,1)=='I') {$ch3='checked';} else {$ch3='';} 
			if (substr($sor2['hkep_megjelenik'],3,1)=='I') {$ch4='checked';} else {$ch4='';} 
			if (substr($sor2['hkep_megjelenik'],4,1)=='I') {$ch5='checked';} else {$ch5='';} 
		 } 
	else {	$mozgatid=0;					$gnev='felvesz';				$felirat='Felvesz';			$tilt='';					$szin='#669999';	$mrejt='';			$frejt='hidden';
			$link='';						$ervenyes='';												$kezd='';					$tip_id=1045;
			$ch1='';		 		$ch2='';				$ch3='';				$ch4='';				$ch5='';
		 }
echo "<form enctype='multipart/form-data' action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."' align='center'><tr><td>";
	echo "<table border='3'><tr><th>Kép linkje</th><th>Mikortól legyen megjelenítve<br>(ha rögtön, akkor<br>nem szükséges kitölteni)?</th><th>Meddig legyen<br>megjelenítve?</th><th>Megjelenítés<br>helye:</th><th>Honlap</th></tr>";
		echo "<tr><td><input type='hidden' id='mid' name='mid' value='".$mozgatid."'>";
		echo "<label for='file' ".$mrejt."> Válassz egy fájlt (Max: 2MB jpg vagy jpeg )!</label><input id='file' type='file' name='kep' ".$mrejt."/><input type='text' name='flink' size='100' maxlength='255' value='".$link."' ".$tilt." ".$frejt."><br>";
		echo "Leírás: <textarea name='fleir' rows='3' cols='50' maxlength='150' >".$leir."</textarea></td></td>";
		echo "<td><input type='text' name='fkezd' size='10' maxlength='10' value='".$kezd."'></td>";
		echo "<td><input type='text' name='fervenyes' size='10' maxlength='10' value='".$ervenyes."'></td>";
		echo "<td>";
		$query = "SELECT tip_id, tip_nev  FROM a_tipusok where tip_fajta='hkephely' and tip_del=0";
		$eredm = mysql_query($query);
		echo "<SELECT name='ftip_id'>";
		while ($rek = mysql_fetch_array($eredm))
		{   if ($rek["tip_id"]==$tip_id) {$sel='selected';} else {$sel='';}
			echo "<OPTION value='".$rek["tip_id"]."' ".$sel.">".$rek["tip_nev"]."</OPTION>";
		}
		echo "</SELECT></td>";
		echo "<td>";
		echo "<input type='checkbox' name='fch1' value='I' ".$ch1." >kassaiter<br>";
		echo "<input type='checkbox' name='fch2' value='I' ".$ch2." >kkakademia<br>";
//		echo "<input type='checkbox' name='fch3' value='I' ".$ch3." >jakikapolna<br>";
//		echo "<input type='checkbox' name='fch4' value='I' ".$ch4." >rozsafuzer<br>";
//		echo "<input type='checkbox' name='fch5' value='I' ".$ch5." >Credo<br>";
		echo "</td>";
	echo "</tr></table>";
echo "</td><td width='10%' ><input type='submit' name='".$gnev."' value='".$felirat."'></td></tr></table>";
echo "</form>";


//lista és szûrés rész
//echo "<form action='admin_hirdeteskep.php' name='szures' method='get'>";
//echo "<table border='0' bgcolor='gray' align='center'><tr><td>";
//	echo "<table border='3'>";
//	echo "<tr><th>Elõadó neve</th></tr>";
//	echo "<tr>";
//		echo "<td><input type='text' name='fkeres1' size='20' maxlength='20' value='".$keres1."'></td>";
//	echo "</tr></table>";
//echo "</td><td>";
//echo "<input type='submit' name='szures' value='Keres'>";
//echo "</td></tr></table>";
//echo "</form>";



$query="select * from a_hirdetes_kepek join a_tipusok on hkep_tip_id=tip_id  order by hkep_ervenyes desc ";  
$eredm=mysql_query($query);  //query futtatása

echo "<table class='naptar'><tr><th></th><th>ID</th><th>Kép linkje / Leírás</th><th>Kép kinézete:</th><th>Megjelenítése ettõl:<th>Megjelenítése eddig:</th><th>Hol</th><th>Honlap</th></tr>";
$v='</font>';
while($sor=mysql_fetch_array($eredm)) 
  {	if ($sor['hkep_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
		$del="<a href='admin_hirdeteskep.php?delid=".$sor['hkep_id']."'><img src='".$kep.".png' height='15'></a>";
		$mod="<a href='admin_hirdeteskep.php?mozgat=".$sor['hkep_id']."'><img src='mozgat.png' height='15'></a>";
		echo "<tr><td>".$del."</td><td>".$szin.$sor['hkep_id'].$v."</td><td>".$szin."<a href='".$sor['hkep_link']."' target='_blank'>".$sor['hkep_link']."</a>".$v."<br>".$szin.$sor['hkep_leiras'].$v."</td><td><img src='".$sor['hkep_link']."' width='80'> </td><td>".$szin.$sor['hkep_kezd'].$v."</td>";
		echo "<td>".$szin.$sor['hkep_ervenyes'].$v."</td><td>".$szin.$sor['tip_nev'].$v."</td><td align='left'>";
		if (substr($sor['hkep_megjelenik'],0,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>kassaiter<br>";
		if (substr($sor['hkep_megjelenik'],1,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>kkakademia<br>";
		if (substr($sor['hkep_megjelenik'],2,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>jakikapolna<br>";
		if (substr($sor['hkep_megjelenik'],3,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>rozsafuzer<br>";
		if (substr($sor['hkep_megjelenik'],4,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>Credo<br>";
//		if (substr($sor['hkep_megjelenik'],5,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>kassaiter<br>";
		echo "</td><td>".$mod."</td></tr>";
	  }
echo "</table>";

echo "</body>";
echo "</HTML>";
?>