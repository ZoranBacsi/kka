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
	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='menu'";
	mysql_query($query)  or die ("A törlés sikertelen!". $query);
	$query="insert into a_temp (tmp_user, tmp_menu, tmp_s, tmp_i1) values (".$_SESSION["userid"].",'menu','".$_GET['menutip']."','".$_GET['sorba']."')";
	mysql_query($query)  or die ("Az insert sikertelen!". $query);
}


if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_menuk set m_del=1-m_del where m_id=".$id);
	if($query1){header('location:admin_menu.php');}
}


if(isset($_GET['sid']))
{	$id=$_GET['sid'];
	$ssz=$_GET['s'];
	$irany=$_GET['irany'];
	$tip=$_GET['tip'];
	if ($_GET['irany']=='le') {
		$query="update a_menuk set m_sorrend=".$ssz." where m_sorrend=".$ssz."+1 and concat(coalesce(m_szulo_m_id,'0'),m_hely)='".$tip."'";
		mysql_query($query)  or die ("Az upd1 sikertelen!". $query);
		$query="update a_menuk set m_sorrend=".$ssz."+1 where m_id=".$id;
		mysql_query($query)  or die ("Az upd2 sikertelen!". $query);
	}
	if ($_GET['irany']=='fel') {
		$query="update a_menuk set m_sorrend=".$ssz." where m_sorrend=".$ssz."-1 and concat(coalesce(m_szulo_m_id,'0'),m_hely)='".$tip."'";
		mysql_query($query)  or die ("Az upd1 sikertelen!". $query);
		$query="update a_menuk set m_sorrend=".$ssz."-1 where m_id=".$id;
		mysql_query($query)  or die ("Az upd2 sikertelen!". $query);
	}
}



if(isset($_POST['felvesz']))
{
		if ($_POST[szulo]==0) {$szid='Null';} else {$szid=$_POST[szulo];}
		if ($_POST[mhely]=='ADMINMENU') {$mjog='A-';} else {$mjog='  ';}
		$query="insert into a_menuk (m_szulo_m_id, m_nev, m_kep_link, m_hely, m_stilus, m_target, m_url, m_del, m_jog) values (".$szid.",".nulloz(ekezetcsere($_POST[menunev])).",".nulloz(ekezetcsere($_POST[menukeplink])).",'".$_POST[mhely]."','".$_POST[stilus]."','".$_POST[megjelen]."',".nulloz(ekezetcsere($_POST[megnyitlink])).",0,'".$mjog."')";
		mysql_query($query) or die ("A felvétel sikertelen!". $query);

		$query="select coalesce(SZ.m_szint,0)+1 as szint, ssz, U.m_id as id ";
		$query=$query."from (SELECT max(m_sorrend)+1 as ssz, concat(coalesce(m_szulo_m_id,0),m_hely) tip FROM a_menuk group by concat(coalesce(m_szulo_m_id,0),m_hely) ) M ";
		$query=$query."left join a_menuk SZ on SZ.m_id=".$szid." ";
		$query=$query."join a_menuk U on tip=concat(coalesce(U.m_szulo_m_id,0),U.m_hely) and U.m_sorrend=0 ";
		$eredm=mysql_query($query);  //query futtatása
		$sor=mysql_fetch_array($eredm); 
		$updid=$sor['id'];
		$updssz=$sor['ssz'];
		$updsz=$sor['szint'];
		$query="update a_menuk set m_szint=".$updsz.", m_sorrend=".$updssz.", m_stilus=case when m_stilus=0 then Null else m_stilus end where m_id=".$updid;
		mysql_query($query) or die ("A sorszám, szint beállítás sikertelen!". $query);
}




if(isset($_POST['modosit']))
{
		if ($_POST[szulo]==0) {$szid='Null';} else {$szid=$_POST[szulo];}
		$query="update a_menuk set m_szulo_m_id=".$szid.", m_nev='".$_POST[menunev]."', m_kep_link=".nulloz(ekezetcsere($_POST[menukeplink])).", m_stilus='".$_POST[stilus]."',";
		$query=$query." m_target='".$_POST[megjelen]."', m_url=".nulloz(ekezetcsere($_POST[megnyitlink])); 
		$query=$query." where m_id=".$_POST[mid]; 
		mysql_query($query) or die ("A módosítás nem sikerült!". $query);
		unset($_GET['mozgat']);
}



