<?php

echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' />";
echo "<style>table table#t01 tr:nth-child(even) {background-color: #ddd;}";
echo "table#t01 tr:nth-child(odd) {background-color:#fff;}";
echo "table#t01 th	{background-color: black;color: white;}</style></HEAD>";
echo "<body onload=meret()><script>";
echo "function meret()";
echo "{window.parent.document.getElementById('konyvtarunk').style.height = document.getElementById('dtable').offsetHeight;}";
echo "</script>";


echo "<table id='dtable'><tr><td>";

require("a_kapcs.inc.cS7.php");
dbkapcs();

echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='Könyvtár' src='/images/stories/Cikkekhez/konyvtar.png' height='60'  /></p>";
echo "<p style='text-align: center;'><strong>A könyvtár szeretettel várja a Kassai téri gyülekezet tagjait!</strong></p>";
echo "<p style='text-align: center;'><strong>A könyvtári belépéshez (kölcsönzéshez) igazoló lapot kell kérni András atyától.</strong></p>";
echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='Nyitvatartás' src='/images/stories/Cikkekhez/nyitvatartas.png' height='60' /></p>";
echo "<p style='text-align: center;'>Vasárnaponként 10-12 óra között</p>";
echo "<p style='text-align: center;'>Keddenként 16 és 18 óra között.</p>";
echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='Katalógus' src='/images/stories/Cikkekhez/katalogus.png' height='60' /></p>";
echo "<p style='text-align: center;'>A lista még nem teljes. Az elektronikus katalógusért köszönet az Olvasókör mûködtetõinek!</p>";


if ($_GET['szerzo']=="") {$szerzo='';}
		else {$szerzo=$_GET['szerzo'];}
if ($_GET['cim']=="") {$cim='';}
		else {$cim=$_GET['cim'];}

if ($szerzo=='') 
	 {	$where=" (1=1 and ";	}
else {	$where=" (konyv_szerzo like '%".$szerzo."%' and ";	}
if ($cim=='') 
	 {	$where=$where." 1=1) ";	}
else {	$where=$where." konyv_cim like '%".$cim."%') ";	}

echo "<form action='konyvtar.php' name='szures' method='get'>";
echo "<table border='0' bgcolor='gray'><tr><th width='90%'>";
echo "<table border='3'>";
echo "<tr><th>Szerzõ</th><th></th><th>Cím</th>";
echo "<tr><td><input type='text' name='szerzo' value='".$szerzo."'></td>";
echo "<td>és/vagy</td>";
echo "<td><input type='text' name='cim' value='".$cim."'></td>";
echo "</tr></table>";
echo "</th><th width='10%' >";
echo "<input type='submit' name='szures' value='Szûrés'>";
echo "</th></tr></table>";
echo "</form>";





$query="SELECT konyv_ssz, konyv_cim, konyv_szerzo, konyv_kiado, konyv_ev FROM a_konyvtar where konyv_del=0 and ".$where." ORDER BY konyv_szerzo, konyv_cim"; 

echo "<table id='t01'><tbody>";
echo "<tr><th style='width: 10%;'>Sorszám</th>";
echo "<th style='width: 30%;' >Szerzõ(k)</th>";
echo "<th style='width: 30%;' >Könyv címe</th>";
echo "<th style='width: 15%;' >Kiadó neve</th>";
echo "<th style='width: 15%;' >Kiadás helye,éve</th></tr>";

$query_eredm=mysql_query($query);  
while($eredm=mysql_fetch_array($query_eredm)) 
{
	echo "<tr><td style='width: 10%;' align='left'>".$eredm["konyv_ssz"]."</td>";
	echo "<td style='width: 30%;' align='left'>".$eredm["konyv_szerzo"]."</td>";
	echo "<td style='width: 30%;' align='left'>".$eredm["konyv_cim"]."</td>";
	echo "<td style='width: 15%;' align='left'>".$eredm["konyv_kiado"]."</td>";
	echo "<td style='width: 15%;' align='left'>".$eredm["konyv_ev"]."</td></tr>";
}
echo "</table>";

echo "</body></HTML>";

?>