<?php
 session_start();
 if (!isset( $_SESSION["userid"])) die("Nincs bejelentkezve!");

require("../a_kapcs.inc.cS7.php");
require("../a_ellenorzes.inc.php");
dbkapcs();


echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<body>";

if(isset($_GET['helyszin']))
{	
	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='esm'";
	mysql_query($query)  or die ("A törlés sikertelen!". $query);
	$query="insert into a_temp (tmp_user, tmp_menu, tmp_i1, tmp_i2) values (".$_SESSION["userid"].",'esm','".$_GET['helyszin']."','".$_GET['szemely']."')";
	mysql_query($query)  or die ("Az insert sikertelen!". $query);
}

if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_esemenyek set esm_del=1-esm_del where esm_id=".$id);
	if($query1){header('location:admin_esemeny.php');}
}



if(isset($_POST['felvesz']))
{
	if ( (ures($_POST["datum"])) || (ures($_POST["ido"])) ) 	{		echo "A mezõk kitöltése kötelezõ!";	}
	else
 	{
		$query="insert into a_esemenyek (esm_nap, esm_ido, esm_tip_id, esm_hely_id, esm_eveg_id, esm_szemely1, esm_szemely2, esm_perc, esm_rogzitette, esm_del) 
			values ('".$_POST[datum]."','".$_POST[ido]."',".$_POST[esemeny].",".$_POST[helyszin].",".$_POST[vegzo].",".nulloz(ekezetcsere($_POST[szemely1])).",".nulloz(ekezetcsere($_POST[szemely2])).",".$_POST[becsido].",'".$_SESSION["userid"]."',1)";
		mysql_query($query) or die ("A felvétel sikertelen!". $query);
	}
}


if(isset($_POST['modosit']))
{
		$query="update a_esemenyek set esm_nap='".$_POST[datum]."', esm_ido='".$_POST[ido]."', esm_hely_id=".$_POST[helyszin].", esm_eveg_id=".$_POST[vegzo];
		$query=$query.", esm_szemely1=".nulloz(ekezetcsere($_POST[szemely1])).", esm_szemely2=".nulloz(ekezetcsere($_POST[szemely2])).", esm_perc=".$_POST[becsido]; 
		$query=$query." where esm_id=".$_POST[mid]; 
		mysql_query($query) or die ("A módosítás nem sikerült!". $query);
		unset($_GET['mozgat']);
}


echo "<script>"; 
echo "function changeText()"; 
echo "{ ";
echo "var x = document.getElementById('esemeny').selectedIndex;";
echo "  window.document.getElementById('szemely1').removeAttribute('disabled'); window.document.getElementById('szemely2').removeAttribute('disabled');"; 
echo "  switch (document.getElementsByTagName('option')[x].value) {"; 
echo "  case '2':  window.document.getElementById('szem1').textContent = 'Menyasszony neve:';	window.document.getElementById('szem2').textContent = 'Võlegény neve:'; break;"; 
echo "  case '3':  window.document.getElementById('szem1').textContent = 'Keresztelendõ';		window.document.getElementById('szem2').textContent = 'Keresztszülõk';	break;"; 
echo "  case '4':  window.document.getElementById('szem1').textContent = 'Miseszándék:';		window.document.getElementById('szem2').textContent = '-';				window.document.getElementById('szemely2').setAttribute('disabled','disabled'); break;"; 
echo "  case '5':  window.document.getElementById('szem1').textContent = 'Miseszándék:';		window.document.getElementById('szem2').textContent = '-';				window.document.getElementById('szemely2').setAttribute('disabled','disabled'); break;"; 
echo "  case '10': window.document.getElementById('szem1').textContent = 'Elhunyt neve:';		window.document.getElementById('szem2').textContent = '-';				window.document.getElementById('szemely2').setAttribute('disabled','disabled'); break;"; 