//szûrés állapot elõállítása (a felvételben is használjuk, ezért kell itt)
$query="select tmp_s, tmp_i1 from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='menu'";
$eredm=mysql_query($query);  //query futtatása
$sor=mysql_fetch_array($eredm);
$hely=$sor['tmp_s'];	
$sorrend=$sor['tmp_i1'];	

$orderby="ORDER BY m_hely, m_szint, szinszint2, szinszint, m_sorrend";
if ($hely=='')		{$hely='MENU';}
if ($sorrend=='')	{$sorrend=1;}
if ($sorrend==2)	{$orderby="ORDER BY szinszint, m_hely, m_szint, m_szulo_m_id, m_sorrend";}

if ($hely=='MENU')		{$szint=3; $mneven='';			$murlen='readonly';	$stilusen='disabled';	$stilusdef='';		$holjelenen='';			$holjelendef='frame_kozep';		} 
if ($hely=='ADMINMENU') {$szint=1; $mneven='';			$murlen='readonly';	$stilusen='disabled';	$stilusdef='';		$holjelenen='disabled';	$holjelendef='frame_kozep';		} 
if ($hely=='BSZ_LENT1') {$szint=1; $mneven='readonly';	$murlen='';			$stilusen='';			$stilusdef='gomb';	$holjelenen='disabled';	$holjelendef='';				} 
if ($hely=='BSZ_LENT2') {$szint=1; $mneven='readonly';	$murlen='';			$stilusen='';			$stilusdef='gomb';	$holjelenen='disabled';	$holjelendef='';				} 

if(isset($_GET['mozgat'])) {
			$query2="select * from a_menuk where m_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtatása
			$sor2=mysql_fetch_array($eredm2);			//$sor2['']

			$mozgatid=$_GET['mozgat'];				$gnev='modosit';					$felirat='Módosít';						$tilt=' disabled';						$szin='#ffcc66';
			$szuloid=$sor2['m_szulo_m_id'];			$menunev=$sor2['m_nev'];			$menulink=$sor2['m_kep_link'];			$stilusval=$sor2['m_stilus'];			$holjelenval=$sor2['m_target'];			$url=$sor2['m_url'];
		 } 
	else {	$mozgatid=0;							$gnev='felvesz';					$felirat='Felvesz';						$tilt='';								$szin='#669999';		
			$szuloid=0;								$menunev='';						$menulink='';							$stilusval=$stilusdef;					$holjelenval=$holjelendef;				$url='';
		 }



echo "<p>Oda lehet új menüpontot (elemet) felvenni, amelyek lenti listában láthatóak.</p>";

