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
//	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='program'";
//	mysql_query($query)  or die ("A törlés sikertelen!". $query);
//	$query="insert into a_temp (tmp_user, tmp_menu, tmp_s, tmp_i1) values (".$_SESSION["userid"].",'program','".$_GET['fkeres1']."',".$_GET['fkeres2'].")";
//	mysql_query($query)  or die ("Az insert sikertelen!". $query);
//}

if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_programok set prg_del=1-prg_del where prg_id=".$id);
	if($query1){header('location:admin_program.php');}
}



if(isset($_POST['felvesz']))
{		
		$query="insert into a_programok (prg_nap, prg_ido, prg_tip_id, prg_hely_id, prg_szem_id_1, prg_szem_id_2, prg_leiras, prg_leirlink, prg_reszletes, prg_arkat, prg_megjelenik, prg_del) values ";
		$query=$query."('".$_POST[datum]."','".$_POST[ido]."',".$_POST[esemeny].",".$_POST[helyszin].",".nulloz(ekezetcsere($_POST[eloado1])).",".nulloz(ekezetcsere($_POST[eloado2])).",'".ekezetcsere($_POST[leir])."',";
		$query=$query.nulloz($_POST[leirlink]).",".nulloz($_POST[reszletes]).",'".$_POST[arkat]."','".pipa($_POST[fch1]).pipa($_POST[fch2]).pipa($_POST[fch3]).pipa($_POST[fch4]).pipa($_POST[fch5])."NNNNN',0)";
		mysql_query($query) or die ("A felvétel sikertelen!". $query);
}


if(isset($_POST['modosit']))
{
		$query="update a_programok set ";
		$query=$query." prg_nap='".$_POST[datum]."', prg_ido='".$_POST[ido]."'";
		$query=$query.", prg_tip_id=".$_POST[esemeny].", prg_hely_id=".$_POST[helyszin];
		$query=$query.", prg_szem_id_1=".nulloz(ekezetcsere($_POST[eloado1])).", prg_szem_id_2=".nulloz(ekezetcsere($_POST[eloado2]));
		$query=$query.", prg_leiras='".ekezetcsere($_POST[leir])."', prg_leirlink=".nulloz($_POST[leirlink]);
		$query=$query.", prg_reszletes=".nulloz($_POST[reszletes]).", prg_arkat='".$_POST[arkat]."', prg_megjelenik='".pipa($_POST[fch1]).pipa($_POST[fch2]).pipa($_POST[fch3]).pipa($_POST[fch4]).pipa($_POST[fch5])."NNNNN'";
		$query=$query." where prg_id=".$_POST[mid]; 
		mysql_query($query) or die ("A módosítás nem sikerült!". $query);
		unset($_GET['mozgat']);
}


//szûrés állapot elõállítása (a felvételben is használjuk, ezért kell itt)
$query="select tmp_s, tmp_i1 from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='program'";
$eredm=mysql_query($query);  //query futtatása
$sor=mysql_fetch_array($eredm);
//$keres1=$sor['tmp_s'];	
$keres2=$sor['tmp_i1'];


if(isset($_GET['mozgat'])) {
			$query2="select * from a_programok where prg_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtatása
			$sor2=mysql_fetch_array($eredm2);

			$mozgatid=$_GET['mozgat'];			$gnev='modosit';				$felirat='Módosít';					$tilt=' disabled';					$szin='#ffcc66';
			$nap=$sor2['prg_nap'];				$ido=$sor2['prg_ido'];			$tip_id=$sor2['prg_tip_id'];		$hely_id=$sor2['prg_hely_id'];		$szem_id_1=$sor2['prg_szem_id_1'];		
			$szem_id_2=$sor2['prg_szem_id_2'];	$leiras=$sor2['prg_leiras'];	$leirlink=$sor2['prg_leirlink'];	$reszletes=$sor2['prg_reszletes'];	$arkat=$sor2['prg_arkat'];	
			if (substr($sor2['prg_megjelenik'],0,1)=='I') {$ch1='checked';} else {$ch1='';}		//A php kódban levõ substr -nél az elsõ karakter a 0-s sorszámú, de MySQL-ben 1-s!!!!!!!!!!!!!!!!!!!!
			if (substr($sor2['prg_megjelenik'],1,1)=='I') {$ch2='checked';} else {$ch2='';} 
			if (substr($sor2['prg_megjelenik'],2,1)=='I') {$ch3='checked';} else {$ch3='';} 
			if (substr($sor2['prg_megjelenik'],3,1)=='I') {$ch4='checked';} else {$ch4='';} 
			if (substr($sor2['prg_megjelenik'],4,1)=='I') {$ch5='checked';} else {$ch5='';} 
		 } 
	else {	
			$query="SELECT current_date() as datum"; 
			$eredm=mysql_query($query);  
			$sor=mysql_fetch_array($eredm);

			$mozgatid=0;						$gnev='felvesz';				$felirat='Felvesz';				$tilt='';						$szin='#669999';
			$nap=substr($sor["datum"],0,4);		$ido=':00';						$tip_id=1;						$hely_id=0;						$szem_id_1=0;		
			$szem_id_2=0;						$leiras='';						$leirlink='';					$reszletes='';					$arkat='ingyenes';	
			$ch1='checked';		 		$ch2='';				$ch3='';				$ch4='';				$ch5='';
		 }