$query = "SELECT tip_id FROM a_tipusok where tip_fajta='esemény' and tip_tipus='N' and tip_del=0 order by tip_id";
$eredm = mysql_query($query);
while ($rek = mysql_fetch_array($eredm))
{	echo "  case '".$rek['tip_id']."': window.document.getElementById('szem1').textContent = 'Meddig tart (vagy ezt, vagy az egyéb információ mezõt töltse!):';	window.document.getElementById('szem2').textContent = 'Egyéb információ (pl. délelõtt misék után)';		break;"; }
//echo "  case '1034': window.document.getElementById('szem1').textContent = 'Meddig tart (vagy ezt, vagy az egyéb információ mezõt töltse!):';	window.document.getElementById('szem2').textContent = 'Egyéb információ (pl. délelõtt misék után)';		break;"; 
echo "  default: window.document.getElementById('szem1').textContent = 'Egyéb információ:';		window.document.getElementById('szem2').textContent = '-';				window.document.getElementById('szemely2').setAttribute('disabled','disabled'); break;"; 
echo "}"; 
echo "}"; 
echo "</script>"; 




$query="SELECT current_date() as datum"; 
$eredm=mysql_query($query);  
$sor=mysql_fetch_array($eredm);


if(isset($_GET['mozgat'])) {
			$query2="select * from a_esemenyek JOIN a_tipusok ON esm_tip_id = tip_id where esm_id=".$_GET['mozgat'];
			$eredm2=mysql_query($query2);  //query futtatása
			$sor2=mysql_fetch_array($eredm2);
			$sor2[''];

			$mozgatid=$_GET['mozgat'];
			$gnev='modosit';
			$felirat='Módosít';
			$tilt=' disabled';
			$szin='#ffcc66';

			$esem=$sor2['tip_nev'];;
			$ev=$sor2['esm_nap'];
			$hanykor=$sor2['esm_ido'];
			$kid=$sor2['esm_eveg_id'];
			$hid=$sor2['esm_hely_id'];
			$s1=$sor2['esm_szemely1'];
			$s2=$sor2['esm_szemely2'];
			$p=$sor2['esm_perc'];
		 } 
	else {	$mozgatid=0;
			$gnev='felvesz';
			$felirat='Felvesz';
			$tilt='';
			$szin='#669999';

			$esem='Esküvõ';
			$ev=substr($sor["datum"],0,4).'-';
			$hanykor='00:00';
			$kid='-1';
			$hid='';
			$s1='';
			$s2='';
			$p='60';
		 }



echo "<form action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."' align='center'><tr><th width='90%'>";
echo "<table border='3'>";
echo "<tr><th>Esemény típusa</th><th>Dátum</th><th>Kezdõ idõpont</th><th>Végzi</th><th>Becsült idõtartam [perc]</th><th>Helyszín</th>";
echo "<tr><td><input type='hidden' id='mid' name='mid' size='60' maxlength='80' value='".$mozgatid."'>";
	$query = "SELECT tip_id, tip_nev, tip_tipus FROM a_tipusok where tip_fajta='esemény' and tip_del=0 order by tip_nev";
	$eredm = mysql_query($query);
	echo "<SELECT name='esemeny' id='esemeny' onchange='changeText();'".$tilt.">";
	while ($rek = mysql_fetch_array($eredm))
	{	if ($esem==$rek["tip_nev"]) {$sel=' selected';} else {$sel='';}
		echo "<OPTION value='".$rek["tip_id"]."'".$sel.">";
		if (($rek["tip_tipus"]!='-') and ($rek["tip_tipus"]!='N')) {echo $rek["tip_nev"]."(".$rek["tip_tipus"].")</OPTION>";}
		else {echo $rek["tip_nev"]."</OPTION>";}
	}
	echo "</SELECT></td>";