echo "<form action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."' align='center'><tr><th width='90%'>";
	echo "<table border='3'><tr><th>Szülõ(menü)</th><th>Menü neve</th><th>Kép URL</th><th>Menü</th></tr>";
		echo "<tr><td><input type='hidden' id='mid' name='mid' value='".$mozgatid."'>";
	
		$query = "SELECT M1.m_id, concat(M1.m_nev,'(',M1.m_szint,'. szint)',case when M2.m_nev is not null then concat(M2.m_nev,' almenüje' ) else '' end) as szulonev ";
		$query = $query."FROM a_menuk M1 left join a_menuk M2 on M1.m_szulo_m_id=M2.m_id ";
		$query = $query."where M1.m_hely='".$hely."' and M1.m_del=0 and M1.m_szint<".$szint." order by M1.m_szint, M2.m_sorrend, M1.m_sorrend, M1.m_nev";
		$eredm = mysql_query($query);
		echo "<SELECT name='szulo' >";
		if ($szuloid==0) {echo "<OPTION value='0' selected>Nincs szülõ (0. szint)</OPTION>";} else {echo "<OPTION value='0'>Nincs szülõ (0. szint)</OPTION>";}
		while ($rek = mysql_fetch_array($eredm)) 
			{	if ($szuloid==$rek["m_id"]) {$sel=' selected';} else {$sel='';}
				echo "<OPTION value='".$rek["m_id"]."' ".$sel.">".$rek["szulonev"]."</OPTION>";	}
		echo "</SELECT></td>";

		echo "<td><input type='text' name='menunev' size='20' maxlength='100' value='".$menunev."' ".$mneven."></td>";
	
		echo "<td><input type='text' name='menukeplink' size='40' maxlength='250' value='".$menulink."' ".$murlen."></td>";

		echo "<td><input type='text' name='mhely'  value='".$hely."' readonly></td>"; 
	echo "</tr></table>";

	echo "<table border='3'><tr><th>Stílus</th><th>Megjelenés helye</th><th>Megjelenítendõ URL</th></tr>";

		echo "<tr><td><SELECT name='stilus'>";
			if ((''==$stilusdef and $stilusen=='disabled') or ($stilusen=='')) {if (''==$stilusdef)		{echo "<OPTION value='0' selected>-</OPTION>";}			else {echo "<OPTION value='0'>-</OPTION>";}}
			if (('gomb'==$stilusdef and $stilusen=='disabled') or ($stilusen=='')) {if ('gomb'==$stilusdef) {echo "<OPTION value='gomb' selected>gomb</OPTION>";}	else {echo "<OPTION value='gomb'>gomb</OPTION>";}}
		echo "</SELECT></td>";

		$query = "SELECT coalesce(m_target,'') as m_target FROM a_menuk group by m_target order by m_target";
		$eredm = mysql_query($query);
		echo "<td><SELECT name='megjelen'>";
		while ($rek = mysql_fetch_array($eredm)) 
		{	if (($rek['m_target']==$holjelendef and $holjelenen=='disabled') or ($holjelenen=='')) 
				{if ($rek['m_target']==$holjelendef) {echo "<OPTION value='".$rek["m_target"]."' selected>".$rek["m_target"]."</OPTION>";	} 
					else {echo "<OPTION value='".$rek["m_target"]."'>".$rek["m_target"]."</OPTION>";	}}
					}
		echo "</SELECT></td>";

		echo "<td><input type='text' name='megnyitlink' size='40' maxlength='250' value='".$url."'></td>";
	echo "</tr></table>";
echo "</th><th width='10%' >";
echo "<input type='submit' name='".$gnev."' value='".$felirat."'>";	
echo "</th></tr></table>";
echo "</form>";





//lista és szûrés rész

echo "<form action='admin_menu.php' name='szures' method='get'>";
echo "<table border='0' bgcolor='gray' align='center'><tr><th >";
	echo "<table border='3'>";
	echo "<tr><th>Menü helye</th><th>Sorrend</th>";
	echo "<tr><td>";
		$query = "SELECT * FROM a_menutipus where mtip_tip='MENU' order by mtip_hely";
		$eredm = mysql_query($query);
		echo "<SELECT name='menutip'>";
		while ($rek = mysql_fetch_array($eredm))
		{
			if ($rek["mtip_hely"]==$hely) {$sel=" selected";} else {$sel="";}
			echo "<OPTION value='".$rek["mtip_hely"]."' ".$sel.">".$rek["mtip_hely"]."</OPTION>";
		}
		echo "</SELECT></td>";
	echo "<td>";
		echo "<SELECT name='sorba'>";
		if ($sorrend==1) {$sel=" selected";} else {$sel="";}
		echo "<OPTION value='1' ".$sel.">Szintenként</OPTION>";
		if ($sorrend==2) {$sel=" selected";} else {$sel="";}
		echo "<OPTION value='2' ".$sel.">Menü-almenüi</OPTION>";
		echo "</SELECT></td>";
	echo "</tr></table>";
echo "</th><th  >";
echo "<input type='submit' name='szures' value='szures'>";
echo "</th></tr></table>";
echo "</form>";