echo "<form action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."' align='center'><tr><th width='90%'>";
echo "<table border='3'>";
echo "<tr><th><input type='hidden' id='mid' name='mid' value='".$mozgatid."'>Program típusa</th><th>Dátum</th><th>idõpont</th><th>Helyszín</th>";
echo "<tr><td>";
	$query = "SELECT tip_id, tip_nev, case when LENGTH(tip_tipus)>1 then concat(' (',tip_tipus,')') else '' end as tipus FROM a_tipusok where tip_fajta='program' and tip_del=0";
	$eredm = mysql_query($query);
	echo "<SELECT name='esemeny'>";
	while ($rek = mysql_fetch_array($eredm))
	{   if ($rek["tip_id"]==$tip_id) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["tip_id"]."' ".$sel.">".$rek["tip_nev"].$rek["tip_tipus"]."</OPTION>";
	}
	echo "</SELECT></td>";
echo "<td><input type='text' name='datum' size='10' maxlength='10' value='".$nap."-'></td>";
echo "<td><input type='text' name='ido' size='5' maxlength='5' value='".$ido."'></td>";
echo "<td>";
	$query = "SELECT hely_id, hely_megnev, hely_cim FROM a_helyszinek where hely_del=0 order by hely_tip_id, hely_megnev";
	$eredm = mysql_query($query);
	echo "<SELECT name='helyszin'>";
	while ($rek = mysql_fetch_array($eredm))
	{	if ($rek["hely_id"]==$hely_id) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["hely_id"]."' ".$sel.">".$rek["hely_megnev"]." (".$rek["hely_cim"].")</OPTION>";
	}
	echo "</SELECT></td>";
echo "</tr></table>";
echo "<table border='3'><tr><th>Elõadó 1</th><th>Elõadó 2 </th></tr><tr>";
echo "<td>";
	$query = "SELECT * FROM a_szemelyek where szem_del=0 order by szem_nev;";
	$eredm = mysql_query($query);
	echo "<SELECT name='eloado1'>";
	if ($szem_id_1==0) {echo "<OPTION value='0'></OPTION>";} else {echo "<OPTION value='0' selected></OPTION>";}
	while ($rek = mysql_fetch_array($eredm))
	{   if ($rek["szem_id"]==$szem_id_1) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["szem_id"]."' ".$sel.">".$rek["szem_nev"]."</OPTION>";
	}
	echo "</SELECT></td>";
echo "<td>";
	$query = "SELECT * FROM a_szemelyek where szem_del=0 order by szem_nev;";
	$eredm = mysql_query($query);
	echo "<SELECT name='eloado2'>";
	if ($szem_id_2==0) {echo "<OPTION value='0'></OPTION>";} else {echo "<OPTION value='0' selected></OPTION>";}
	while ($rek = mysql_fetch_array($eredm))
	{   if ($rek["szem_id"]==$szem_id_2) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["szem_id"]."' ".$sel.">".$rek["szem_nev"]."</OPTION>";
	}
	echo "</SELECT></td>";
echo "</tr></table>";
echo "<table border='3'><tr><th>Program leírása</th><th>Program linkje</th></tr><tr>";
echo "<td><input type='text' name='leir' size='100' maxlength='500' value='".$leiras."'></td>";
echo "<td><input type='text' name='leirlink' size='60' maxlength='250' value='".$leirlink."'></td>";
echo "</tr></table>";
echo "<table border='3'><tr><th>Részletes program</th><th>Belépõ ára</th></tr><tr>";
echo "<td><input type='text' name='reszletes' size='150' maxlength='500' value='".$reszletes."'></td>";
echo "<td><input type='text' name='arkat' size='15' maxlength='15' value='".$arkat."'></td>";
echo "</tr></table>";
echo "</th><th width='10%' ><table><tr><td>";
echo "<input type='checkbox' name='fch1' value='I' ".$ch1.">kassaiter<br>";
//echo "<input type='checkbox' name='fch2' value='I' ".$ch2." >kkakademia<br>";
//echo "<input type='checkbox' name='fch3' value='I' ".$ch3." >jakikapolna<br>";
//echo "<input type='checkbox' name='fch4' value='I' ".$ch4." >rozsafuzer<br>";
//echo "<input type='checkbox' name='fch5' value='I' ".$ch5." >Credo<br>";
echo "</td></tr><tr><td >";
echo "<input type='submit' name='".$gnev."' value='".$felirat."'>";
echo "</td></tr></table></th></tr></table>";
echo "</form>";




