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
	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='adkeres'";
	mysql_query($query)  or die ("A t�rl�s sikertelen!". $query);
	$query="insert into a_temp (tmp_user, tmp_menu, tmp_s) values (".$_SESSION["userid"].",'adkeres','".$_GET['fkeres1']."')";
	mysql_query($query)  or die ("Az insert sikertelen!". $query);
}

if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_adkeres set adk_del=1-adk_del where adk_id=".$id);
	if($query1){header('location:admin_adkeres.php');}
}



if(isset($_POST['felvesz']))
{		
	$filename = $_FILES["kep"]["tmp_name"];			// f�jl el�r�si �tja
	$data = getimagesize($filename);				// eredeti k�p m�retei
	$h = 500;										// �j k�p magass�g be�ll�t�sa
	$w=round(($h/$data[1])*$data[0]);				// a sz�less�get sz�m�tjuk az eredeti k�pb�l ar�nyosan
	$newimage = imagecreatetruecolor($w, $h);		// �res k�p l�trehoz�sa
	$oldimage = imagecreatefromjpeg($filename);		// eredeti k�p beolvas�sa

	$types = array("jpg", "jpeg");					// enged�lyezett kiterjeszt�sek
	$maxsize = 2000000;                             // maxim�lis m�ret (~2 MB) 1MB=1048576
	$query = "SELECT tip_tipus FROM a_tipusok where tip_del=0 and tip_fajta='KEP' and tip_nev='Ad/Keres'";
	$eredm = mysql_query($query);
	$rek = mysql_fetch_array($eredm);
	$hova=$rek["tip_tipus"];
	
	$target = $_SERVER['DOCUMENT_ROOT']."/".$hova;						// v�gleges hely

	// felt�lt�s ellen�rz�se
	if ($_FILES["kep"]["name"] == ""){  $name='nincs.png';  print "Nem t�lt�tt�l fel k�pet!";	}
	else{
	    $upload = true;
	    $name = removeaccent($_FILES["kep"]["name"]);
	    // kiterjeszt�s ellen�rz�se
	    $ext = strtolower(array_pop(explode(".", $name)));
	    if (!in_array($ext, $types)){														print "Csak k�p t�lthet� fel!";									$upload = false;    }
	    // m�ret ellen�rz�se
	    if (($_FILES["kep"]["size"] > $maxsize) or ($_FILES["kep"]["size"] ==0)){			print "T�l nagy a f�jl m�rete (max 2MB lehet)!";				$upload = false;    }
	    // �thelyez�s
	    if ($upload){
		////        move_uploaded_file($_FILES["kep"]["tmp_name"], $target."/".$name);
			imagecopyresampled($newimage, $oldimage, 0, 0, 0, 0, $w, $h, $data[0], $data[1]); // eredeti k�p r�m�sol�sa az �jra, �tm�retezve
			imagejpeg($newimage, $target.$name, 100);	// k�p ment�se
			echo '<script language="javascript">';		echo 'alert("Felt�lt�s megt�rt�nt!")';		echo '</script>';
		}
	}
	$query="insert into a_adkeres (adk_mit, adk_kep, adk_meddig, adk_felad_nev, adk_felad_email, adk_felad_telefon, adk_ad, adk_ar, adk_del) values ('".$_POST[fmit]."',";
	$query=$query."'/".$hova.$name."',".nulloz($_POST[fmeddig]).",'".$_POST[ffelad_nev]."','".$_POST[ffelad_email]."','".$_POST[ffelad_telefon]."','".$_POST[fad]."',".$_POST[far].",0)";
	mysql_query($query) or die ("A felv�tel sikertelen!". $query);
}


if(isset($_POST['modosit']))
{
		$query="update a_adkeres set adk_mit='".$_POST[fmit]."', adk_meddig=".nulloz($_POST[fmeddig]).", adk_ad='".$_POST[fad]."', adk_ar='".$_POST[far]."', ";
		$query=$query."adk_felad_nev='".$_POST[ffelad_nev]."', adk_felad_email='".$_POST[ffelad_email]."', adk_felad_telefon='".$_POST[ffelad_telefon]."' "; 
		$query=$query."where adk_id=".$_POST[mid]; 
		mysql_query($query) or die ("A m�dos�t�s nem siker�lt!". $query);
		unset($_GET['mozgat']);
}


//sz�r�s �llapot el��ll�t�sa (a felv�telben is haszn�ljuk, ez�rt kell itt)
$query="select tmp_s from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='adkeres'";
$eredm=mysql_query($query);  //query futtat�sa
$sor=mysql_fetch_array($eredm);
$keres1=$sor['tmp_s'];	


if(isset($_GET['mozgat'])) {
			$query2="select * from a_adkeres where adk_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtat�sa
			$sor2=mysql_fetch_array($eredm2);

			$mozgatid=$_GET['mozgat'];		$gnev='modosit';				$felirat='M�dos�t';			$tilt=' disabled';			$szin='#ffcc66';	$mrejt='hidden';	$frejt='';
			$mit=$sor2['adk_mit'];			$kep=$sor2['adk_kep'];			$meddig=$sor2['adk_meddig'];$felad_nev=$sor2['adk_felad_nev'];				$felad_email=$sor2['adk_felad_email'];	$felad_telefon=$sor2['adk_felad_telefon'];	
			$ad=$sor2['adk_ad'];			$ar=$sor2['adk_ar'];	
		 } 
	else {	$mozgatid=0;					$gnev='felvesz';				$felirat='Felvesz';			$tilt='';					$szin='#669999';	$mrejt='';			$frejt='hidden';
			$mit='';						$kep='';						$meddig='';					$felad_nev='';									$felad_email='';						$felad_telefon='';	
			$ad='I';						$ar='';	
		 }