$query="select T.*, M.*, coalesce(T2.m_sorrend,T1.m_sorrend,T.m_sorrend) as szinszint, coalesce(T1.m_sorrend,T.m_sorrend) as szinszint2 ";
$query=$query."from a_menuk T ";
$query=$query."left join a_menuk T1 on T1.m_id=T.m_szulo_m_id ";
$query=$query."left join a_menuk T2 on T2.m_id=T1.m_szulo_m_id ";
$query=$query."left join (SELECT min(m_sorrend) as minssz, max(m_sorrend) as maxssz, concat(coalesce(m_szulo_m_id,0),m_hely) tip FROM a_menuk group by concat(coalesce(m_szulo_m_id,0),m_hely) ) M on ";
$query=$query." M.tip=concat(coalesce(T.m_szulo_m_id,0),T.m_hely) ";
$query=$query."where T.m_hely='".$hely."' ".$orderby; 
//echo $query."<br>";
$eredm=mysql_query($query);  //query futtatása

echo "<table class='naptar2'><tr><th></th><th>ID</th><th>Szülõ</th><th>Szint</th><th>Menünév</th><th>vagy kép URL</th><th>Hely</th><th colspan='3'>Sorrend</th><th>Stílus</th><th>Hol jelenik meg</th><th>Mi jelenik meg</th><th></th></tr>";
$v='</font>';
while($sor=mysql_fetch_array($eredm)) 
  {		//if ($sorrend==1) {$szinvizsgal=$sor['szinszint'];} else {if ($sor['m_szint']==2) {$szinvizsgal=$sor['m_sorrend'];} else {$szinvizsgal=$sor['szinszint2'];}}
		$szinvizsgal=$sor['szinszint'];
		switch ($szinvizsgal % 10)	{
			case 1 : $bgszin="#ffff00";		break;  
			case 2 : $bgszin="#ccffcc";		break;  
			case 3 : $bgszin="#ffcc99";		break;  
			case 4 : $bgszin="#ccff66";		break;  
			case 5 : $bgszin="#66ffff";		break;  
			case 6 : $bgszin="#99cc00";		break;  
			case 7 : $bgszin="#00ccff";		break;  
			case 8 : $bgszin="#ff9900";		break;  
			case 9 : $bgszin="#9999ff";		break;   
			default: $bgszin="#ff99ff";		break;  		}

		if ($sor['m_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
		$del="<a href='admin_menu.php?delid=".$sor['m_id']."'><img src='".$kep.".png' height='10'></a>";
		if ($sor['m_sorrend']<$sor['maxssz']) {	$le ="<a href='admin_menu.php?sid=".$sor['m_id']."&s=".$sor['m_sorrend']."&irany=le&tip=".$sor['tip']."'><img src='le.png' height='15'></a> "; } else {$le='';}
		if ($sor['m_sorrend']>$sor['minssz']) {	$fel="<a href='admin_menu.php?sid=".$sor['m_id']."&s=".$sor['m_sorrend']."&irany=fel&tip=".$sor['tip']."'><img src='fel.png' height='15'></a>";} else {$fel='';}

		echo "<tr bgcolor='".$bgszin."'><td>".$del."</td><td>".$szin.$sor['m_id'].$v."</td><td>".$szin.$sor['m_szulo_m_id'].$v."</td><td>".$szin.$sor['m_szint'].$v."</td><td>".$szin.$sor['m_nev'].$v."</td><td>".$szin.$sor['m_kep_link'].$v."</td><td>";
		echo $szin.$sor['m_hely'].$v."</td><td>".$fel."</td><td>".$sor['m_sorrend']."</td><td>".$le."</td><td>".$szin.$sor['m_stilus'].$v."</td><td>";
		echo $szin.$sor['m_target'].$v."</td><td>".$szin.$sor['m_url'].$v."</td><td><a href='admin_menu.php?mozgat=".$sor['m_id']."'><img src='mozgat.png' height='10'></a></td></tr>";
	  }

echo "</table>";


echo "</body>";
echo "</HTML>";
?>