//lista és szûrés rész
//echo "<form action='admin_program.php' name='szures' method='get'>";
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




$query="select prg_id, prg_nap, prg_ido, hely_rnev, hely_megnev, hely_cim, coalesce(hely_link,'-') as hely_link, coalesce(F1.szem_nev,'-') AS sz1, coalesce(F1.szem_hiv,'-') as szh1, coalesce(F2.szem_nev, '-' ) as sz2, coalesce(F2.szem_hiv,'-') as szh2, ";
$query=$query."case when DATEDIFF(prg_nap,current_date)<0 then -1 when DATEDIFF(prg_nap,current_date)=0 then 0 when DATEDIFF(prg_nap,current_date)<7 then 1 when DATEDIFF(prg_nap,current_date)<31 then 2 else 3 end as friss, prg_del, prg_megjelenik, ";
$query=$query."prg_leiras, coalesce(prg_leirlink,'-') as link, DAYOFWEEK(prg_nap) as nap, prg_arkat, coalesce(prg_reszletes,'') as prg_reszletes, case when LENGTH(tip_tipus)>1 then concat(tip_nev,' (',tip_tipus,')') else tip_nev end as tip_nev ";
$query=$query."from a_programok P ";
$query=$query."  join a_helyszinek on hely_id=prg_hely_id ";
$query=$query."  join a_tipusok on prg_tip_id=tip_id ";
$query=$query."  LEFT JOIN a_szemelyek F1 ON P.prg_szem_id_1 = F1.szem_id ";
$query=$query."  LEFT JOIN a_szemelyek F2 ON P.prg_szem_id_2 = F2.szem_id ";
//$query=$query."where prg_del=0 and prg_nap>=current_date ";
$query=$query."order by prg_nap desc, prg_ido desc";
$eredm=mysql_query($query);  //query futtatása

echo "<table class='naptar'><tr><th></th><th>ID</th><th>Dátum</th><th>Idõpont</th><th>Típus</th><th>Helyszín</th><th>Elõadó 1</th><th>Elõadó 2</th><th>Leírás</th><th>Részletes leírás</th><th>Megjelenítendõ</th><th>Árkategória</th><th></th></tr>";
$v='</font>';
while($sor=mysql_fetch_array($eredm)) 
  {	if ($sor['prg_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
	if ($sor['link']!='-')  {$href="<a href='".$sor['link']."' target='_blank'>"; $hrefv='</a>';} else {$href=""; $hrefv='';}
		$del="<a href='admin_program.php?delid=".$sor['prg_id']."'><img src='".$kep.".png' height='15'></a>";
		$mod="<a href='admin_program.php?mozgat=".$sor['prg_id']."'><img src='mozgat.png' height='15'></a>";
		echo "<tr><td>".$del."</td><td>".$szin.$sor['prg_id'].$v."</td><td>".$szin.$sor['prg_nap'].$v."</td><td>".$szin.$sor['prg_ido'].$v."</td><td>".$szin.$sor['tip_nev'].$v."</td>";
		echo "<td>".$szin.$sor['hely_cim'].$v."</td><td>".$szin.$sor['sz1'].$v."</td><td>".$szin.$sor['sz2'].$v."</td>";   
		echo "<td>".$szin.$href.$sor['prg_leiras'].$hrefv.$v."</td><td>".$szin.$sor['prg_reszletes'].$v."</td><td align='left'>";
		if (substr($sor['prg_megjelenik'],0,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>kassaiter<br>";
		if (substr($sor['prg_megjelenik'],1,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>kkakademia<br>";
		if (substr($sor['prg_megjelenik'],2,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>jakikapolna<br>";
		if (substr($sor['prg_megjelenik'],3,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>rozsafuzer<br>";
		if (substr($sor['prg_megjelenik'],4,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>Credo<br>";
//		if (substr($sor['prg_megjelenik'],5,1)=='I') {$ch='checked';} else {$ch='';} echo "<input type='checkbox' ".$ch." disabled>kassaiter<br>";
		echo "</td><td>".$szin.$sor['prg_arkat'].$v."</td>";

		echo "<td>".$mod."</td></tr>";
	  }
echo "</table>";

echo "</body>";
echo "</HTML>";
?>