echo "<form enctype='multipart/form-data' action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."' align='center'><tr><td>";
	echo "<table border='3'><tr><th>Mit adunk/keres�nk?</th><th>K�p linkje</th><th>Meddig legyen<br>megjelen�tve?</th></tr>";
		echo "<tr><td><input type='hidden' id='mid' name='mid' value='".$mozgatid."'>";
		echo "<textarea name='fmit' rows='3' cols='50' maxlength='500' >".$mit."</textarea></td>";
		echo "<td><label for='file' ".$mrejt."> V�lassz egy f�jlt (Max: 2MB jpg vagy jpeg )!</label><input id='file' type='file' name='kep' ".$mrejt."/><input type='text' name='fkep' size='70' maxlength='200' value='".$kep."' ".$tilt." ".$frejt."></td>";
		echo "<td><input type='text' name='fmeddig' size='10' maxlength='10' value='".$meddig."'></td>";
	echo "</tr></table>";
	echo "<table border='3'><tr><th>Hirdet� neve</th><th>E-mail c�me</th><th>Telefonsz�ma:</th><th>Ad?</th><th>�r</th></tr>";
		echo "<tr>";
		echo "<td><input type='text' name='ffelad_nev' size='40' maxlength='50' value='".$felad_nev."'></td>";
		echo "<td><input type='text' name='ffelad_email' size='65' maxlength='80' value='".$felad_email."'></td>";
		echo "<td><input type='text' name='ffelad_telefon' size='15' maxlength='15' value='".$felad_telefon."'></td>";
		echo "<td><SELECT name='fad' >";
			if ($ad=='I') {$sel=' selected';} else {$sel='';} echo "<OPTION value='I' ".$sel.">I</OPTION>";
			if ($ad=='N') {$sel=' selected';} else {$sel='';} echo "<OPTION value='N' ".$sel.">N</OPTION>";			
		echo "</SELECT></td>";
		echo "<td><input type='text' name='far' size='8' maxlength='8' value='".$ar."'></td>";
	echo "</tr></table>";
echo "</td><td width='10%' ><input type='submit' name='".$gnev."' value='".$felirat."'></td></tr></table>";
echo "</form>";


//lista �s sz�r�s r�sz
echo "<form action='admin_adkeres.php' name='szures' method='get'>";
echo "<table border='0' bgcolor='gray' align='center'><tr><td>";
	echo "<table border='3'>";
	echo "<tr><th>Ad?</th></tr>";
	echo "<tr>";
	echo "<td><SELECT name='fkeres1' >";
	if ($keres1=='%') {$sel=' selected';} else {$sel='';} echo "<OPTION value='%' ".$sel.">-</OPTION>";
	if ($keres1=='I') {$sel=' selected';} else {$sel='';} echo "<OPTION value='I' ".$sel.">I</OPTION>";
	if ($keres1=='N') {$sel=' selected';} else {$sel='';} echo "<OPTION value='N' ".$sel.">N</OPTION>";			
	echo "</SELECT></td>";
	echo "</tr></table>";
echo "</td><td>";
echo "<input type='submit' name='szures' value='Keres'>";
echo "</td></tr></table>";
echo "</form>";



$query="select * from a_adkeres T where adk_ad like '".$keres1."' order by adk_meddig desc ";  
$eredm=mysql_query($query);  //query futtat�sa

echo "<table class='naptar'><tr><th></th><th>ID</th><th>Mit</th><th>K�p linkje</th><th>K�p kin�zete:</th><th>Megjelen�t�se eddig:<th>Hirdet� neve</th><th>E-mail c�me</th><th>Telefonsz�ma:</th><th>Ad?</th><th>�r:</th><th></th></tr>";
$v='</font>';
while($sor=mysql_fetch_array($eredm)) 
  {	if ($sor['adk_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
		$del="<a href='admin_adkeres.php?delid=".$sor['adk_id']."'><img src='".$kep.".png' height='15'></a>";
		$mod="<a href='admin_adkeres.php?mozgat=".$sor['adk_id']."'><img src='mozgat.png' height='15'></a>";
		echo "<tr><td>".$del."</td><td>".$szin.$sor['adk_id'].$v."</td><td>".$szin.$sor['adk_mit'].$v."</td><td>".$szin."<a href='".$sor['adk_kep']."' target='_blank'>".$sor['adk_kep']."</a>".$v."</td><td><img src='".$sor['adk_kep']."' width='80'> </td><td>".$szin.$sor['adk_meddig'].$v."</td><td>".$szin.$sor['adk_felad_nev'].$v."</td><td>".$szin.$sor['adk_felad_email'].$v."</td><td>".$szin.$sor['adk_felad_telefon'].$v."</td><td>".$szin.$sor['adk_ad'].$v."</td><td>".$szin.$sor['adk_ar'].$v."</td>";
		echo "<td>".$mod."</td></tr>";
	  }
echo "</table>";

echo "</body>";
echo "</HTML>";
?>