echo "<td><input type='text' name='datum' size='10' maxlength='10' value='".$ev."'></td>";
echo "<td><input type='text' name='ido' size='5' maxlength='5' value='".$hanykor."'></td>";
echo "<td>";
	$query = "SELECT eveg_id, eveg_tnev FROM a_esemeny_vegzo where eveg_del=0";
	$eredm = mysql_query($query);
	echo "<SELECT name='vegzo'>";
	if ($kid==-1) {$sel=' selected';} else {$sel='';}
	echo "<OPTION value='-1'".$sel.">-</OPTION>";
	while ($rek = mysql_fetch_array($eredm))
	{   if ($kid==$rek["eveg_id"]) {$sel=' selected';} else {$sel='';}
		echo "<OPTION value='".$rek["eveg_id"]."'".$sel.">".$rek["eveg_tnev"]."</OPTION>";
	}
	echo "</SELECT></td>";
echo "<td><input type='text' name='becsido' size='5' maxlength='5' value='".$p."'></td>";
echo "<td>";
	$query = "SELECT hely_id, hely_megnev, hely_rnev, hely_cim FROM a_helyszinek where hely_del=0 and hely_naploba='I' order by hely_tip_id, hely_megnev";
	$eredm = mysql_query($query);
	echo "<SELECT name='helyszin'>";
	while ($rek = mysql_fetch_array($eredm))
	{   if ($hid==$rek["hely_id"]) {$sel=' selected';} else {$sel='';}
		echo "<OPTION value='".$rek["hely_id"]."'".$sel.">";
		echo $rek["hely_rnev"]." (".$rek["hely_cim"].")</OPTION>";
	}
	echo "</SELECT></td>";
echo "</tr></table>";

echo "<table border='3'><tr><td id='szem1'>Menyasszony neve:</td><td id='szem2'>Võlegény neve:</td></tr><tr>";
echo "<td><input type='text' id='szemely1' name='szemely1' size='60' maxlength='80' value='".$s1."'></td>";
echo "<td><input type='text' id='szemely2' name='szemely2' size='60' maxlength='80' value='".$s2."'></td>";
echo "</tr></table>";


echo "</th><th width='10%' >";
echo "<input type='submit' name='".$gnev."' value='".$felirat."'>";	

echo "</th></tr></table>";

echo "</form>";





//lista és szûrés rész
	$query="select tmp_i1, tmp_i2 from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='esm'";
	$eredm=mysql_query($query);  //query futtatása
	$sor=mysql_fetch_array($eredm);
	$helyszin=$sor['tmp_i1'];	
	$szemely=$sor['tmp_i2'];	

if ($helyszin==0) {$helyszin=-1;}
if ($szemely==0) {$szemely=-1;}

$where=' (1=1 and ';
if ($szemely==-1) 
	 {	$where=' (1=1 and ';	}
else {	$where=' (esm_eveg_id='.$szemely.' and ';	}

if ($helyszin==-1) 
	 {	$where=$where.' 1=1) ';	}
else {	$where=$where.' esm_hely_id='.$helyszin.') ';	}



echo "<form action='admin_esemeny.php' name='szures' method='get'>";
echo "<table border='0' bgcolor='gray' align='center'><tr><th width='90%'>";
echo "<table border='3'>";
echo "<tr><th>Személy</th><th></th><th>Helyszín</th>";
echo "<tr><td>";
	$query = "SELECT eveg_id, eveg_tnev FROM a_esemeny_vegzo where eveg_del=0";
	$eredm = mysql_query($query);
	echo "<SELECT name='szemely'>";
	if ($szemely==-1) {	echo "<OPTION value='-1' selected> - </OPTION>";} else {echo "<OPTION value='-1'> - </OPTION>";}

	while ($rek = mysql_fetch_array($eredm))
	{
		if ($rek["eveg_id"]==$szemely) {$sel=" selected";} else {$sel="";}
		echo "<OPTION value='".$rek["eveg_id"]."' ".$sel.">".$rek["eveg_tnev"]."</OPTION>";
	}
	echo "</SELECT></td>";
