<?php

echo "<HTML><HEAD>";
echo "<meta charset='utf-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link rel='icon' href='assets/img/favicon.ico' type='image/ico' sizes='16x16'>";
echo "<link type='text/css' rel='stylesheet' media='all' href='assets/css/bootstrap.min.css'>";
echo "<link type='text/css' rel='stylesheet' media='all' href='assets/css/bootstrap-theme.min.css'>";
echo "<link type='text/css' rel='stylesheet' media='all' href='assets/css/main.css'>";
echo "<script src='assets/js/jquery-3.1.1.min.js'></script>";
echo "<script src='assets/js/bootstrap.min.js'></script>";
echo "<script src='assets/js/api.js'></script>";
echo "</HEAD>";

echo "<body onload=meret()><script>";
echo "function meret()";
echo "{window.parent.document.getElementById('onlineradio').style.height = document.getElementById('dtable').offsetHeight;}";
echo "</script>";

require("../a_kapcs.inc.cS7.php");
dbkapcs();


echo "<p style='text-align:center;'><img src='/assets/img/gomb_cimkek/iranyatermeszet.png' alt='' align='middle'/></p>";
echo "<p>Ez az oldal arra szolgál, hogy közösségünk tagjai feltehessék a tervezett kirándulásokat és a többiek így csatlakozhassanak hozzájuk.<br>";
echo "<b>A kirándulások (szabadidõs programok) megjelenítése kérhetõ egy levél megírásávál: <a href='mailto:csernyilaszlo.kassaiter@gmail.com?Subject=irany a természet&body=Kedves Címzett!%0DKérlek a honlapra kirakni az alábbit:%0D%0D- Találkozó idõpontja:%0D- Találkozó helyszíne:%0D- A program rövid leírása:%0D- A program idõtartama:%0D- A program leírása:%0D- Jelentkezési határidõ:%0D- Kapcsolattartó adatai: (E-mail, név)%0D-Kedvcsinálóként egy url (nem kötelezõ):%0D%0DKöszönettel:'>Kirándulás beküldése</a></b></p>";

echo "<p style='text-align:center;'><img src='/assets/img/gomb_cimkek/aktualis_ajanlat.png' alt='' align='middle'/></p>";

$query="SELECT kir_id, case when kir_taldate>='9999-01-01 00:00:00' then 'Tervezés alatt' else kir_taldate end kir_taldate, kir_talhely, kir_nev, kir_leiras, kir_idotartam, case when kir_jelhatdate<current_timestamp() then concat(kir_jelhatdate,' volt, az esetleges módosulásokról az idõben jelentkezettek értesítve lettek/lesznek, a felületen nem biztos, hogy ez megjelenik!!!') else kir_jelhatdate end kir_jelhatdate, kir_kapcsolat, coalesce(kir_izelito,'-') as izelito "; 
$query=$query."FROM a_kirandulas where kir_del=0 and substr(kir_megjelenik,1,1)='I' and kir_taldate>=current_timestamp() ORDER BY coalesce(kir_jelhatdate,kir_taldate) ";
$query_eredm=mysql_query($query);  
$rekordok = mysql_num_rows($query_eredm);
if ($rekordok==0)
{echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='Aktuális programok' src='/assets/img/gomb_cimkek/nincs_adat.png' height=35 /></p>";}
else
{
echo "<tbody><table style='width:100%'>";
while($eredm=mysql_fetch_array($query_eredm)) 
{
	echo "<tr><th colspan='2' style='width: 100%; align=left'><p>Program megnevezése: ".$eredm["kir_nev"]."</p></th></tr>";
	echo "<tr>";
	echo "<td style='width: 40%; align=left'><p>Találkozó:<br>".$eredm["kir_taldate"]."<br>".$eredm["kir_talhely"]."<br><br>Idõtartam:<br>".$eredm["kir_idotartam"]."<br><br>Jelentkezési határidõ<br>".$eredm["kir_jelhatdate"]."<br><br>Kontakt (szervezõ)<br>".$eredm["kir_kapcsolat"]."</p></td>";
	echo "<td style='width: 60%;'><p>".$eredm["kir_leiras"]."</p>";
	if ($eredm["izelito"]!='-') 
	{	if (strpos(substr($eredm["izelito"],-4),'.')===false) {echo "<a href='".$eredm["izelito"]."' target='_blank'>Kedvcsináló</a>";}
		else {echo "<img src='".$eredm["izelito"]."' height='250'>";}
	}
	echo "</td>";
	echo "</tr>";
}
echo "</table>";
}

echo "<p style='text-align:center;'><img src='/assets/img/gomb_cimkek/korabbi.png' alt='' align='middle'/></p>";

$query="SELECT kir_id, kir_taldate, kir_talhely, kir_nev, kir_leiras, kir_idotartam, kir_jelhatdate, kir_kapcsolat, coalesce(kir_izelito,'-') as izelito "; 
$query=$query."FROM a_kirandulas where kir_del=0 and substr(kir_megjelenik,1,1)='I' and coalesce(kir_jelhatdate,kir_taldate)<current_timestamp() ORDER BY kir_taldate desc";

$query_eredm=mysql_query($query);  

$rekordok = mysql_num_rows($query_eredm);
if ($rekordok==0)
{echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='Aktuális programok' src='/assets/img/gomb_cimkek/nincs_adat.png' height=35 /></p>";}
else
{
echo "<table style='width:100%'>";
while($eredm=mysql_fetch_array($query_eredm)) 
{
	echo "<tr><th colspan='2' style='width: 100%; align=left'><p>Program megnevezése: ".$eredm["kir_nev"]."</p></th></tr>";
	echo "<tr>";
	echo "<td style='width: 40%; align=left'><p>Találkozó:<br>".$eredm["kir_taldate"]."<br>".$eredm["kir_talhely"]."<br><br>Idõtartam:<br>".$eredm["kir_idotartam"]."<br><br>Jelentkezési határidõ<br>".$eredm["kir_jelhatdate"]."<br><br>Kontakt (szervezõ)<br>".$eredm["kir_kapcsolat"]."</p></td>";
	echo "<td style='width: 60%;'><p>".$eredm["kir_leiras"]."</p>";
	if ($eredm["izelito"]!='-') 
	{	if (strpos(substr($eredm["izelito"],-4),'.')===false) {echo "<a href='".$eredm["izelito"]."' target='_blank'>Kedvcsináló</a>";}
		else {echo "<img src='".$eredm["izelito"]."' height='250'>";}
	}
	echo "</tr>";
}
echo "</table>";
}

echo "</body></HTML>";


?>