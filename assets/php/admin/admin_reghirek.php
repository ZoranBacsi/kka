<?php

 session_start();
 if (!isset( $_SESSION["userid"])) die("Nincs bejelentkezve!");

require("../a_kapcs.inc.cS7.php");
require("../a_ellenorzes.inc.php");
dbkapcs();

if(isset($_POST['felvesz']))
{	if ( (ures($_POST[ido])) )	{		echo "A mez�k kit�lt�se k�telez�!";	}
	else 	{
		$query="insert into a_reghirdetesek (hir_tip_id, hir_nap, hir_leiras) 
			values (".$_POST[tipus].",".nulloz($_POST[ido]).",".nosqlinjekt($_POST[hir]).")";
		mysql_query($query) or die ($query);	}
}


if(isset($_POST['modosit']))
{
//		$query="update a_reghirdetesek set hir_nap='".$_POST[ido]."', hir_leiras='".$_POST[hir]."' where hir_id=".$_POST[mid]; 
//		mysql_query($query) or die ("A m�dos�t�s nem siker�lt!". $query);
		unset($_GET['mozgat']);
}



if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_reghirdetesek set hir_del=1-hir_del where hir_id=".$id);
	if($query1){header('location: admin_reghirek.php');}}



$query="SELECT current_date() as datum"; 
$eredm=mysql_query($query);  
$sor=mysql_fetch_array($eredm);


if(isset($_GET['mozgat'])) {
			$mozgatid=$_GET['mozgat'];
			$gnev='modosit';
			$felirat='M�dos�t';
			$tilt=' disabled';
			$szin='#ffcc66';

			$mikor=$_GET['d'];
			$leir=$_GET['leiras'];
			$hol=$_GET['hol'];
		 } 
	else {	$mozgatid=0;
			$gnev='felvesz';
			$felirat='Felvesz';
			$tilt='';
			$szin='#669999';

			$mikor=substr($sor["datum"],0,4).'-';
			$leir='';
			$hol='Csal�di napk�zi';
		 }


echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<form action='' name='felvesz' method='post'>";
echo "<table border='3' bgcolor='".$szin."' align='center'><tr bgcolor='#D8D8D8'><th colspan='3'>H�r felv�tele</th></tr>";

echo "<tr><td>Hol jelenjen meg a h�r?<input type='hidden' id='id' name='id' value='".$mozgatid."'></td><td><select name='tipus' id='tipus'>";
$query = 'SELECT tip_id, tip_nev FROM a_regtipusok where tip_fajta="hirdet�s"';
$eredm = @mysql_query($query);
while ($rek = mysql_fetch_array($eredm))
    {	if ($rek['tip_nev']==$hol) {$sel=" selected";} else {$sel='';}	
		echo "<option value='".$rek['tip_id']."'".$sel.">".$rek['tip_nev']."</option>";    }
echo "</select></td><td rowspan='3'><input type='submit' name='felvesz' value='Felvesz'></td></tr>";
echo "<tr><td>Meddig aktu�lis a h�r?</td><td><input type='text' name='ido' size='10' maxlength='10' value='".$mikor."'></td></tr>";
echo "<tr><td>H�r sz�vege</td><td><textarea name='hir' rows='5' cols='80' maxlength='15000' >".$leir."</textarea></td></tr>";
echo "</table>";
echo "</form>";




echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='Inform�ci�k' src='/images/stories/Cikkekhez/aktualis.png' height=50 /></p>";

$query="select tip_nev, hir_id, hir_nap, hir_sorrend, hir_leiras, DATEDIFF(hir_nap,current_date) as d, hir_del from a_reghirdetesek join a_regtipusok on hir_tip_id=tip_id where hir_tip_id<>0 order by hir_nap desc, hir_sorrend ASC"; 
$eredm=mysql_query($query);  //query futtat�sa

echo "<table class='naptar'><tr><th></th><th>D�tum</th><th>H�r le�r�sa</th><th>H�r helye</th><th></th></tr>";
while($sor=mysql_fetch_array($eredm)) 
  {		
		if ($sor['hir_del']==0)  {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
		$del="<a href='admin_reghirek.php?delid=".$sor['hir_id']."'><img src='".$kep.".png' height='10'></a>";
//<a href='admin_reghirek.php?mozgat=".$sor['hir_id']."&d=".$sor['hir_nap']."&leiras=".$sor['hir_leiras']."&hol=".$sor['tip_nev']."'><img src='mozgat.png' height='10'></a>
		echo "<tr><td>".$del."</td><td>".$sor['hir_nap']."</td><td>".$sor['hir_leiras']."</td><td>".$sor['tip_nev']."</td><td></td></tr>";
	  }

echo "</table>";

echo "</HTML>";
?>