echo "<td>és/vagy</td>";
echo "<td>";
	$query = "SELECT hely_id, hely_megnev, hely_rnev, hely_cim FROM a_helyszinek where hely_del=0 and hely_naploba='I' order by hely_tip_id, hely_megnev";
	$eredm = mysql_query($query);
	echo "<SELECT name='helyszin'>";
	if ($helyszin==-1) {	echo "<OPTION value='-1' selected> - </OPTION>";} else {echo "<OPTION value='-1'> - </OPTION>";}
	while ($rek = mysql_fetch_array($eredm))
	{
		if ($rek["hely_id"]==$helyszin) {$sel=" selected";} else {$sel="";}
		echo "<OPTION value='".$rek["hely_id"]."' ".$sel.">";
		echo $rek["hely_rnev"]." (".$rek["hely_cim"].")</OPTION>";
	}
	echo "</SELECT></td>";
echo "</tr></table>";


echo "</th><th width='10%' >";
echo "<input type='submit' name='szures' value='szures'>";
echo "</th></tr></table>";

echo "</form>";




$query="select max(esm_nap) maxnap from a_esemenyek E where ".$where." and esm_del>=0 and esm_nap>=current_date()"; 
$eredm=mysql_query($query);  //query futtatása
$rekordok = mysql_num_rows($eredm);
$sor=mysql_fetch_array($eredm);
$maxnap=$sor["maxnap"];
while (date('w', strtotime($maxnap))!=0) {
	$maxnap = strtotime ( '+1 day' , strtotime ( $maxnap ) ) ;
	$maxnap = date ( 'Y-m-d' , $maxnap );
}
echo "<table class='naptar'><tr><th width='10%'>".napok('2')."</th><th width='10%'>".napok('3')."</th><th width='10%'>".napok('4')."</th><th width='10%'>".napok('5')."</th><th width='10%'>".napok('6')."</th><th width='10%'>".napok('7')."</th><th width='10%'>".napok('1')."</th></tr>";
$mostnap = date('Y-m-d');
while (date('w', strtotime($mostnap))!=1) {
	$mostnap = strtotime ( '-1 day' , strtotime ( $mostnap ) ) ;
	$mostnap = date ( 'Y-m-d' , $mostnap );
}
$oszlop = 1;

$v='</font>';
while (strtotime($mostnap)<=strtotime($maxnap)) {
	if ($oszlop==1) {echo "<tr>";}

  	  echo "<td><div class='keret'>".honapok(date ('m' , strtotime($mostnap) )).' '.date ('d' , strtotime($mostnap) )."</div>";
 	  $query="SELECT esm_ido mikor, T.tip_nev mit, coalesce( eveg_rnev,'') ki, esm_del, esm_id, esm_eveg_id, esm_hely_id, esm_szemely1, esm_szemely2, esm_perc ";
	  $query=$query."FROM a_esemenyek JOIN a_tipusok T ON esm_tip_id = tip_id LEFT JOIN a_esemeny_vegzo ON eveg_id = esm_eveg_id where ".$where;
	  $query=$query." and esm_del>=0 and esm_nap='".$mostnap."' order by esm_ido"; 
	  $eredm=mysql_query($query);  //query futtatása
	  echo "<table>";
	  while($sor=mysql_fetch_array($eredm)) 
	  { 
		echo "<tr><td>";
		if ($sor["ki"]=='') {$reszki='';} else {$reszki=' ('.$sor["ki"].')';}
		if ($sor['esm_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
		if ($sor['mit']!='Mise') {$mozgat="<td><a href='admin_esemeny.php?mozgat=".$sor['esm_id']."'><img src='mozgat.png' height='10'></a></td>";} else {$mozgat='';}
		echo "<a href='admin_esemeny.php?delid=".$sor['esm_id']."'><img src='".$kep.".png' height='10'></a></td><td><div class='lista'>".$szin.$sor["mikor"].' - '.$sor["mit"].$reszki.$v;
		echo "</div></td>".$mozgat."</tr>";
	  }
      echo "</table>";


	echo "</td>";
	$mostnap = strtotime ( '+1 day' , strtotime ( $mostnap ) ) ;
	$mostnap = date ( 'Y-m-d' , $mostnap );
	if ($oszlop==7) {echo "</tr>"; $oszlop=1;}
		else {	$oszlop++;}

}
echo "</table>";


echo "</body>";
echo "</HTML>";
?>